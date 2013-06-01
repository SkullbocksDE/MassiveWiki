<?php
/**
 * @mainpage
 * @tableofcontents
 * @section decription Decription
 *				The extension 'FooterBar' will add a customizable bar at the bottom of each mediawiki page.\n
 *				You can customize it via the MediaWiki:Common.css without touching the css-file.\n
 *				There are also some variables, which can be set in the LocalSettings.php.\n
 * @note		Read the @ref README.md for more details.
 *
 * @ingroup 	Extensions
 *
 * @subsection metadata Meta Data
 * @author		Mike Knappe
 * @version		0.8
 * @copyright	© 2013 Mike Knappe
 * @license 	GNU General Public Licence 2.0 or later
 *
 * @section bugs Bugs
 * @bug Better names for some values, like $..content $...array + readable code + extract the to array and create footerbar in ONE function
 * @bug Proper style for the docu of css.
 * @bug Extend the readme with the new css classes and i18 values.
 * @bug Alignment of links must be reversable to make FooterBar accessible for arabic countries.
 *
 * @section todo ToDo
 * @todo Test the FooterBar on other browsers and in field.
 *
 * @section changelog ChangeLog
 * This is the changelog of the extension during the development.
 *
 * @subsection version0_1 Version 0.1
 * @li @c new	It wasn't possible to skin the FooterBar. Now using the new ResourceLoader for CSS and JS.
 * @li @c fixed	Extension was called multiple times by the hook 'SkinAfterContent', 'ParseAfterTidy' and 'ParseBeforeTidy',
 *              where as 'AfterFinalPageOutput' has done bullshit at the end of the page. Now using 'OutputPageParserOutput'.
 * @li @c fixed	Couldn't detect the global array.
 *
 * @subsection version0_2 Version 0.2
 * @li @c new	It wasn't possible to use i18. Now included.
 * @li @c new	It wasn't possible to hide the bar. Javascript included.
 * @li @c new	It wasn't possible to hide the bar via value. New value added, to allow/deny hiding the bar.
 * @li @c new	There was no explanation of the usage of the global array. Readme is now avaible.
 * @li @c fixed It wasn't possible to use i18 for some text. Now all output is in i18.
 *
 * @subsection version0_3 Version 0.3
 * @li @c new	It wasn't possible to change the FooterBar content within the MediaWiki. Now using the page MediaWiki:FooterBar.
 *				The variable '$footerBarPage' has to be set 'true' to use the page.
 * @li @c fixed	All content of the refered FooterBar page was parsed. Now extracting links.
 * @li @c fixed	Parser detected images as links. Now only extracting links.
 * @li @c fixed	It wasn't possible to customize the FooterBar as user. Now using the page 'User:Username/FooterBar'.
 *				 The variable '$footerBarUser' has to be set 'true' to allow users to customize the bar.
 *
 * @subsection version0_4 Version 0.4
 * @li @c new	It wasn't possible to concatenate the certain areas like 'MediaWiki:FooterBar' and the global array.
 *				The array '$footerBarConcat' will handle the concatenate. Possible values: 'array', 'page', 'user'
 *              The order of the entries in this array effects the concatenate order.
 *				If the Concat is empty, the highest area will be selected: user >> page >> array
 * @li @c new	If wasn't possible to declare a link of the global array only for admins. New field 'groups' inserted.
 * @li @c fixed	IP users were able to create a FooterBar for the ip. IP users will now be denied.
 *
 * @subsection version0_5 Version 0.5
 * @li @c new	There where no error messages from the script. Now added into source and i18.
 * @li @c new	It wasn't possible to name a link on your own. Now added a new field 'title'.
 * @li @c new	It wasn't possible to give a link a certain id and class. Now added new fields 'id' and 'class'.
 * @li @c new	It wasn't possible to turn off the error messages and use them as some kind of debug. Now added value '$footerBarShowErrors'.
 * @li @c fixed	On SpecialPages the FooterBar was hooked multiple time and it was added to the top. Now using again SkinAfterContent.
 * @li @c fixed	It wasn't possible to change the hideShow behaviour. JS is now checking for className.
 *				CSS classes accessible via: '.footerBar.footerBarHide' and '.footerBarContent.footerBarContentHide'.
 *
 * @subsection version0_6 Version 0.6
 * @li @c new	The status of the bar wasn't saved during a session. Now using cookies via the jQuery#cookie.
 *				To check the cookie there is a new inline JS, but only to call a head function. ^^
 * @li @c new	It wasn't possible to create menus for the FooterBar. New field added 'menu'.
 *				The menu will be created at the position of the first match of a certain menuname.
 * @li @c new	Introduced the doxygen documentation, doxyfile and html output.
 * @li @c fixed	FooterBar wasn't displayed on SpecialPages, EditForms and Login. Hooks rearranged!
 *				Now using to create the Bar 'ArticlePageDataAfter' and 'SpecialPageBeforeExecute' and to show 'SkinAfterContent'.
 *				It also fixed the bug, that the JS was declared inline
 *
 * @subsection version0_7 Version 0.7
 * @li @c new	Users couldn't read about the extension. Now using the Special page 'Special:FooterBar'. Alias-File created.
 * @li @c new	Admins couldn't turn on a help item in the bar. New value added '$footerBarShowHelp'. Connected to 'Special:FooterBar'.
 * @li @c new	User couldn't create their own menus. Now possible via the MediaWiki listing function (*).
 * @li @c fixed	The SideBar had a higher z-index than the FooterBar. Now using Z-Index.
 * @li @c fixed	HideShow was not using MediaWiki functions. Own Hide/Show JS removed and now using the mw-toggle and collapse classes.
 * @li @c fixed	CSS was messy as hell trough the restructuring. Redesigned the CSS (shortened, unified, clearer).
 * @li @c fixed	Collector was messy as hell trough the restructuring. Restructured the HTML collect (shortened, unified, clearer).
 * @li @c fixed	Cookie wasn't working anymore trough the restructuring. Included again.
 *
 * @subsection version0_8 Version 0.8
 * @li @c new	The option 'name' was used as text. Now using the new field 'text' for it. 'name' is still avaible as attribute of a html link.
 * @li @c new	HTML replacement is now done by the const class ConstFooterBarHTML.
 * @li @c new	The targets 'self' and 'top' wasn't working well. Now using the const class ConstFooterBarURL and a new method to handle it.
 *				'self' and 'top', where changed to '{self}' and '{top}'. The field 'target' now used for the link target.
 *				To create a link, that jumps to the top, the field 'link' needs to be '{self}{top}'.
 *				Anchors can now be used and users can also use '{self}' and '{top}' in their links.
 *				Plus a new constant called '{bottom}'.
 * @li @c new	FooterBar has now own hooks, which can be used by other extensions to modify the content.
 *				The hook 'FooterBarRequestFinished' runs, when the array with all links is complete. Returns the main array with all links.
 *				The hook 'FooterBarCreated' runs, when the html of the content is complete. Returns the entire html output of the extension.
 * @li @c new	It wasn't possible to arrange the hide-arrow. Now using a new value $footerBarArrowFloatString via setArrowFloat( $string );
 * @li @c new	The style of the FooterBar was not using CSS3 elements. Now using linear gradients.
 * @li @c fixed	Content of FooterBar was stored in a global array. Now using an instance of the FooterBar class.
 *				Changed unneeded functions to private. Removed all globals -> introduced setter/getter for the values.
 *				Changed the readme content that belongs to the globals. Hook registration now using another method: array($this, "method");
 * @li @c fixed The internal variables had strange names. Renamed and restructured the html insertion.
 * @li @c fixed	Error message wasn't able to access wfMessage. Now using wfMessage in FooterBar::displayErrors.
 * @li @c fixed	The user and mediawiki page wasn't stored in the FooterBar::$footerBarContentArray. Now unified.
 * @li @c fixed	If no value of the FooterBar::$footerBarConcatArray fitted the avaible areas, no content was displayed. Now all content is displayed.
 * @li @c fixed	Links to 'MediaWiki:FooterBar' and 'User:Username/FooterBar' were not using i18.
 *				Now using 'footerbar-link-page' and 'footerbar-link-user'.
 * @li @c fixed	The links wasn't centered in the FooterBar. Now all links are centered.
 * @li @c fixed The FooterBar wasn't centered at all and had floating problems. New center concept is fixing this.
 * 
 */
 
/**
 * @brief	A class with constants for the HTML comment replacement.
 * @details	A class with constants for the HTML comment replacement. 
 *			Its used as a constant enum in FooterBar::initFooterBar and FooterBar::createFooterBar.
 */
class ConstFooterBarHTML{
    const HELP = "<!-- FooterBarHelpLink -->";
    const CONTENT = "<!-- FooterBarContent -->";
    const HIDE = "<!-- FooterBarHideButton -->";
}

class ConstFooterBarURL{
    const SELF = "{self}";
    const TOP = "{top}";
    const BOTTOM = "{bottom}";
}

/**
 * @brief	The class of the extension FooterBar. The main class of the extension, where you i.e. add links.
 * @details	The class of the extension FooterBar. The main class of the extension, where you i.e. add links.
 *			You also set your options like a hideable Footerbar with this class.
 *			The initialization functions will be called automatically in the constructor, until there is a MediaWiki installed.
 *			After an instance of this class was successfully created, you can start adding links via FooterBar::setLink or FooterBar::appendLink.
 *			To turn on i.e. the help button in the FooterBar, call FooterBar::setShowHelp or to show errors, call FooterBar::setShowErrors.
 */
class FooterBar{

	/**
	 * @brief	Constructor of the FooterBar class. It sets all avaible option to a default value and creates the html template $footerBarHtmlTemplate.
	 * @details	Constructor of the FooterBar class. It sets all avaible option to a default value and creates the html template $footerBarHtmlTemplate.
	 *			It calls the private functions FooterBar::checkForMediaWiki to check the existance of a MediaWiki
	 *			and FooterBar::initFooterBar if there is a MediaWiki.
	 * @note	After you have included the extension, there will be an instance named: $footerBarClass
	 * @see		ConstFooterBarHTML
	 */
	public function FooterBar(){
		//Check if MediaWiki is defined
		$this->checkForMediaWiki();
		
		//Init.the variables
		$this->footerBarArrowFloatString = 'right';
		$this->footerBarHideAbleBool = true;
		$this->footerBarPageBool = false;
		$this->footerBarUserBool = false;
		$this->footerBarConcatArray = array();
		$this->footerBarShowErrorsBool = false;
		$this->footerBarShowHelpBool = false;
		$this->footerBarErrorsArray = array();
		$this->footerBarContentArray = array( 'array'=>array(), 'page'=>array(), 'user'=>array() );
		$this->footerBarHtmlTemplate = "";
		$this->footerBarHtmlTemplate .= "<div class='footerBar' id='footerBar'>";
		$this->footerBarHtmlTemplate .= "<div class='mw-collapsible footerBarStyle' id='mw-customcollapsible-footerBarStyle'>";
		$this->footerBarHtmlTemplate .= "<div class='mw-collapsible-content footerBarContent' id='footerBarContent'>";
		$this->footerBarHtmlTemplate .= ConstFooterBarHTML::HELP;
		$this->footerBarHtmlTemplate .= ConstFooterBarHTML::CONTENT;
		$this->footerBarHtmlTemplate .= "</div>";
		$this->footerBarHtmlTemplate .= ConstFooterBarHTML::HIDE;
		$this->footerBarHtmlTemplate .= "</div>";
		$this->footerBarHtmlTemplate .= "</div>";
		//$this->footerBarHtmlTemplate .= "</div></div>"; //<<BUG =!=!=!==!!=
		$this->footerBarHtmlTemplate .= "<script type='text/javascript'>initFooterBar();</script>";

		//Register the variables, resources, etc.
		$this->initFooterBar();
	}
	
	private $footerBarArrowFloatString;
	/**
	 * @var		boolean $footerBarHideAbleBool
	 * @brief   Variable to define, if FooterBar is hideable or not.
	 * @see		FooterBar::setHideAble
	 * @see		FooterBar::getHideAble
	 */
	private $footerBarHideAbleBool;
	/**
	 * @var		boolean $footerBarPageBool
	 * @brief	Variable to define, if FooterBar collects links from 'MediaWiki:FooterBar' (depends on i18).
	 * @see		FooterBar::setPage
	 * @see		FooterBar::getPage
	 */
	private $footerBarPageBool;
	/**
	 * @var		boolean $footerBarUserBool
	 * @brief	Variable to define, if FooterBar collects links from user pages 'User:Username/FooterBar'.
	 * @see		FooterBar::setUser
	 * @see		FooterBar::getUser
	 */
	private $footerBarUserBool;
	/**
	 * @var		array $footerBarContentArray
	 * @brief	An array to store all links that are added via 'LocalSettings.php'.
	 * @par		Usage
	 *			array( array( 'link'=>string, 'name'=>string, 'title'=>string, 'target'=>string, 'id'=>string, 'class'=>string, 'menu'=>string 'groups'=>array ) )
	 * @see		FooterBar::setLinks
	 * @see		FooterBar::getLinks
	 * @see		FooterBar::appendLink
	 * @see		FooterBar::setLink
	 * @see		FooterBar::getLinkFromId
	 */
	private $footerBarContentArray;
	/**
	 * @var		array $footerBarConcatArray
	 * @brief   An array to define, in which order the links are added to the FooterBar.
	 * @par		Usage
	 *			array( 'array', 'page', 'user' )
	 * @see		FooterBar::setConcat
	 * @see		FooterBar::getConcat
	 */
	private $footerBarConcatArray;
	/**
	 * @var		boolean $footerBarShowErrorsBool
	 * @brief   Variable to define, if FooterBar shows errors made by adding links via 'LocalSettings.php'.
	 * @see		FooterBar::setShowErrors
	 * @see		FooterBar::getShowErrors
	 */
	private $footerBarShowErrorsBool;
	/**
	 * @var		boolean $footerBarShowHelpBool
	 * @brief	Variable to define, if FooterBar shows a link to the help of the FooterBar 'Special:FooterBar'.
	 * @see		FooterBar::setShowHelp
	 * @see		FooterBar::getShowHelp
	 */
	private $footerBarShowHelpBool;	
	/**
	 * @brief	Variable to store all links generated during the MediaWiki hooks, to append it after all into the MediaWiki html output.
	 * @var		string $footerBarHtmlContent
	 * @see		FooterBar::createFooterBar
	 */
	private $footerBarHtmlContent;
	/**
	 * @brief	Variable that stores the HTML template/layout of the FooterBar. Auto-filled by the constructor.
	 * @var		string $footerBarHtmlTemplate
	 * @see		FooterBar::FooterBar
	 */	
	private $footerBarHtmlTemplate;
	/**
	 * @brief	An array that stores the i18 error messages, that where created during the function calls.
	 * @var		array $footerBarErrorsArray
	 * @see		FooterBar::displayErrors
	 * @see		FooterBar::createErrorMsg
	 * @see		FooterBar::appendLink
	 */	
	private $footerBarErrorsArray;

	public function setArrowFloat( $arrowFloatString ){
		if( !is_string( $arrowFloatString) )
			return false;
		$this->footerBarArrowFloatString = $arrowFloatString;
		return true;
	}
	
	public function getArrowFloat( ){
		return $this->footerBarArrowFloatString;
	}
	
	/**
	 * @brief	Function to define, if FooterBar is hideable or not.
	 * @param	boolean $hideAbleBool
	 * @return	_boolean_ 'True', if function was successful or 'false' if it wasn't successful.
	 * @see		FooterBar::getHideAble
	 * @see		FooterBar::$footerBarHideAbleBool
	 */
	public function setHideAble( $hideAbleBool ){
		if( !is_bool( $hideAbleBool ) )
			return false;
		$this->footerBarHideAbleBool = $hideAbleBool;
		return true;
	}

	/**
	 * @brief	Function to receive the current status of the 'Hideable' function.
	 * @return	_boolean_ FooterBar::$footerBarHideAbleBool 'True', if 'Hideable' is enabled or 'false', if 'Hideable' is disabled.
	 * @see		FooterBar::setHideAble
	 */
	public function getHideAble( ){
		return $this->footerBarHideAbleBool;
	}

	/**
	 * @brief	Function to define, if FooterBar collects links from 'MediaWiki:FooterBar' (depends on i18).
	 * @param	boolean $pageBool
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::getPage
	 * @see		FooterBar::$footerBarPageBool
	 */
	public function setPage( $pageBool ){
		if( !is_bool( $pageBool ) )
			return false;
		$this->footerBarPageBool = $pageBool;
		return true;
	}

	/**
	 * @brief	Function to receive, if FooterBar collects links from 'MediaWiki:FooterBar' (depends on i18).
	 * @return	_boolean_ FooterBar::$footerBarPageBool 'True', if it collects links or 'false', if not
	 * @see		FooterBar::setPage
	 */	
	public function getPage( ){
		return $this->footerBarPageBool;
	}

	/**
	 * @brief	Function to define, if FooterBar collects links from user pages 'User:Username/FooterBar'.
	 * @param	boolean $userBool
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::getUser
	 * @see		FooterBar::$footerBarUserBool
	 */
	public function setUser( $userBool ){
		if( !is_bool( $userBool ) )
			return false;
		$this->footerBarUserBool = $userBool;
		return true;
	}

	/**
	 * @brief	Function to receive, if FooterBar collects links from user pages 'User:Username/FooterBar'.
	 * @return	_boolean_ FooterBar::$footerBarUserBool 'True', if it collect links from user pages or 'false', if not.
	 * @see		FooterBar::setUser
	 */		
	public function getUser( ){
		return $this->footerBarUserBool;
	}

	/**
	 * @brief	Function to define, if FooterBar shows errors made by adding links via FooterBar::appendLinks in 'LocalSettings.php'.
	 * @param	boolean $showErrorsBool
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::getShowErrors
	 * @see		FooterBar::$footerBarShowErrorsBool
	 */
	public function setShowErrors( $showErrorsBool ){
		if( !is_bool( $showErrorsBool ) )
			return false;
		$this->footerBarShowErrorsBool = $showErrorsBool;
		return true;
	}

	/**
	 * @brief	Function to receive the current status of the 'ShowErrors' function.
	 * @return	_boolean_ FooterBar::$footerBarShowErrorsBool 'True', if 'ShowErrors' is enabled or 'false', if 'ShowErrors' is disabled.
	 * @see		FooterBar::setShowErrors
	 */
	public function getShowErrors( ){
		return $this->footerBarShowErrorsBool;
	}

	/**
	 * @brief	Function to define, if FooterBar shows a link to the help of the FooterBar 'Special:FooterBar'.
	 * @param	boolean $showHelpBool	
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::getShowHelp
	 * @see		FooterBar::$footerBarShowHelpBool
	 */
	public function setShowHelp( $showHelpBool ){
		if( !is_bool( $showHelpBool ) )
			return false;
		$this->footerBarShowHelpBool = $showHelpBool;
		return true;
	}

	/**
	 * @brief	Function to receive the current status of the 'ShowHelp' function.
	 * @return	_boolean_ FooterBar::$footerBarShowHelpBool 'True', if 'ShowHelp' is enabled or 'false', if 'ShowHelp' is disabled.
	 * @see		FooterBar::setShowHelp
	 */
	public function getShowHelp( ){
		return $this->footerBarShowHelpBool;
	}

	/**
	 * @brief	Function to define, in which order the links are added to the FooterBar.
	 * @par		Usage
	 *			array( 'array', 'page', 'user' )
	 * @par		Possible values
	 * @li @c	'array' For all links added via 'LocalSettings.php'
	 * @li @c	'page' For all links in 'MediaWiki:FooterBar' (depends on i18)
	 * @li @c	'user' For all links in 'User:Username/FooterBar' (depends on i18)
	 * @param	array $contatArray
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::getConcat
	 * @see		FooterBar::$footerBarConcatArray
	 */
	public function setConcat( $contatArray ){
		if( !is_array( $contatArray ) )
			return false;
		$this->footerBarConcatArray = $contatArray;
		return true;
	}

	/**
	 * @brief	Function to receive the concatenation order as an array.
	 * @return	_array_ FooterBar::$footerBarConcatArray
	 * @see		FooterBar::setConcat
	 */
	public function getConcat( ){
		return $this->footerBarConcatArray;
	}

	/**
	 * @brief	Function to define all links at once via one function to FooterBar::$footerBarContentArray.
	 * @param	array $linksArray	
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::appendLink
	 */
	public function setLinks( $linksArray ){
		if( is_array( $linksArray ) == false )
			return false;
		$this->footerBarContentArray['array'] = array();
		$gate = true;
		foreach( $linksArray as $linkArray )
			if( $this->checkLinkArray( $linkArray ) == true )
				$this->footerBarContentArray['array'][] = $linkArray;
			else
				$gate = false;
		return $gate;
	}

	/**
	 * @brief	Function to receive all links from the array.
	 * @return	array FooterBar::$footerBarContentArray
	 */
	public function getLinks( ){
		return $this->footerBarContentArray;
	}

	/**
	 * @brief	Function to append links one after another to FooterBar::$footerBarContentArray.
	 * @par		Usage
	 *			array( 'link'=>string, 'name'=>string, 'title'=>string, 'target'=>string, 'id'=>string, 'class'=>string, 'menu'=>string 'groups'=>array )
	 * @par		Possible values
	 * @li @c	'link' For the link
	 * @li @c	'name' For the text of the link
	 * @li @c	'title' For the title of the link
	 * @li @c	'target' For all links in 'User:Username/FooterBar'
	 * @li @c	'id' For the id of the link
	 * @li @c	'class' For the class of the link
	 * @li @c	'menu' For the menu
	 * @li @c	'groups' For protection
	 * @param	array $linksArray
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::setLinks
	 */
	public function appendLink( $linkArray ){
		if( $this->checkLinkArray( $linkArray ) == false )
			return false;
		$this->footerBarContentArray['array'][] = $linkArray;
		return true;
	}
	
	/**
	 * @brief	Function to append links one after another to FooterBar::$footerBarContentArray.
	 * @par		Usage
	 *			array( 'link'=>string, 'target'=>string, 'text'=>'string', 'title'=>string, 'menu'=>string, 'groups'=>array, 'class'=>string, 'id'=>string, 'name'=>string )
	 * @par		Possible values
	 * @li @c	'link' For the link
	 * @li @c	'target' For the target
	 * @li @c	'text' For the text of the link
	 * @li @c	'title' For the title of the link
	 * @li @c	'menu' For the menu
	 * @li @c	'groups' For protection
	 * @li @c	'class' For the class of the link
	 * @li @c	'id' For the id of the link
	 * @li @c	'name' For the name of the link
	 * @param	string $linkString
	 *			Link to the source: 'Category:Help'
	 * @param	string $targetString
	 *			Target of the link: '&action=edit'
	 * @param	string $textString
	 *			Text of the link: 'Get Help!'
	 * @param	string $titleString
	 *			Title of the link: 'Here you will receive help.'
	 * @param	string $menuString
	 *			Name of the menu: 'Help Section'
	 * @param	array $groupsArray
	 *			Groups of the link: array( 'sysop', 'admin' )
	 * @param	string $classString
	 *			Id of the link: 'help'
	 * @param	string $idString
	 *			Id of the link: 'help'
	 * @param	string $nameString
	 *			Text of the link: 'help'
	 * @return	_boolean_ 'True', if function was successful or 'false', if it wasn't successful.
	 * @see		FooterBar::appendLink
	 */
	public function setLink( $linkString, $targetString='', $textString='', $titleString='',$menuString='', $groupsArray=array(''), $classString='', $idString='', $nameString='' ){
		$linkArray = array( 'link'=>$linkString, 'target'=>$targetString, 'text'=>$textString, 'title'=>$titleString, 'menu'=>$menuString, 'groups'=>$groupsArray, 'class'=>$classString, 'id'=>$idString, 'name'=>$nameString );
		if( $this->checkLinkArray( $linkArray ) == false )
			return false;
		$this->footerBarContentArray['array'][] = $linkArray;
		return true;
	}

	/**
	 * @brief	Function to receive one link from the links by the given key.
	 * @param	string $key	
	 * @return	_array|boolean_ Returns array, if $key is avaible/set or 'false', if not.
	 * @see		FooterBar::getLinks
	 */
	public function getLinkFromId( $key ){
		if( isset($this->footerBarContentArray['array'][$key]) )
			return $this->footerBarContentArray['array'][$key];
		else
			return false;
	}

	/**
	 * @brief	Function to check, if the variable 'MEDIAWIKI' is defined.
	 * @details	Function to check, if the variable 'MEDIAWIKI' is defined.
	 *			It will print an usage dialoge and exit the execution, if the variable is not defined.
	 *			This function will be automatically called by the constructor.
	 * @see		FooterBar::FooterBar
	 */
	private function checkForMediaWiki( ){
		if( !defined('MEDIAWIKI') ){ 
			echo "To install the 'FooterBar' extension, put the following line in LocalSettings.php:";
			echo "<br><dl><dd>require_once( '\$IP/extensions/FooterBar/FooterBar.php' );</dd></dl>";
			echo "For more information read the <a href='./README.md'>README</a> :)";
			exit( 1 );
		}
	}
	
	/**
	 * @brief	Function to initialize all necessary settings for MediaWiki, such as registration hooks, appending credits, defining the special page etc.
	 *			This function will be automatically called by the constructor.
	 * @return	_boolean_ 'True'
	 */
	private function initFooterBar( ){
		global $wgExtensionCredits, $wgHooks, $wgExtensionMessagesFiles, $wgResourceModules, $wgAvailableRights, $wgGroupPermissions, $wgAutoloadClasses, $wgSpecialPages, $wgSpecialPageGroups;
		
		//Credits of FooterBar (see Special:Version)
		$wgExtensionCredits['FooterBar'][] = array(
			'path' => __FILE__,
			'name' => 'FooterBar',
			'version' => '0.1',
			'author' => 'Mike Knappe',
			'descriptionmsg' => 'footerbar-desc'
		);
			
		//Setup the needed hooks
		$wgHooks['ResourceLoaderRegisterModules'][] = array($this, 'resourceLoaderRegisterModules');//'footerBarClass->resourceLoaderRegisterModules';
		$wgHooks['ArticlePageDataAfter'][] = array($this, 'createFooterBar');//'footerBarClass->createFooterBar';
		$wgHooks['SpecialPageBeforeExecute'][] = array($this, 'createFooterBar');//'footerBarClass->createFooterBar';
		$wgHooks['SkinAfterContent'][] = array($this, 'addFooterBar');//'footerBarClass->addFooterBar';
		
		//AutoLoad Special page as Class aka Key from file
		$wgAutoloadClasses['SpecialFooterBar'] = dirname( __FILE__ ) . '/FooterBarHelp.php';
		
		//Register Special page from classname
		$wgSpecialPages['FooterBar'] = 'SpecialFooterBar';
		
		//Register Special page in the SpecialPages (see i18)
		//Key links into the i18-file
		$wgSpecialPageGroups['FooterBar-Help'] = 'ext-help';
		
		//Set rights for the links
		//$wgGroupPermissions['sysop']['footerbar-help'] = true;
		//$wgAvailableRights[] = 'footerbar-help';

		//Define the internationalization file
		$wgExtensionMessagesFiles['FooterBar'] = dirname( __FILE__ ) . '/FooterBar.i18n.php';
		
		//Define the alias file, where url aliases are define. The first item will be called.
		$wgExtensionMessagesFiles['FooterBarAlias'] = dirname( __FILE__ ) . '/FooterBar.alias.php';

		//Setup the needed resources via the ResourceLoader
		$wgResourceModules['FooterBar'] = array(
			'localBasePath' => dirname( __FILE__ ),
			'remoteExtPath' => 'FooterBar',
			'styles' => 'FooterBar.css',
			//'scripts' => 'FooterBar.js',
		);
		return true;
	}
	
	/**
	 * @brief	Function to register the previously added resources in the ResourceLoader.
	 *			Called by the MediaWiki hook 'ResourceLoaderRegisterModules'.
	 * @return	_boolean_ 'True'
	 * @see		FooterBar::initFooterBar
	 */
	public function resourceLoaderRegisterModules( &$resourceLoader ) {
		global $wgOut;
		$wgOut->addModules( 'FooterBar' );
		return true;
	}
	
	/**
	 * @brief	Function to extract only links and listings from the given plain text. It returns an array with the structure of the internal array.
	 * @param	string $plaintext
	 *			The plaintext of a certain wiki page.
	 * @return	_array_ An array with the structure of the internal array.
	 */
	private function extractLinksFromText( $plaintext ) {
		$footerLineBreakArray = explode( '
', $plaintext );
		$cLinkArray = array();
		$menuNameString = "";
		$popArray = array();
		$popPosInt = 0;
		$menuCountInt = 0;
		for( $i = 0; $i < count($footerLineBreakArray); $i++ ){
			$footerLineLinksArray = explode( ']]', $footerLineBreakArray[$i] );
			if( strpos($footerLineBreakArray[$i], '*' ) !== false && strpos($footerLineBreakArray[$i],'*') == 0 && $i > 0 ){
				if( $menuNameString == '' ){
					$popPosInt = count($cLinkArray)-1;
					$menuNameString = $cLinkArray[$popPosInt]['text'];
				}
			}else{
				if( $menuNameString != '' && $menuCountInt > 0 ){
					$popArray[] = $popPosInt;
					$menuCountInt = 0;
					$menuNameString = '';
				}
			}
			
			array_pop( $footerLineLinksArray );
			foreach( $footerLineLinksArray as $linkArray ){
				$link = '';
				$text = '';
				$footerLinkArray = explode( '[[', $linkArray );
				$footerLinkAttrArray = explode( '|', $footerLinkArray[1] );
				switch( count($footerLinkAttrArray) ){
					case 3:
						$link = $footerLinkAttrArray[0];
						array_shift( $footerLinkAttrArray );
						$text = implode('|',$footerLinkAttrArray);
					break;
					case 2:
						$link = $footerLinkAttrArray[0];
						$text = $footerLinkAttrArray[1];
					break;
					case 1;
					default:
						$link = $footerLinkAttrArray[0];
						$text = $footerLinkAttrArray[0];
					break;
				}
				if( $menuNameString == '' )
					$cLinkArray[] = array( 'link'=>$link, 'text'=>$text );
				else{
					$cLinkArray[] = array( 'link'=>$link, 'text'=>$text, 'menu'=>$menuNameString );
					$menuCountInt++;
				}
			}
		}
		for( $i = 0; $i < count($popArray); $i++ ){
			unset($cLinkArray[$popArray[$i]]);
		}
		return $cLinkArray;
	}
	
	/**
	 * @brief	Function to insert HTML at certain positions in the template of the FooterBar.
	 * @param	string $htmlTextString
	 *			The htmlTextString is a string with the html text that should be inserted.
	 * @param	string $htmlPosition
	 *			The htmlPosition is a string comming from the class ConstFooterBarHTML.
	 * @return	_string_ Html-formatted string with the links and menus.
	 * @see		ConstFooterBarHTML
	 */	
	private function insertHtmlToFooterBar( $htmlTextString, $htmlPosition ){
		$this->footerBarHtmlTemplate = str_replace( $htmlPosition, $htmlTextString, $this->footerBarHtmlTemplate );
	}
		
	/**
	 * @brief	Function to insert all errors, that happened to the extension.
	 * @details	Function to insert all errors, that happened to the extension.
	 *			Its called in FooterBar::createFooterBar, if FooterBar::$footerBarShowErrorsBool is true.
	 *			All errors are stored in the array FooterBar::$footerBarErrorsArray via FooterBar::createErrorMsg.
	 */	
	private function displayErrors( ){
		global $wgOut;
		
		if( $this->footerBarShowErrorsBool == true ){
			$errorHtmlArray = array();
			
			$errorMsgString = "";
			foreach( $this->footerBarErrorsArray as $footerBarErrorString ){
				$errorMsgString = wfMessage( $footerBarErrorString[0] )->params( $footerBarErrorString[1] )->inContentLanguage()->plain();
				if( $errorMsgString == '' ){
					$errorMsgString = wfMessage( "footerbar-error-msgerror" )->params( $footerBarErrorString[1] )->inContentLanguage()->plain();
				}
				$errorHtmlArray[] = '<li>FooterBar Ext. :: '.$errorMsgString.'</li>';
				$errorMsgString = "";
			}
			
			if( count($errorHtmlArray)>0 )
				$wgOut->addHTML('<div class="footerBarErrors"><ul>'.implode( '', $errorHtmlArray ).'</ul></div>');
		}
	}
	
	/**
	 * @brief	Function to create the entire html structure of the FooterBar and store the html.
	 *			Called by the MediaWiki hook 'ArticlePageDataAfter' and 'SpecialPageBeforeExecute'.
	 * @return	_boolean_ 'True'
	 */
	public function createFooterBar( $article, $row ){
		global $wgOut, $wgUser;
		
		$this->displayErrors();
		
		if( $this->footerBarHideAbleBool == true )
			$wgOut->addHeadItem( "footerBar", "<script type='text/javascript'>function initFooterBar(){ if( $.cookie( \"FooterBar\" ) == \"Off\" ){ document.getElementById(\"mw-customcollapsible-footerBarStyle\").className = \"mw-collapsible footerBarStyle mw-collapsed\"; } } function storeFooterBar(){if(document.getElementById(\"mw-customcollapsible-footerBarStyle\").className==\"mw-collapsible footerBarStyle mw-made-collapsible mw-collapsed\"){ $.cookie( 'FooterBar', 'On', { expires: 7, path: '/'} ); }else{ $.cookie( 'FooterBar', 'Off', { expires: 7, path: '/'} ); }}</script>");

		if( $this->footerBarShowHelpBool == true ){
			$specialPage = Title::newFromText( 'Special:FooterBar' );
			$footerBarHelpLink = "<a href='".$specialPage->getFullURL( )."' class='mw-collapsible-content footerBarHelp'>".wfMessage( 'footerbar-help-sign' )->inContentLanguage()->plain()."</a>";
			$this->insertHtmlToFooterBar( $footerBarHelpLink, ConstFooterBarHTML::HELP );
		}
		
		$titleObject = "";
		$titleObjArr = array();
		if( $this->footerBarUserBool == true && $wgUser->getId() != 0 )
			$titleObjArr['user'] = Title::newFromText( wfMessage( 'footerbar-link-user' )->params($wgUser->getName())->inContentLanguage()->plain().'/FooterBar' );
		if( $this->footerBarPageBool == true )
			$titleObjArr['page'] = Title::newFromText( wfMessage( 'footerbar-link-page' )->inContentLanguage()->plain().':FooterBar' );
		foreach( $titleObjArr as $kkey => $titleObject ){
			if( $titleObject != "" && $titleObject->exists( ) == true ){
				$pageObject = WikiPage::newFromId( $titleObject->getArticleID() );
				$footerTextPlain = $pageObject->getText( );
				$footerTextHTML = $this->extractLinksFromText( $footerTextPlain );
				$this->footerBarContentArray[$kkey] = $footerTextHTML;
			}
		}
		
		wfRunHooks( 'FooterBarRequestFinished', array(&$this->footerBarContentArray) );
		
		$barContentHTML = array( 'user'=>'', 'page'=>'', 'array'=>'' );
		
		if( is_array($this->footerBarContentArray) ){
			$linkArray = array();
			$menuArray = array();
			$uGroups = $wgUser->getGroups();
			foreach($this->footerBarContentArray as $key2=>$value2){
				foreach($value2 as $key=>$value){
					
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
						$value['link'] = str_replace( ConstFooterBarURL::TOP, '#mw-page-base', $value['link'] );
						$value['link'] = str_replace( ConstFooterBarURL::BOTTOM, '#mw-data-after-content', $value['link'] );
						$value['link'] = str_replace( ConstFooterBarURL::SELF, $article->getTitle(), $value['link'] );
						
						$titleObject = Title::newFromText( $value['link'] );
						$cString = '<a href="';
						$cString .= $titleObject->getLinkURL();
						$cString .= '" ';
						
						$checkStrings = array('title', 'target', 'class', 'id', 'name');
						foreach($checkStrings as $checkString)
							if(isset($value[$checkString]) && $value[$checkString]!='')
								$cString .= ' '.$checkString.'="'.$value[$checkString].'" ';
						
						$cString .= '>';
						
						if(isset($value['text']) && $value['text']!='')
							$cString .= $value['text'];
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
				$mArray[$mKey] = '<div class="menu aaa"><ul id="footerBarMenu_'.$mKey.'"><li>'.implode($mVal, '</li><li>').'</li></ul></div>';
			}
			$lCounter = 0;
			if(count($linkArray)>0)
				$barContentHTML['array'] .= '<ul>';
			foreach($linkArray as $lKey => $lLink){
				$barContentHTML['array'] .= '<li>';
				if($lKey.'' == $lLink){
					if($lCounter!=0);
						$barContentHTML['array'] .= '<div class="vectorMenu"><a href="#">'.$lKey.'</a>'.$mArray[$lKey].' </div>';
				}else{
					$barContentHTML['array'] .= $lLink;
				}
				/*if($lCounter<count($linkArray)-1)
					$barContentHTML['array'] .= wfMessage( 'footerbar-delimiter' )->inContentLanguage()->plain();*/
				$barContentHTML['array'] .= '</li>';
				$lCounter++;
			}
		}else{
			$this->errorMsg('footerbar-error-array');
		}
		

		$outputContentHTML = $this->doAreaConcat( $barContentHTML );
		$this->insertHtmlToFooterBar( implode( $outputContentHTML, '' ), ConstFooterBarHTML::CONTENT );
		
		if( $this->footerBarHideAbleBool == true ){
			$footerBarHideButton = "<div class='mw-customtoggle-footerBarStyle footerBarArrow".($this->footerBarArrowFloatString=='left'?' footerBarArrowLeft':'')."' id='footerBarArrow' onclick='storeFooterBar();'></div>";
			$this->insertHtmlToFooterBar( $footerBarHideButton, ConstFooterBarHTML::HIDE);
		}
		//$ttt = $this->footerBarHtmlTemplate;
		wfRunHooks( 'FooterBarCreated', array(&$this->footerBarHtmlTemplate) );
		//$this->footerBarHtmlTemplate = $ttt;

		$this->footerBarHtmlContent = $this->footerBarHtmlTemplate;
		return true;
	}
	
	/**
	 * @brief	Based on the avaible option FooterBar::footerBarConcatArray, which function will order the given array by the and return it.
	 * @param	array $barContentHTML
	 * @return	_array_ $barContentHTML (concatenated)
	 */
	private function doAreaConcat( $barContentHTML ){
		$outputContentHTML = array();
		if( is_array($this->footerBarConcatArray) == true ){
			foreach( $this->footerBarConcatArray as $value )
				if( isset($barContentHTML[$value]) == true && $barContentHTML[$value] != '' )
					$outputContentHTML[] = $barContentHTML[$value];
		}
		if( count($outputContentHTML) == 0 ){
			foreach( $barContentHTML as $value )
				if( $value != "" ){	$outputContentHTML[] = $value; }
		}
		return $outputContentHTML;
	}
	
	/**
	 * @brief	Function to check a given link and reject it, if it's broken. It throws error messages, if FooterBar::$footerBarShowErrorsBool is enabled.
	 *			Called in FooterBar::createFooterBar.
	 * @param	array $value	
	 *			The array contains all parameters of the given link.
	 * @param	string $key
	 *			The key is needed to generate the certain internationalized error message.
	 * @return	_boolean_ 'True', if link is valid or 'false', if link is broken.
	 */
	private function checkLinkArray( $linkArray ){
		$gate = true;
		
		if( !is_array( $linkArray ) ){
			$this->createErrorMsg( 'array' );
			$gate = false;
		}
		
		if( isset($linkArray['link']) ){
			if( !is_string($linkArray['link']) || $linkArray['link'] == ''){
				$this->createErrorMsg( 'link' );
				$gate = false;
			}
		}else{
			$this->createErrorMsg( 'link');
			$gate = false;
		}
		
		if( isset($linkArray['groups']) )
			if( !is_array($linkArray['groups']) || count($linkArray['groups']) == 0 ){
				$this->createErrorMsg( 'groups' );
				$gate = false;
			}
		
		$checkStrings = array( 'target', 'text', 'title', 'menu', 'class', 'id', 'name' );
		foreach( $checkStrings as $checkString ){
			if( isset($linkArray[$checkString]) )
				if( !is_string($linkArray[$checkString]) ){
					$this->createErrorMsg( $checkString );
					$gate = false;
				}
		}
		
		return $gate;
	}
	
	/**
	 * @brief	Function to generate an error message for internationalized message id.
	 * @param	string $i18ErrorString
	 *			The internationalization key of the i18-file.
	 * @return	_boolean_ 'True', if error creating was successful or 'false', if error creating was not successful.
	 * @see		FooterBar::displayErrors
	 * @see		FooterBar::$footerBarShowErrorsBool
	 */
	private function createErrorMsg( $i18ErrorString ){
		$this->footerBarErrorsArray[] = array( 'footerbar-error-'.$i18ErrorString, count($this->footerBarContentArray['array'])-1 );
		return true;
	}
	
	/**
	 * @brief	Function to add the FooterBar html to the MediaWiki html output.
	 *			Called by the MediaWiki hook 'SkinAfterContent'.
	 * @param	string $text	
	 *			Text to be printed out directly (without parsing)
	 * @param	Skin $skin
	 *			Skin object
	 * @return	_boolean_ 'True'
	 */
	public function addFooterBar( &$text, Skin $skin ){
		$text .= $this->footerBarHtmlContent;
		return true;
	}

}

//Initialize an object of the FooterBar class.
$footerBarClass = new FooterBar();