<?php
/**
 * @brief	Array of the internationalized content of the extension.
 * @var		array $messages
 * @see		FooterBar
 */
$messages = array();

/** @brief	English */
$messages['en'] = array(
	'specialpages-group-ext-help' => 'Extension Help',	//Title of the group ext-help in 'Special:SpecialPages'
	'footerbar' => 'FooterBar',
	'footerbar-desc' => 'The extension "FooterBar" will add a fixed customizable bar at the bottom of each page. See [[Special:FooterBar]]',
	'footerbar-delimiter' => ' | ',
	'footerbar-content' => '$1',			//Used to parse text.
	'footerbar-link-user' => 'User:$1',		//Where to collect the user created FooterBar page. (User:Username/FooterBar)
	'footerbar-link-page' => 'MediaWiki',	//Where to collect the admin created FooterBar page. (MediaWiki:FooterBar)
	//Error Messages
	'footerbar-error-msgerror' => 'Failure to parse error message.',
	'footerbar-error-array' => 'Please set a valid "array" at array position "$1".',
	'footerbar-error-link' => 'Please set a valid "link" at array position "$1".',
	'footerbar-error-text' => 'Please set a valid "page name" at array position "$1".',
	'footerbar-error-name' => 'Please set a valid "name" at array position "$1".',
	'footerbar-error-title' => 'Please set a valid "title" at array position "$1".',
	'footerbar-error-target' => 'Please set a valid "target" at array position "$1".',
	'footerbar-error-class' => 'Please set a valid "class" at array position "$1".',
	'footerbar-error-id' => 'Please set a valid "id" at array position "$1".',
	'footerbar-error-groups' => 'Please set a valid "group array" at array position "$1".',
	//Help Page
	'footerbar-help' => 'FooterBar Help',
	'footerbar-help-sign' => ' ? ',
	'footerbar-help-intro' => 'This is the help page of the MediaWiki extension "FooterBar".
==What is FooterBar?==
The extension "FooterBar" will add a fixed customizable bar at the bottom of each page.
',
	'footerbar-help-user-enabled' => '==How to use?==
As a user you can use this extension to add links to the bar as some kind of shortcuts. Therefore use your own namespace page: [[$1]]

Create the given page and add links to it. You can also create menus by using listings. See the following example:<br />
 <nowiki>[[Test]]</nowiki>
 <nowiki>[[Test|Title]]</nowiki>
 <nowiki>* [[Category:Test|Test]]</nowiki>

Nested listing (**) is not supported. All other content of the page will be ignored.
',
	'footerbar-help-user-disabled' => '==How to use?==
Currently users are not allowed to customize the bar on their own, because the function is disabled. You can only use the avaible links of the bar.
',
	'footerbar-help-admin' => '==For Admins==
See the readme file of this extension.
',
);