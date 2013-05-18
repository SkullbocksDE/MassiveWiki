MediaWiki :: Extension :: FooterBar
===========

This code is an extension for MediaWiki ( > v.1.2).
FooterBar adds a bar at the bottom of each page.
You can add links via a global array to the FooterBar.
It's possible to do some personalization (read further).

* Author: Mike Knappe
* Version: 0.1
* Copyright: © 2013 Mike Knappe
* License: GNU General Public Licence 2.0 or later
	
Install Notes
===========

To install the 'FooterBar' extension, put the following line in LocalSettings.php:
* require_once( '$IP/extensions/FooterBar/FooterBar.php' );

Personalization
===========

To append links to the FooterBar, use the following code in LocalSettings.php:
* $footerBarArray[]= array('namespace'=>'Category','name'=>'Help','target'=>'');
	
To append a string at the end of a url use 'target':
* $footerBarArray[]= array('namespace'=>'Category','name'=>'Help','target'=>'/Subpage');
* $footerBarArray[]= array('namespace'=>'Category','name'=>'Help','target'=>'&action=edit');
	
To append the current page title to the end of the url use 'target' 'self':
* $footerBarArray[]= array('namespace'=>'Special','name'=>'WhatLinksHere','target'=>'self');
	
To turn off the hide-function use this:
* $footerBarHideable = false;
	
To redesign the bar via the MediaWiki:Common.css use this classes:
* .footerBar
* .footerBarContent
* .footerBarArrow
	
To internationalize the extension for you language use the i18-file:
* FooterBar.i18.php
		
History
===========

(fixed): Extension was called multiple times by the hook 'SkinAfterContent', 'ParseAfterTidy' and 'ParseBeforeTidy',
			where as 'AfterFinalPageOutput' has done bullshit at the end of the page. Now using 'OutputPageParserOutput'.
(fixed): Couldn't detect the global array.
(fixed): It wasn't possible to skin the FooterBar. Now using the new ResourceLoader for CSS and JS.
(fixed): It wasn't possible to use i18. Now included.
(fixed): It wasn't possible to hide the bar. Javascript included.
(fixed): It wasn't possible to hide the bar via value. New value added, to allow/deny hiding the bar.
(fixed): It wasn't possible to use i18 for some text. Now all output is in i18.
(fixed): There was no explanation of the usage of the global array. Readme is now avaible.
	
ToDo
======================

* Add class/name/title tag to the links.
* Test the FooterBar on other browsers.
* Include JS via file, currently it's an inline script.
* Declare an option, where the current status of the bar is saved in during a session.
* Implement to add multiple links as a hove menu.
* Implement a function to fetch a certain page instead of using the global array (like the mediawiki navigation).
* Use the class name of the FooterBar to switch between hide/show (for JS).
