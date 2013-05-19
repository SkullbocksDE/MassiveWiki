<?php
/**
 * @brief		The extension 'FooterBar' will add a customizable bar at the bottom of each mediawiki page.
 * @details		The extension 'FooterBar' will add a customizable bar at the bottom of each mediawiki page.
 *				You can customize it via the MediaWiki:Common.css without touching the css-file.
 *				There are also some variables, which can be set in the LocalSettings.php.
 *				Read the README.md for more details.
 *
 * @ingroup 	Extensions
 * @author		Mike Knappe
 * @version		0.5
 * @copyright	© 2013 Mike Knappe
 * @license 	GNU General Public Licence 2.0 or later
 *
 * @bug (fixed): Extension was called multiple times by the hook 'SkinAfterContent', 'ParseAfterTidy' and 'ParseBeforeTidy',
 *               where as 'AfterFinalPageOutput' has done bullshit at the end of the page. Now using 'OutputPageParserOutput'.
 * @bug (fixed): Couldn't detect the global array.
 * @bug (fixed): It wasn't possible to skin the FooterBar. Now using the new ResourceLoader for CSS and JS.
 * @bug (fixed): It wasn't possible to use i18. Now included.
 * @bug (fixed): It wasn't possible to hide the bar. Javascript included.
 * @bug (fixed): It wasn't possible to hide the bar via value. New value added, to allow/deny hiding the bar.
 * @bug (fixed): It wasn't possible to use i18 for some text. Now all output is in i18.
 * @bug (fixed): There was no explanation of the usage of the global array. Readme is now avaible.
 * @bug (fixed): It wasn't possible to change the FooterBar content within the MediaWiki. Now using the page MediaWiki:FooterBar.
 *				 The variable '$footerBarPage' has to be set 'true' to use the page.
 * @bug (fixed): All content of the refered FooterBar page was parsed. Now extracting links.
 * @bug (fixed): Parser detected images as links. Now only extracting links.
 * @bug (fixed): It wasn't possible to customize the FooterBar as user. Now using the page 'User:Username/FooterBar'.
 *				 The variable '$footerBarUser' has to be set 'true' to allow users to customize the bar.
 * @bug (fixed): IP users were able to create a FooterBar for the ip. IP users will now be denied.
 * @bug (fixed): It wasn't possible to concatenate the certain areas like 'MediaWiki:FooterBar' and the global array.
 *				 The array '$footerBarConcat' will handle the concatenate. Possible values: 'array', 'page', 'user'
 *               The order of the entries in this array effects the concatenate order.
 *				 If the Concat is empty, the highest area will be selected: user >> page >> array
 * @bug (fixed): If wasn't possible to declare a link of the global array only for admins. New field 'groups' inserted.
 * @bug (fixed): There where no error messages from the script. Now added into source and i18.
 * @bug (fixed): On SpecialPages the FooterBar was hooked multiple time and it was added to the top. Now using again SkinAfterContent.
 * @bug (fixed): It wasn't possible to name a link on your own. Now added a new field 'title'.
 * @bug (fixed): It wasn't possible to give a link a certain id and class. Now added new fields 'id' and 'class'.
 * @bug (fixed): It wasn't possible to turn off the error messages and use them as some kind of debug. Now added value '$footerBarShowErrors'.
 * @bug (fixed): It wasn't possible to change the hideShow behaviour. JS is now checking for className.
 *				 CSS classes accessible via: '.footerBar.footerBarHide' and '.footerBarContent.footerBarContentHide'.
 * @bug (fixed): FooterBar wasn't displayed on SpecialPages, EditForms and Login. Hooks rearranged!
 *				 Now using to create the Bar 'ArticlePageDataAfter' and 'SpecialPageBeforeExecute' and to show 'SkinAfterContent'.
 *				 It also fixed the bug, that the JS was declared inline
 * @bug (fixed): The status of the bar wasn't saved during a session. Now using cookies via the jQuery#cookie.
 *				 To check the cookie there is a new inline JS, but only to call a head function. ^^
 * @bug (fixed): It wasn't possible to create menus for the FooterBar. New field added 'menu'.
 *				 The menu will be created at the position of the first match of a certain menuname.
 *
 * @bug (open) : The 'self' link doesn't work proper, because of the getTitle-function.
 * @bug (open) : If no value of '$footerBarConcat' fits, then the FooterBar is empty.
 * @bug (open) : New function of menu creation needs much better CSS.
 * @bug (open) : Let users create menus too.
 * @bug (open) : Catch possible menu errors.
 * @bug (open) : Get rid of the global value that stores the HTML of the FooterBar.
 * @bug (open) : Restructure the JS functions.
 *
 * @todo Create a more readable source code with code reduction by using loops.
 * @todo Test the FooterBar on other browsers.
 */

/**
 * @brief The FooterBar class
 */
class FooterBar{
	//Class constructor
	function __construct(){
		parent::__construct( 'FooterBar' );
	}
	
	//Check if MediaWiki is defined
	public static function checkForMediaWiki(){
		if( !defined('MEDIAWIKI') ){ 
			echo "To install the 'FooterBar' extension, put the following line in LocalSettings.php:";
			echo "<br><dl><dd>require_once( '\$IP/extensions/FooterBar/FooterBar.php' );</dd></dl>";
			echo "For more information read the <a href='./README.md'>README</a> :)";
			exit( 1 );
		}
	}
	
	//Initialize the needed variables etc.
	public static function initFooterBar( ){
		global $wgExtensionCredits, $wgHooks, $wgExtensionMessagesFiles, $wgResourceModules;
		
		//Credits of FooterBar (see Special:Version)
		$wgExtensionCredits['FooterBar'][] = array(
			'path' => __FILE__,
			'name' => 'FooterBar',
			'version' => '0.1',
			'author' => 'Mike Knappe',
			'descriptionmsg' => 'The extension FooterBar will add a fixed customizable bar at the bottom of each page.'
		);

		//Setup the needed hooks
		$wgHooks['ResourceLoaderRegisterModules'][] = 'FooterBar::resourceLoaderRegisterModules';
		$wgHooks['ArticlePageDataAfter'][] = 'FooterBar::createFooterBar';
		$wgHooks['SpecialPageBeforeExecute'][] = 'FooterBar::createFooterBar';
		$wgHooks['SkinAfterContent'][] = 'FooterBar::addFooterBar';

		//Define the internationalization file
		$wgExtensionMessagesFiles['FooterBar'] = dirname( __FILE__ ) . '/FooterBar.i18n.php';

		//Setup the needed resources via the ResourceLoader
		$wgResourceModules['FooterBar'] = array(
			'localBasePath' => dirname( __FILE__ ),
			'remoteExtPath' => 'FooterBar',
			'styles' => 'FooterBar.css',
			'scripts' => 'FooterBar.js',
		);
		return true;
	}
	
	//Register the previously added resources in the ResourceLoader
	public static function resourceLoaderRegisterModules( &$resourceLoader ) {
		global $wgOut;
		$wgOut->addModules( 'FooterBar' );
		return true;
	}
	
	//Extract only the link from a FooterBar page
	public static function extractLinksFromFooterBarPage( &$plaintext ) {
		$preLinkArr = explode('[[',$plaintext);
		if(count($preLinkArr)>1){
			array_shift($preLinkArr);
			$cArray = array();
			foreach($preLinkArr as $preLink){
				$linkArr = explode(']]',$preLink);
				if(strpos($linkArr[0],'File:')===false)
					$cArray[] = '[['.$linkArr[0].']]';
			}
			$plaintext = implode($cArray, wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain());
		}
		return true;
	}
	
	//Register the JS due to the variable $footerBarHideable (registration is still a workaround)
	public static function createFooterBar( $article, $row ){
		global $wgOut, $wgUser, $footerBarArray, $footerBarHideable, $footerBarPage, $footerBarUser, $footerBarConcat, $footerBarHtmlContent;
		if($footerBarHideable==true)
			$wgOut->addHeadItem("footerBar","<script type='text/javascript'>function hideshow(name){if(document.getElementById(name).style.display==\"none\"){document.getElementById(name).style.display=\"block\";}else{document.getElementById(name).style.display=\"none\";}} function initFooterBar(){ if( $.cookie( 'FooterBar' ) == 'Off' ){ document.getElementById(\"footerBarArrow\").innerHTML = \"".wfMessage( 'footerbar-arrow-open' )->inContentLanguage()->plain()."\";document.getElementById(\"footerBar\").className=\"footerBar footerBarHide\";document.getElementById(\"footerBarContent\").className=\"footerBarContent footerBarContentHide\"; } } function hideShowBar(){if(document.getElementById(\"footerBar\").className==\"footerBar\"){ $.cookie( 'FooterBar', 'Off', { expires: 7, path: '/'} ); document.getElementById(\"footerBarArrow\").innerHTML = \"".wfMessage( 'footerbar-arrow-open' )->inContentLanguage()->plain()."\";document.getElementById(\"footerBar\").className=\"footerBar footerBarHide\";document.getElementById(\"footerBarContent\").className=\"footerBarContent footerBarContentHide\";}else{ $.cookie( 'FooterBar', 'On', { expires: 7, path: '/'} ); document.getElementById(\"footerBarArrow\").innerHTML = \"".wfMessage( 'footerbar-arrow-close' )->inContentLanguage()->plain()."\";document.getElementById(\"footerBar\").className=\"footerBar\";document.getElementById(\"footerBarContent\").className=\"footerBarContent\";}}</script>");
		
		$contentHTML = "<div class='footerBar' id='footerBar'><div class='footerBarContent' id='footerBarContent'>";
		$barContentHTML = array('user'=>'','page'=>'','array'=>'');
		$titleObject = "";
		$titleObjArr = array();
		if( $footerBarUser==true && $wgUser->getId()!=0)
			$titleObjArr[] = Title::newFromText( 'User:'.$wgUser->getName().'/FooterBar' );
		if( $footerBarPage==true )
			$titleObjArr[] = Title::newFromText( 'MediaWiki:FooterBar' );
		foreach($titleObjArr as $titleObject){
			if($titleObject != "" && $titleObject->exists()){
				$pageObject = WikiPage::newFromId( $titleObject->getArticleID() );
				$footerTextPlain = $pageObject->getText();
				FooterBar::extractLinksFromFooterBarPage($footerTextPlain);
				$footerTextWiki = wfMessage( 'footerbar-content' )->params( $footerTextPlain )->inContentLanguage()->parse();
				$barContentHTML['user'] = $footerTextWiki;
			}
		}
		
		if( is_array($footerBarArray) ){
			$linkArray = array();
			$menuArray = array();
			$uGroups = $wgUser->getGroups();
			foreach($footerBarArray as $key=>$value){
				if(FooterBar::checkGlobalArrayValue($value, $key)){
					
					$groupGate = false;
					if(isset($value['groups']) && is_array($value['groups']) && count($value['groups'])>0){
						foreach($value['groups'] as $linkGroup){
							if($linkGroup==""){$groupGate=true; break;}
							foreach($uGroups as $uGroup){
								if($linkGroup==$uGroup){$groupGate=true; break;}
							}
						}
					}else{ $groupGate = true; }
					
					if( $groupGate ){
						$exGate = false;
						$titleObject = Title::newFromText( $value['link'] );
						$cString = '<a href="'.$titleObject->getLinkURL();
						if(isset($value['target']) && $value['target']!='')
							if($value['target']=='self')
								$cString .= '/'.$wgOut->getPageTitle();
							else
								$cString .= $value['target'];
						$cString .= '" ';
						
						$checkStrings = array('title', 'class', 'id');
						foreach($checkStrings as $checkString)
							if(isset($value[$checkString]) && $value[$checkString]!='')
								$cString .= ' '.$checkString.'="'.$value[$checkString].'" ';
						
						$cString .= '>';
						
						if(isset($value['name']) && $value['name']!='')
							$cString .= $value['name'];
						else
							$cString .= $value['link'];
						
						$cString .= '</a>';
						if(isset($value['menu']) && is_string($value['menu']) && $value['menu']!=''){
							if(!isset($menuArray[$value['menu']]))
								$menuArray[$value['menu']] = array();
							$menuArray[$value['menu']][] = $cString;
							$linkArray[$value['menu']] = $value['menu'];
						}else{
							$linkArray[] = $cString;
						}
					}
				}
			}
			$mArray = array();
			foreach($menuArray as $mKey=>$mVal){
				$mArray[$mKey] = '<a href="#" onmouseover="hideshow(\'footerBarMenu_'.$mKey.'\');">'.$mKey.'</a><ul id="footerBarMenu_'.$mKey.'" style="display:none;"><li>'.implode($mVal, '</li><li>').'</li></ul>';
			}
			$lCounter = 0;
			if(count($linkArray)>0)
				$barContentHTML['array'] .= '<ul>';
			foreach($linkArray as $lKey => $lLink){
				$barContentHTML['array'] .= '<li>';
				if($lKey == $lLink){
					$barContentHTML['array'] .= $mArray[$lKey];
				}else{
					$barContentHTML['array'] .= $lLink;
				}
				if($lCounter<count($linkArray)-1)
					$barContentHTML['array'] .= wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain();
				$barContentHTML['array'] .= '</li>';
				$lCounter++;
			}
			/*
			if(count($linkArray)>0)
				$barContentHTML['array'] .= '</li></ul>';
			$barContentHTML['array'] = implode($mArray, "");
			$barContentHTML['array'] .= '<ul><li>'.implode($linkArray, "</li><li>".wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain()).'</li></ul>';*/
		}else{
			FooterBar::errorMsg('footerbar-error-array');
		}
		
		$outputContentHTML = array();
		if(is_array($footerBarConcat) && count($footerBarConcat)>0){
			foreach($footerBarConcat as $value)
				if(isset($barContentHTML[$value]) && $barContentHTML[$value]!='')
					$outputContentHTML[] = $barContentHTML[$value];
		}else{
			foreach($barContentHTML as $value)
				if($value!=""){	$outputContentHTML[] = $value; break; }
		}
		
		$contentHTML .= implode($outputContentHTML, wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain());
		$contentHTML .= "</div>";
		if($footerBarHideable==true){
			$contentHTML .= "<div class='footerBarArrow' id='footerBarArrow' onclick='hideShowBar();'>".wfMessage( 'footerbar-arrow-close' )->inContentLanguage()->plain()."</div>";
		}
		$contentHTML .= "</div><script type='text/javascript'>initFooterBar();</script>";
		
		$footerBarHtmlContent = $contentHTML;
		return true;
	}
	
	
	//Check a given array of the global array
	public static function checkGlobalArrayValue($value, $key){
		$gate = true;
		
		if(isset($value['link'])){
			if(!is_string($value['link']) || $value['link']==''){
				FooterBar::errorMsg('footerbar-error-link', $key);
				$gate = false;
			}
		}else{
			FooterBar::errorMsg('footerbar-error-link', $key);
			$gate = false;
		}
		
		if(isset($value['groups']))
			if(!is_array($value['groups']) || count($value['groups'])==0){
				FooterBar::errorMsg('footerbar-error-groups', $key);
				$gate = false;
			}
		
		$checkStrings = array('name', 'title', 'class', 'id', 'target');
		foreach($checkStrings as $checkString){
			if(isset($value[$checkString]))
				if(!is_string($value[$checkString])){
					FooterBar::errorMsg('footerbar-error-'.$checkString, $key);
					$gate = false;
				}
		}
		
		return $gate;
	}
	
	//Generates a error message for internationalized message id
	public static function errorMsg($messageId, $key=''){
		global $wgOut, $footerBarShowErrors;
		if($footerBarShowErrors==true){
			$msg = '';
			$msg = wfMessage( $messageId )->params($key)->inContentLanguage()->plain();
			if($msg==''){
				$msg = wfMessage( "footerbar-error-msgerror" )->params($key)->inContentLanguage()->plain();
				$wgOut->addHTML('<span class="footerBarError">FooterBar Ext. :: '.$msg.'</span>');
				return false;
			}
			$wgOut->addHTML('<span class="footerBarError">FooterBar Ext. :: '.$msg.'</span><br/>');
		}
		return true;
	}
	
	//Create the HTML-Source of the FooterBar and append it to the Output
	public static function addFooterBar( &$text, Skin $skin ){
		global $footerBarHtmlContent;
		$text .= $footerBarHtmlContent;
		return true;
	}
}

//Check if MediaWiki is defined
FooterBar::checkForMediaWiki();

$footerBarHtmlContent = "";

//Define the FooterBar variables, change them in LocalSettings.php
$footerBarHideable = true;
$footerBarPage = false;
$footerBarUser = false;
$footerBarArray = array();
$footerBarConcat = array();
$footerBarShowErrors = false;

//Register the variables, resources, etc.
FooterBar::initFooterBar();