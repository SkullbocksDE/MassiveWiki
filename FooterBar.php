<?php
/**
 * This extension will add a fixed customizable bar at the bottom of each page.
 *
 * @author Mike Knappe
 *
 * @bug Fixed: Called multiple times by the hook 'SkinAfterContent', 'ParseAfterTidy' and 'ParseBeforeTidy',
 *              where as 'AfterFinalPageOutput' makes bullshit at the end of the page. Now using 'OutputPageParserOutput'.
 * @bug Fixed: Coudn't detect the global array.
 * @bug Fixed: It was not possible to skin the footerbar insertion. Now using the new ResourceLoader.
 * @bug Fixed: Wasn't possible to use i18. Now included.
 * @bug Fixed: It was not possible to hide the bar. Javascript included.
 * @bug Fixed: New value added, to allow/deny hiding the bar.
 *
 * @todo Explain the array useage
 * @todo Add class/name/title to links
 * @todo Test on other browsers
 * @todo Include JS via file, currently it's an inline script. I bet that the function has to be registered in the mw lib or something.
 * @todo Make the option, that the bar is hidden as some kind of a global, that a user don't have to toggle it again during a session.
 */

 if (!defined('MEDIAWIKI')) {
	echo "To install the 'FooterBar' extension, put the following line in LocalSettings.php:";
	echo "<br>require_once( '\$IP/extensions/FooterBar/FooterBar.php' );";
	exit( 1 );
}

// Credits
$wgExtensionCredits['FooterBar'][] = array(
	'path' => __FILE__,
	'name' => 'FooterBar',
	'version' => '0.1',
	'author' => 'Mike Knappe',
	'descriptionmsg' => 'This extension will add a fixed customizable bar at the bottom of each page.'
);

$wgHooks['ResourceLoaderRegisterModules'][] = 'FooterBar::resourceLoaderRegisterModules';
$wgHooks['OutputPageParserOutput'][] = 'FooterBar::addFooterBar';
//$wgHooks['AfterFinalPageOutput'][] = 'FooterBar::addFooterBar';

$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['FooterBar'] = $dir . 'FooterBar.i18n.php';

$wgResourceModules['FooterBar'] = array(
		'localBasePath' => dirname( __FILE__ ),
		'remoteExtPath' => 'FooterBar',
        'styles' => 'FooterBar.css',
        'scripts' => 'FooterBar.js',
);

$footerBarHideable = true;
$footerBarArray = array(array('namespace'=>'Special','name'=>'WhatLinksHere','target'=>'self'));

class FooterBar{
	/**
	 * Constructor
	 */
	function __construct(){
		parent::__construct( 'FooterBar' );
	}
	
	public static function resourceLoaderRegisterModules( &$resourceLoader ) {
		global $wgOut;
		$wgOut->addModules( 'FooterBar' );
		return true;
	}
	
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
			$bottombar .= "<div class='footerBarArrow' id='footerBarArrow' onclick='javascript:{if(document.getElementById(\"footerBarContent\").style.display==\"block\"){document.getElementById(\"footerBarArrow\").innerHTML = \"&laquo;\";document.getElementById(\"footerBarContent\").style.display=\"none\";document.getElementById(\"footerBar\").style.width=\"20px\";}else{document.getElementById(\"footerBarArrow\").innerHTML = \"&raquo;\";document.getElementById(\"footerBarContent\").style.display=\"block\";document.getElementById(\"footerBar\").style.width=\"98%\";}};'>&raquo;</div>";
			$bottombar .= "<div class='footerBarArrow' id='footerBarArrow' onclick='javascript:{mw.hideShowFooterBar();}else{document.getElementById(\"footerBarArrow\").innerHTML = \"&raquo;\";document.getElementById(\"footerBarContent\").style.display=\"block\";document.getElementById(\"footerBar\").style.width=\"98%\";}};'>&raquo;</div>";
		}
		$bottombar .= "</div>";
		
		$text->addHTML( $bottombar );
		return true;
	}
	
}