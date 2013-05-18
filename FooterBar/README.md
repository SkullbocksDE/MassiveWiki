MediaWiki :: Extension :: FooterBar
===========

This code is an extension for MediaWiki ( >= v.1.2).

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
* $footerBarArray[]= array('name'=>'Help');
* $footerBarArray[]= array('namespace'=>'Category','name'=>'Help');

To append a string at the end of a url use 'target':
* $footerBarArray[]= array('namespace'=>'Category','name'=>'Help','target'=>'/Subpage');
* $footerBarArray[]= array('namespace'=>'Category','name'=>'Help','target'=>'&action=edit');

To append the current page title to the end of the url use 'target' 'self':
* $footerBarArray[]= array('namespace'=>'Special','name'=>'WhatLinksHere','target'=>'self');

To set group rights to a link, use 'groups':
* $footerBarArray[]= array('namespace'=>'Special','name'=>'WhatLinksHere','target'=>'self','groups'=>array('admin'));
** Note: There are as default groups "user", "bot", "bureaucrat", "comment", "admin", "sysop"

To turn off the hide-function use this:
* $footerBarHideable = false;

To edit the content of the FooterBar within the MediaWiki, turn on the page detection:
* $footerBarPage = true;
** Note1: You have to create the page 'MediaWiki:FooterBar' with content/links to use this function.
** Note2: The global array (from above) will be no longer used, if the page exists and the value is true.

To allow users to customize the FooterBar within the MediaWiki, turn on the user page detection:
* $footerBarUser = true;
** Note1: A user can create the page 'User:Username/FooterBar' with content/links to use this function.
** Note2: The global array (from above) and the 'MediaWiki:FooterBar' will be ignored, if the page exists and the value is true.

To redesign the bar via the MediaWiki:Common.css use this classes:
* .footerBar
* .footerBarContent
* .footerBarArrow

To internationalize the extension for your language use the i18-file:
* FooterBar.i18.php

History
===========

* (fixed): Extension was called multiple times by the hook 'SkinAfterContent', 'ParseAfterTidy' and 'ParseBeforeTidy', where as 'AfterFinalPageOutput' has done bullshit at the end of the page. Now using 'OutputPageParserOutput'.
* (fixed): Couldn't detect the global array.
* (fixed): It wasn't possible to skin the FooterBar. Now using the new ResourceLoader for CSS and JS.
* (fixed): It wasn't possible to use i18. Now included.
* (fixed): It wasn't possible to hide the bar. Javascript included.
* (fixed): It wasn't possible to hide the bar via value. New value added, to allow/deny hiding the bar.
* (fixed): It wasn't possible to use i18 for some text. Now all output is in i18.
* (fixed): There was no explanation of the usage of the global array. Readme is now avaible.
* (fixed): It wasn't possible to change the FooterBar content within the MediaWiki. Now using the page MediaWiki:FooterBar. The variable '$footerBarPage' has to be set 'true' to use the page.
* (fixed): All content of the refered FooterBar page was parsed. Now extracting links.
* (fixed): Parser detected images as links. Now only extracting links.
* (fixed): It wasn't possible to customize the FooterBar as user. Now using the page 'User:Username/FooterBar'. The variable '$footerBarUser' has to be set 'true' to allow users to customize the bar.
* (fixed): IP users were able to create a FooterBar for the ip. IP users will now be denied.
* (fixed): It wasn't possible to concatenate the certain areas like 'MediaWiki:FooterBar' and the global array. The array '$footerBarConcat' will handle the concatenate. Possible values: 'array', 'page', 'user'. The order of the entries in this array effects the concatenate order. If the $footerBarConcat is empty, the highest area will be selected: user >> page >> array
* (fixed): If wasn't possible to declare a link of the global array only for admins. New field inserted.
* (fixed): There where no error messages from the script. Now added into source and i18.
* (open) : The 'self' link doesn't work proper, because of the getTitle-function.
* (open) : If no value of '$footerBarConcat' fits, then the FooterBar is empty.
 
ToDo
===========

* Create a more readable source code with code reduction by using loops.
* Add class/name/title tag to the links.
* Test the FooterBar on other browsers.
* Include JS via file, currently it's an inline script.
* Declare an option, where the current status of the bar is saved in during a session.
* Implement to add multiple links as a hove menu.
* Use the class name of the FooterBar to switch between hide/show (for JS).
