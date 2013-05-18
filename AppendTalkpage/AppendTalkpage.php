<?php
/**
 * @brief 		The extension 'AppendTalkpage' appends the talkpage of the current article at the really end of the article.
 * @details		The extension 'AppendTalkpage' appends the talkpage of the current article at the really end of the article.
 *				The talkpage will be added even below the automatically added 'Categories', but still in the mainframe.
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
 * @bug (fixed) Called multiple times by the hook 'ParseAfterTidy' and 'ParseBeforeTidy. Now using 'SkinAfterContent'.
 * @bug (fixed) First Version added Wiki-Text to the end of an article, which was not dynamic and not below the 'Categories'.
 * @bug (fixed) It was not possible to skin the talkpage insertion. Now using the new ResourceLoader.
 * @bug (fixed) The structure was bad. Now covering the space between and around the headlines by div-containers.
 *
 * @todo Try to save the talkpage localy in the class. Therefore change the hook calls to register the calls.
 * @todo Use i18 for i.e. headlines.
 * @todo Parse the comments via headline to give them unique identifications such as css or links.
 */

/**
 * @brief The AppendTalkpage class
 */
class AppendTalkpage{
	//Class constructor
	function __construct(){
		parent::__construct( 'AppendTalkpage' );
	}
	
	//Check if MediaWiki is defined
	public static function checkForMediaWiki( ){
		if(!defined('MEDIAWIKI')){
			echo "To install the 'AppendTalkpage' extension, put the following line in LocalSettings.php:";
			echo "<br><dl><dd>require_once( '\$IP/extensions/AppendTalkpage/AppendTalkpage.php' );</dd></dl>";
			echo "For more information read the <a href='./README.md'>README</a> :)";
			exit( 1 );
		}
	}
	
	//Initialize the needed variables etc.
	public static function initTalkpage( ){
		global $wgExtensionCredits, $wgHooks, $wgExtensionMessagesFiles, $wgResourceModules;
	
		//Credits of AppendTalkpage (see Special:Version)
		$wgExtensionCredits['AppendTalkpage'][] = array(
			'path' => __FILE__,
			'name' => 'AppendTalkpage',
			'version' => '0.1',
			'author' => 'Mike Knappe',
			'descriptionmsg' => 'This extension will append the talkpage of an article at the end of the article.'
		);

		//Setup the needed hooks
		$wgHooks['ResourceLoaderRegisterModules'][] = 'AppendTalkpage::resourceLoaderRegisterModules';
		$wgHooks['ArticleViewFooter'][] = 'AppendTalkpage::createTalkpage';
		$wgHooks['SkinAfterContent'][] = 'AppendTalkpage::addTalkpage';

		//Define the internationalization file
		$wgExtensionMessagesFiles['AppendTalkpage'] = dirname( __FILE__ ) . '/AppendTalkpage.i18n.php';

		//Setup the needed resources via the ResourceLoader
		$wgResourceModules['AppendTalkpage'] = array(
				'localBasePath' => dirname( __FILE__ ),
				'remoteExtPath' => 'AppendTalkpage',
				'styles' => 'AppendTalkpage.css',
		);
		return true;
	}
	
	//Register the previously added resources in the ResourceLoader
	public static function resourceLoaderRegisterModules( &$resourceLoader ) {
		global $wgOut;
		$wgOut->addModules( 'AppendTalkpage' );
		return true;
	}
	
	//Fetch the talkpage of the current page
	public static function createTalkpage( $article ){
		global $appendTalkpageTOC, $TalkPageAfterContent, $appendTalkpagePlaceTOC;
		$TalkPageAfterContent = "";
		$title = $article->getTitle();
		if( !$title->isTalkPage() && !$title->isMainPage() && !$title->isSpecialPage() ){
			$titleObject = $title->getTalkPage();
			if($titleObject->getArticleID()!=0){
				$talkObject = WikiPage::newFromId( $titleObject->getArticleID() );
				$wikitext = $talkObject->getText();
				AppendTalkpage::handleTOC($wikitext);
				$htmltext = wfMessage( 'appendtalkpage-content' )->params( $wikitext )->inContentLanguage()->parse();
				AppendTalkpage::handleFormat($htmltext);
				$TalkPageAfterContent = $htmltext;
			}
		}
		return true;
	}

	//Handles the TableOfContent (TOC) of the talkpage
	public static function handleTOC( &$wikiText ){
		global $appendTalkpageTOC, $appendTalkpagePlaceTOC;
		$cachetext = $wikiText;
		$textreplacer = array('__TOC__', '__NOTOC__');
		$cachetext = str_replace($textreplacer, '', $cachetext);
		switch($appendTalkpageTOC){
			case 'force':		if($appendTalkpagePlaceTOC=='top'){ $cachetext = '__TOC__'.$cachetext; }else{ $cachetext .= '__TOC__'; }	break;
			case 'no': default:	$cachetext .= '__NOTOC__';																					break;
		};
		$wikiText = $cachetext;
		return true;
	}
	
	//Handles the format of the talkpage (cover the content in div-container)
	public static function handleFormat( &$htmlText ){
		global $appendTalkpageTOC, $appendTalkpagePlaceTOC;
		$cachetext = $htmlText;
		$depth = 6;
		$Harray = array();
		$cachetext = '<hr><div id="appendTalkpage"><div id="appendTalkpage_head">'.wfMessage( 'appendtalkpage-headline' )->inContentLanguage()->plain().'<hr>'.$cachetext;
		for($i=1;$i<=$depth;$i++) $Harray[] = '</h'.$i.'>';
		for($i=0;$i<count($Harray);$i++){
			$cachetext = str_replace($Harray[$i], $Harray[$i].'<div id="appendTalkpage_h'.($i+1).'">', $cachetext);
			$cachetext = str_replace('<h'.$i.'>', '</div><h'.$i.'>', $cachetext);
		}
		$cachetext = str_replace(array('<dl>','<dd>','</dl>','</dd>'), '', $cachetext);
		$cachetext .= '</div></div><hr>';
		$htmlText = $cachetext;
		return true;
	}

	//Append the talkpage at the end of the content
	public static function addTalkpage( &$text, Skin $skin ) {
		global $TalkPageAfterContent;
		$text .= $TalkPageAfterContent;
		return true;
	}
	
}

//Check if MediaWiki is defined
AppendTalkpage::checkForMediaWiki();

//Define the FooterBar variables, change them in LocalSettings.php
$appendTalkpageTOC = 'no';
$appendTalkpagePlaceTOC = 'top';

//Register the variables, resources, etc.
AppendTalkpage::initTalkpage();
