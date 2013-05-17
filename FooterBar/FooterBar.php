<?php
/**
 * @brief	The extension FooterBar will add a fixed customizable bar at the bottom of each page.
 *
 * @author	Mike Knappe
 * @version	0.1
 *
 * @bug (fixed): Extension was called multiple times by the hook 'SkinAfterContent', 'ParseAfterTidy' and 'ParseBeforeTidy',
 *               where as 'AfterFinalPageOutput' has done bullshit at the end of the page. Now using 'OutputPageParserOutput'.
 * @bug (fixed): Couldn't detect the global array.
 * @bug (fixed): It wasn't possible to skin the FooterBar. Now using the new ResourceLoader for CSS and JS.
 * @bug (fixed): It wasn't possible to use i18. Now included.
 * @bug (fixed): It wasn't possible to hide the bar. Javascript included.
 * @bug (fixed): It wasn't possible to hide the bar via value. New value added, to allow/deny hiding the bar.
 *
 * @todo Explain the global array usage.
 * @todo Add class/name/title tag to the links.
 * @todo Test the FooterBar on other browsers.
 * @todo Include JS via file, currently it's an inline script.
 * @todo Declare an option, where the current status of the bar is saved in during a session.
 * @todo Implement to add multiple links as a hove menu.
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
			echo "To append links the FooterBar, use the following code in LocalSettings.php:";
			echo "<dl><dd>\$footerBarArray[]= array('namespace'=>'Category','name'=>'Help','target'=>'');</dd></dl>";
			echo "To customize a link for a special page you can use as 'target' the value 'self', which appends '/CurrentPageName' to the url:";
			echo "<dl><dd>\$footerBarArray[]= array('namespace'=>'Special','name'=>'WhatLinksHere','target'=>'self');</dd></dl>";
			echo "To declare an own 'target', write what you want to append.";
			echo "<dl><dd>\$footerBarArray[]= array('namespace'=>'User_blog','name'=>'TestUser','target'=>'/OldShip');</dd></dl>";
			echo "Turn off the hide-function via:";
			echo "<dl><dd>\$footerBarHideable = false;</dd></dl>";
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
	
	//Create the HTML-Source of the FooterBar and append it to the Output
	public static function addFooterBar( &$text, $parser ){
		global $wgOut, $footerBarArray, $footerBarHideable;
		$bottombar = "<div class='footerBar' id='footerBar'><div style='display:block;' class='footerBarContent' id='footerBarContent'>";
		$bottombarCont = "";
		$subcounter = 0;
		foreach($footerBarArray as $key=>$value){
			$gate = false;
			if($value['namespace']=='Special'){
				$titleObject = SpecialPage::getTitleFor( $value['name'] );
				$gate = true;
			}else if($value['namespace']!='' && isset($value['namespace'])){
				$titleObject = Title::newFromText( $value['namespace'].':'.$value['name'] );
				if($titleObject->exists()) $gate = true;
			}else{
				$titleObject = Title::newFromText( $value['name'] );
				if($titleObject->exists()) $gate = true;
			}
			if ( $gate ){
				if( $subcounter > 0)
					$bottombarCont .= wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain();
				if($value['target']=='self'){
					$bottombarCont .= '<a href="'.$titleObject->getLinkURL().'/'.$wgOut->getPageTitle().'">'.$value['name'].'</a>';
				}else if($value['target']!='' && isset($value['target'])){
					$bottombarCont .= '<a href="'.$titleObject->getLinkURL().$value['target'].'">'.$value['name'].'</a>';
				}else{
					$bottombarCont .= '<a href="'.$titleObject->getLinkURL().'">'.$value['name'].'</a>';
				}
				$subcounter++;
			}
		}
		$bottombar .= $bottombarCont;
		$bottombar .= "</div>";
		if($footerBarHideable==true){
			//Currently a work-a-round for the javascript thingy
			$barscript = "<script type='text/javascript'>function hideShowBar(){if(document.getElementById(\"footerBarContent\").style.display==\"block\"){document.getElementById(\"footerBarArrow\").innerHTML = \"&laquo;\";document.getElementById(\"footerBarContent\").style.display=\"none\";document.getElementById(\"footerBar\").style.width=\"20px\";}else{document.getElementById(\"footerBarArrow\").innerHTML = \"&raquo;\";document.getElementById(\"footerBarContent\").style.display=\"block\";document.getElementById(\"footerBar\").style.width=\"98%\";}}</script>";
			$wgOut->addHeadItem('footerBar', $barscript);
			$bottombar .= "<div class='footerBarArrow' id='footerBarArrow' onclick='hideShowBar();'>&raquo;</div>";
		}
		$bottombar .= "</div>";
		
		$text->addHTML( $bottombar );
		return true;
	}
}

//Check if MediaWiki is defined
FooterBar::checkForMediaWiki();

//Define the FooterBar variables, change them in LocalSettings.php
$footerBarHideable = true;
$footerBarArray = array(array('namespace'=>'Special','name'=>'WhatLinksHere','target'=>'self'));

//Register the variables, resources, etc.
FooterBar::initFooterBar();

