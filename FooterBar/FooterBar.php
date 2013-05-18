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
 * @version		0.1
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
 * @bug (fixed): If wasn't possible to declare a link of the global array only for admins. New field inserted.
 * @bug (fixed): There where no error messages from the script. Now added into source and i18.
 *
 * @bug (open) : The 'self' link doesn't work proper, because of the getTitle-function.
 * @bug (open) : If no value of '$footerBarConcat' fits, then the FooterBar is empty.
 *
 * @todo Create a more readable source code with code reduction by using loops.
 * @todo Add class/name/title tag to the links.
 * @todo Test the FooterBar on other browsers.
 * @todo Include JS via file, currently it's an inline script .. but it has not at all disadvantages ..
 * @todo Declare an option, where the current status of the bar is saved in during a session.
 * @todo Implement to add multiple links as a hover menu.
 * @todo Use the class name of the FooterBar to switch between hide/show (for JS).
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
		$wgHooks['OutputPageParserOutput'][] = 'FooterBar::addFooterBar';

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
	
	//Check a given array of the global array
	public static function checkGlobalArrayValue($value, $key){
		$gate = true;
		
		if(isset($value['namespace']))
			if(is_string($value['namespace']) && $value['namespace']!=''){}else{
				FooterBar::errorMsg('footerbar-error-namespace', $key);
				$gate = false;
			}
		
		if(isset($value['groups']))
			if(is_array($value['groups']) && count($value['groups'])>0){}else{
				FooterBar::errorMsg('footerbar-error-groups', $key);
				$gate = false;
			}
		
		if(isset($value['target']))
			if(is_string($value['target']) && $value['target']!=''){}else{
				FooterBar::errorMsg('footerbar-error-target', $key);
				$gate = false;
			}
			
		if(isset($value['name']))
			if(is_string($value['name']) && $value['name']!=''){}else{
				FooterBar::errorMsg('footerbar-error-name', $key);
				$gate = false;
			}
			
		return $gate;
	}
	
	//Generates a error message for internationalized message id
	public static function errorMsg($messageId, $key){
		global $wgOut;
		$msg = '';
		$msg = wfMessage( $messageId )->params($key)->inContentLanguage()->plain();
		if($msg==''){
			$msg = wfMessage( "footerbar-error-msgerror" )->params($key)->inContentLanguage()->plain();
			$wgOut->addHTML('<span class="footerBarError">FooterBar Ext. :: '.$msg.'</span>');
			return false;
		}
		$wgOut->addHTML('<span class="footerBarError">FooterBar Ext. :: '.$msg.'</span><br/>');
		return true;
	}
	
	//Create the HTML-Source of the FooterBar and append it to the Output
	public static function addFooterBar( &$text, $parser ){
		global $wgOut, $wgUser, $footerBarArray, $footerBarHideable, $footerBarPage, $footerBarUser, $footerBarConcat;
		
		$contentHTML = "<div class='footerBar' id='footerBar'><div style='display:block;' class='footerBarContent' id='footerBarContent'>";
		$barContentHTML = array('user'=>'','page'=>'','array'=>'');
		$titleObject = "";
		
		if( $footerBarUser==true && $wgUser->getId()!=0)
			$titleObject = Title::newFromText( 'User:'.$wgUser->getName().'/FooterBar' );
		if($titleObject != "" && $titleObject->exists()){
			$pageObject = WikiPage::newFromId( $titleObject->getArticleID() );
			$footerTextPlain = $pageObject->getText();
			FooterBar::extractLinksFromFooterBarPage($footerTextPlain);
			$footerTextWiki = wfMessage( 'footerbar-content' )->params( $footerTextPlain )->inContentLanguage()->parse();
			$barContentHTML['user'] = $footerTextWiki;
		}
		$titleObject = "";
		
		if( $footerBarPage==true )
			$titleObject = Title::newFromText( 'MediaWiki:FooterBar' );
		if($titleObject != "" && $titleObject->exists()){
			$pageObject = WikiPage::newFromId( $titleObject->getArticleID() );
			$footerTextPlain = $pageObject->getText();
			FooterBar::extractLinksFromFooterBarPage($footerTextPlain);
			$footerTextWiki = wfMessage( 'footerbar-content' )->params( $footerTextPlain )->inContentLanguage()->parse();
			$barContentHTML['page'] = $footerTextWiki;
		}
		
		if( is_array($footerBarArray) ){
			$cArray = array();
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
					if(isset($value['namespace']) && $value['namespace']=='Special'){
						$titleObject = SpecialPage::getTitleFor( $value['name'] );
						$exGate = true;
					}else if(isset($value['namespace']) && $value['namespace']!=''){
						$titleObject = Title::newFromText( $value['namespace'].':'.$value['name'] );
						if($titleObject->exists()) $exGate = true;
					}else{
						$titleObject = Title::newFromText( $value['name'] );
						if($titleObject->exists()) $exGate = true;
					}
					if ( $exGate ){
						if(isset($value['target']) && $value['target']=='self'){
							$cArray[] = '<a href="'.$titleObject->getLinkURL().'/'.$wgOut->getPageTitle().'">'.$value['name'].'</a>';
						}else if(isset($value['target']) && $value['target']!=''){
							$cArray[] = '<a href="'.$titleObject->getLinkURL().$value['target'].'">'.$value['name'].'</a>';
						}else{
							$cArray[] = '<a href="'.$titleObject->getLinkURL().'">'.$value['name'].'</a>';
						}
					}
				}}
			}
			$barContentHTML['array'] = implode($cArray, wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain());
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
			//Currently a work-a-round for the javascript thingy
			$barScript = "<script type='text/javascript'>function hideShowBar(){if(document.getElementById(\"footerBarContent\").style.display==\"block\"){document.getElementById(\"footerBarArrow\").innerHTML = \"".wfMessage( 'footerbar-arrow-open' )->inContentLanguage()->plain()."\";document.getElementById(\"footerBarContent\").style.display=\"none\";document.getElementById(\"footerBar\").style.width=\"20px\";}else{document.getElementById(\"footerBarArrow\").innerHTML = \"".wfMessage( 'footerbar-arrow-close' )->inContentLanguage()->plain()."\";document.getElementById(\"footerBarContent\").style.display=\"block\";document.getElementById(\"footerBar\").style.width=\"98%\";}}</script>";
			$wgOut->addHeadItem('footerBar', $barScript);
			$contentHTML .= "<div class='footerBarArrow' id='footerBarArrow' onclick='hideShowBar();'>".wfMessage( 'footerbar-arrow-close' )->inContentLanguage()->plain()."</div>";
		}
		$contentHTML .= "</div>";
		
		$text->addHTML( $contentHTML );
		return true;
	}
}

//Check if MediaWiki is defined
FooterBar::checkForMediaWiki();

//Define the FooterBar variables, change them in LocalSettings.php
$footerBarHideable = true;
$footerBarPage = false;
$footerBarUser = false;
$footerBarArray = array();
$footerBarConcat = array();

//Register the variables, resources, etc.
FooterBar::initFooterBar();