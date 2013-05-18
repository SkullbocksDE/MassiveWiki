================================================================================================
						MediaWiki :: Extension :: AppendTalkpage
================================================================================================

	This code is an extension for MediaWiki ( > v.1.2).
	The extension 'AppendTalkpage' appends the talkpage of the current article at the really end of the article.
	The talkpage will be added even below the automatically added 'Categories', but still in the mainframe.
	You can customize it via the MediaWiki:Common.css without touching the css-file.
	There are also some variables, which can be set in the LocalSettings.php.
	It's possible to do some personalization (read further).
	
	Author:		Mike Knappe
	Version:	0.1
	Copyright:	© 2013 Mike Knappe
	License:	GNU General Public Licence 2.0 or later

========================================Install Notes===========================================

	To install the 'AppendTalkpage' extension, put the following line in LocalSettings.php:
		require_once( '$IP/extensions/AppendTalkpage/AppendTalkpage.php' );

========================================Personalization=========================================

	To control the table of content use this option. Avaible are 'no' and 'force' to hide or show:
		$appendTalkpageTOC;
		
	To control the place of the TOC use this option. Avaible are 'top' and 'bottom':
		$appendTalkpagePlaceTOC;
		
	To redesign the appended talkpage via the MediaWiki:Common.css use this classes:
		.
		
	To internationalize the extension for you language use the i18-file:
		AppendTalkpage.i18.php

===========================================History==============================================

	(fixed) Called multiple times by the hook 'ParseAfterTidy' and 'ParseBeforeTidy. Now using 'SkinAfterContent'.
	(fixed) First Version added Wiki-Text to the end of an article, which was not dynamic and not below the 'Categories'.
	(fixed) It was not possible to skin the talkpage insertion. Now using the new ResourceLoader.
	(fixed) The structure was bad. Now covering the space between and around the headlines by div-containers.

=============================================ToDo===============================================

	* Try to save the talkpage localy in the class. Therefore change the hook calls to register the calls.
	* Use i18 for i.e. headlines.
	* Parse the comments via headline to give them unique identifications such as css or links.
