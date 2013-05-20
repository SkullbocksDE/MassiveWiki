MediaWiki :: Extension :: FooterBar
===========

Description
-----------
This code is an extension for MediaWiki ( >= v.1.2).

FooterBar adds a bar with links at the bottom of each page.

You can add links to the FooterBar via:
* a global array (LocalSettings.php) = highly customizable
* a wiki page (MediaWiki:FooterBar) = accessible for admins, sysops within the wiki
* a user page (User:Username/FooterBar) = accessible for all users (not IP) within the wiki

It's possible to do much more personalization (read further).

### Meta Data ###
* Author: Mike Knappe
* Version: 0.4
* Copyright: © 2013 Mike Knappe
* License: GNU General Public Licence 2.0 or later

Install Notes
-----------
To install the 'FooterBar' extension, put the following line in LocalSettings.php:
* require_once( '$IP/extensions/FooterBar/FooterBar.php' );

Define Links
-----------
You have three options to add links to the FooterBar.

1. Add links via the global array '$footerBarArray' in 'LocalSettings.php'

> * This method gives you the chance to add links and to customize the links on your own via 'id', 'class', etc.

2. Add links via the wiki page 'MediaWiki:FooterBar' (see option '$footerBarPage')

> * The second method gives you the chance to add links to the bar within the MediaWiki and without changing the LocalSettings.php anymore.
> * People like admins and sysops, all who have the right to change the content within the 'MediaWiki' namespace, can sipport the FooterBar.

3. Let user add their own links via their wiki page 'User:Username/FooterBar' (see option '$footerBarUser')

> * This method gives you the power to allow users to add links on their own.

Remember: If you are not using the $footerBarConcat, the extension will show the first allowed accessible content: user >> page >> array

### The Global Array ###
To append links to the FooterBar, use the following code in LocalSettings.php:
* $footerBarArray[]= array('link'=>'Help');
* $footerBarArray[]= array('link'=>'Category:Help');

To set an own name for the link use 'name':
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!');

To set the title of the link use 'title':
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!','title'=>'This link will show you the Help category.');

To set an id for the link, use 'id':
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!','id'=>'Help');

> * Note1: FooterBar will always prepend "footerBarItem_" to the id!
> * Note2: The above code will result in "footerBarItem_Help".

To set a class for the link, use 'class':
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!','class'=>'Help');

> * Note1: FooterBar will always prepend "footerBarItem_" to the class!
> * Note2: The above code will result in "footerBarItem_Help".

To append a string at the end of a url use 'target':
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!','target'=>'/Subpage');
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!','target'=>'&action=edit');
* $footerBarArray[]= array('link'=>'Category:Help/Subpage','name'=>'Help Me!','target'=>'&action=edit');

> * Note: The parameter 'target' will not be checked by any script, except for the value 'self', see below.

To append the current page title to the end of the url use 'target' 'self':
* $footerBarArray[]= array('link'=>'Special:WhatLinksHere','name'=>'WhatLinksHere','target'=>'self');

> * Note: The above code will result in "Special:WhatLinksHere/CurrentPage".

To set group rights to a link, use 'groups':
* $footerBarArray[]= array('link'=>'Special:WhatLinksHere','name'=>'WhatLinksHere','target'=>'self','groups'=>array('admin'));

> * Note1: Make sure, that you declared an array of strings! 'groups'=>'admin' will not work!
> * Note2: There are as default groups "user", "bot", "bureaucrat", "comment", "admin", "sysop"

To create a menu of links, use 'menu':
* $footerBarArray[]= array('link'=>'PI_Test','name'=>'PI Test');
* $footerBarArray[]= array('link'=>'Category:Help','name'=>'Help Me!','menu'=>'Help-Section');
* $footerBarArray[]= array('link'=>'IQ_Test','name'=>'IQ Test');
* $footerBarArray[]= array('link'=>'Category:Help/HowTo','name'=>'Need HowTo?','menu'=>'Help-Section');

> * Note1: You only have to enter the name of the menu you want into the field 'menu'.
> * Note2: The above example would result in: PI Test | Help-Section >> ( Help Me! | Need HowTo? ) | IQ Test
> * Note3: As you see, the first occurrence of the menuname will create the menu at this position and place all other links with the same menuname into it.

### The MediaWiki:FooterBar Page and  Page ###
The only thing you have to do to use this method:
* Turn on the needed option '$footerBarPage' or '$footerBarUser'.
* Create the page 'MediaWiki:FooterBar' / 'User:Username/FooterBar' and insert the links you want to have in the FooterBar.

Note: Text, Images, Linebreak, Videos, Signs, Tables and all that will be ignored from the script, it only picks the links.

Options
-----------
To turn off the hide-function use this:
* $footerBarHideable = false;

To edit the content of the FooterBar within the MediaWiki, turn on the page detection:
* $footerBarPage = true;

> * Note1: You have to create the page 'MediaWiki:FooterBar' with content/links to use this function.
> * Note2: The global array (from above) will be no longer used, if the page exists and the value is true.

To allow users to customize the FooterBar within the MediaWiki, turn on the user page detection:
* $footerBarUser = true;

> * Note1: A user can create the page 'User:Username/FooterBar' with content/links to use this function.
> * Note2: The global array (from above) and the 'MediaWiki:FooterBar' will be ignored, if the page exists and the value is true.

To concatenate the certain areas like 'MediaWiki:FooterBar', the global array and 'User:Username/FooterBar' use this:
* $footerBarConcat = array('array','page','user');

> * Note1: Possible values: 'array', 'page', 'user'
> * Note2: The order of the entries in this array effects the concatenate order.
> * Note3: If the array is empty, the following areas will be checked for content: user >> page >> array

To debug the global array of the FooterBar, maybe because you are missing a link, then use this:
* $footerBarShowErrors = true;

> * Note1: Default value is 'false'.
> * Note2: FooterBar will auto. drop items/links, which are not declared in the right way like an array instead of a string.
 
Styling
-----------
To redesign the bar via the MediaWiki:Common.css use these classes:
* .footerBar
* .footerBarContent
* .footerBarArrow
* .footerBarError

To redesign the links by using the 'class' or 'id' parameter:
* .footerBarItem_{parameter}
* #footerBarItem_{parameter}

Internationalization
-----------
To internationalize the extension for your language use the i18-file:
* FooterBar.i18.php

History
-----------
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
* (fixed): On SpecialPages the FooterBar was hooked multiple time and it was added to the top. Now using again SkinAfterContent.
* (fixed): It wasn't possible to name a link on your own. Now added a new field 'title'.
* (fixed): It wasn't possible to give a link a certain id and class. Now added new fields 'id' and 'class'.
* (fixed): It wasn't possible to turn off the error messages and use them as some kind of debug. Now added value '$footerBarShowErrors'.
* (fixed): It wasn't possible to change the hideShow behaviour. JS is now checking for className. CSS classes accessible via: '.footerBar.footerBarHide' and '.footerBarContent.footerBarContentHide'.
* (fixed): FooterBar wasn't displayed on SpecialPages, EditForms and Login. Hooks rearranged! Now using to create the Bar 'ArticlePageDataAfter' and 'SpecialPageBeforeExecute' and to show 'SkinAfterContent'. It also fixed the bug, that the JS was declared inline
* (fixed): The status of the bar wasn't saved during a session. Now using cookies via the jQuery#cookie. To check the cookie there is a new inline JS, but only to call a head function. ^^
* (fixed): It wasn't possible to create menus for the FooterBar. New field added 'menu'. The menu will be created at the position of the first match of a certain menuname.

* (open) : The 'self' link doesn't work proper, because of the getTitle-function.
* (open) : If no value of '$footerBarConcat' fits, then the FooterBar is empty.
* (open) : New function of menu creation needs much better CSS.
* (open) : Let users create menus too.
* (open) : Catch possible menu errors.
* (open) : Get rid of the global value that stores the HTML of the FooterBar.
* (open) : Restructure the JS functions.
 
ToDo
-----------
* Create a more readable source code with code reduction by using loops.
* Test the FooterBar on other browsers.
