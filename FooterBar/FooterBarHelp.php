<?php

/**
 * @brief	Creates a new special page for the FooterBar as help page. It creates its own group in Special:SpecialPages.
 * @details	The SpecialFooterBar is an inheritance from SpecialPage. It creates a new special page for the FooterBar as help page, called 'Special:FooterBar'.
 *			The main function is FooterBar::execute, which generates the i18 content and parses it to the wiki.
 *			The constructor needs to construct at its parent the given page, that is defined in the alias file.
 *			It creates its own group in Special:SpecialPages.
 * @see		FooterBar::initFooterBar
 */
class SpecialFooterBar extends SpecialPage {

	/**
	 * @brief	Constructor of the SpecialFooterBar class.
	 */
	function __construct(){
		parent::__construct( 'FooterBar-Help' );
	}

	/**
	 * @brief	Function to create a new special page within the wiki, called 'Special:FooterBar'.
	 * @param	string $par
	 *			For example, if someone follows a link to Special:MyExtension/blah, $par will contain "blah".
	 */
	function execute( $par ) {
		global $wgOut, $wgUser, $footerBarUser;

		$wgOut->setPageTitle( wfMessage( 'footerbar-help' )->inContentLanguage()->plain() );
		$wgOut->addHTML( wfMessage( 'footerbar-help-intro' )->inContentLanguage()->parse() );
		if( $footerBarUser==true && $wgUser->getId( ) != 0 )
			$wgOut->addHTML( wfMessage( 'footerbar-help-user-enabled' )->params( 'User:'.$wgUser->getName().'/FooterBar' )->inContentLanguage()->parse() );
		else
			$wgOut->addHTML( wfMessage( 'footerbar-help-user-disabled' )->inContentLanguage()->parse() );
		$wgOut->addHTML( wfMessage( 'footerbar-help-admin' )->inContentLanguage()->parse() );
	}
}