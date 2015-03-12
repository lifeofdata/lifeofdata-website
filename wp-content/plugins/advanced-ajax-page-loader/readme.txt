=== Advanced AJAX Page Loader ===
Contributors: deano1987, snumb130
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=MPJDRVYM87ZR4
Version: 2.6.8
Tags: ajax, posts, pages, page, post, loading, loader, no refresh, dynamic, jquery
Requires at least: 2.0?
Tested up to: 3.8.1
Stable tag: 2.6.8

AJAX Page Loader will load posts, pages, etc. without reloading entire page.



== Description ==
Description:
AJAX Page Loader will load posts, pages, etc. without reloading entire page. It will also update the URL bar with the url the user would have been going to without AJAX, this means the user can copy the url or bookmark it and return to the page they were visiting! This plugin will also add the page to there history for even more usability!

If this has helped you and saved you time please consider a donation via PayPal to:

dt_8792@yahoo.co.uk

Many thanks to Luke Howell, author of the original plugin which motivated me to re-write it and extend it to what it is today.



== Changelog ==

= 2.6.8 =
* Updated compatibility to latest wordpress.
* This version is GPL'd, a new PAID version will be released soon!

= 2.6.6 =
* Updated compatibility to latest wordpress.
* More loaders added!

= 2.6.5 =
* Updated compatibility to latest wordpress.

= 2.6.4 =
* Automatic "commercial" checking URL fix

= 2.6.3 =
* Automatic "commercial" checking to make stuff easier
* Added smooth scroll to top, thanks to Bram Perry

= 2.6.2 =
* Small php fixes for compatibility... oops (removed shorthand <?
* Clarification on commercial checkbox....

= 2.6.1 =
* People seem to be getting confused with debug mode, so modified messages and added info to the FAQ.
* Now using json_encode when loading the error html and etc.
* Search form checking had some problems, seems I merged an old code with a new one - dumbass I know.
* Some new rules for commercial users and removing adverts/donation.

= 2.6.0 =
* sorry for bad releases!
* fixed "no jquery" checker
* Updated admin panel so its cleaner.
* You can now toggle the "scroll to top" feature

= 2.5.17 =
* updated jquery version check/include.

= 2.5.16 =
* whoops...

= 2.5.15 =
* whoops...

= 2.5.14 =
* jQuery update warning now only appears if you have the debug enabled.
* changes to the initJQuery() function.
* try - catch added around reload codes to avoid error reports when it is in fact reload code. (put AAPL in debug to see errors).

= 2.5.13 =
* Added compatibility tag for latest wordpress

= 2.5.12 =
* Slight change to the default click code.
* Fixed a small layout hickup for reload_code.js - not causing any problems but nice to have things layout good :)
* Changed tested tag to the latest wordpress version - yes it works fine :)
* More loading images added :D

= 2.5.11 =
* I fell for it... accidently used $ instead of jQuery for reference. Fixed!

= 2.5.10 =
* title tag code has been updated and should now fix the &amp; issues! (Needs Testing).
* [eag] - Thanks to Juanjo who has contributed a function for manipulating returned data before AJAX drops it onto the page.

= 2.5.9 =
* replaced all get_settings() to get_option() - get_settings() is now deprecated since wordpress 2.1 and if you have debugging enabled it breaks this script. 

= 2.5.8 =
* Reverted the .on() back to .click() - some people reported problems where their other plguins were calling jQuery 1.5 and AAPL was failing because it needed 1.7 minimum, now 1.5 will work again.

= 2.5.7 =
* Reversed the changelog and upgrade notice list so that the latest changes are at the top (thanks for the tip christianebuddy).
* 'fixed' a short code <? to the correct <?php.
* scripts now use jQuery instead of $ - should fix lots of compatibility problems, also we now try and call jQuery the RIGHT way :)
* the "check for jQuery" option is now not required by default, and should be disabled unless it breaks your site.
* You can now add code to a special section called "click code", useful for menu / link changing.
* Added example codes to the resplace.net website, these should be useful for people :)

= 2.5.6 =
* Sorry for rapid development releases...
* Better integration with search box's, now you can set a "class", it is recommended to setup a "class" to search forms so (a) you can have multiple on the page, (b) more debugging availiable.
* Google analytics support has now been added, so when someone AJAX's to another tab, you can track it in analytics!!!
* extra information has been added to the admin panel to explain stuff.
* search binding by ID is still supported for backwards compatibility and quick integration for atleast the main theme.
* caching has been turned off for ajax requests, in the future though I will make an admin option for this...
* debug mode now checks for jQuery version and reports if you are not in the 1.7 range. (hope 1.8 doesnt get released now ;)

= 2.5.5 =
* IE7 / IE8 issue resolved and was caused by jQuery : http://dean.resplace.net/blog/2012/04/ajax-loaded-content-not-visible-in-ie7-ie8-with-jquery/
* Now using .on() to attatch click events.
* Page titles are now not so messed up - but are still a problem :s
* Leaving any field plank in the admin panel should now cause the default settings to load (useful when upgrading and there are new options).
* Added some new loading animations (using WordPress logos).

= 2.5.4 =
* Plugin is disabled for IE7 and IE8 due to the peakaboo rendering bug in these versions.

= 2.5.3 =
* admin panel work, MORE FEATURES!!! :D
* - You can now set the loading HTML code.
* - You can also set the loading error HTML code.
* - The href ignore has been extended and can now be changed in the admin panel.

= 2.5.2 =
* Fix for back button.

= 2.5.1 =
* Fix for reload code.

= 2.5.0 =
* reverted AAPLhome variable as suggested by Brandon Nourse.
* admin panel added for various options.
* added ability to change the target id for changing content.
* added ability to change/upload the loading image (image will now be kept on updates).
* added ability to set reload code in admin panel, this will be kept upon upgrading.
* Enable Javascript debug and jQuery check from the admin panel.
* You can optionally enable a footer link to link to the project site!
* Various sample loaders included so you can choose one to suit you.

= 2.4.8 =
* # links fix.
* Improvements to loading.gif handling, dont need to provide dimensions anymore :)
* Improvements to loading.gif handling, image is now pre-loaded and kept in memory :)
* plugin file path code improved, now using plugins_url instead of hard coded paths!
* Suffusion menu bar changer included (need to uncomment it to use it)
* loading is more likely to be centered now - yup

= 2.4.7 =
* HTML Special characters in the page <title> now display correctly (I hope).
* Anchor links (hash (#) links) are now ignored by the ajax process.
* I think I#m now correctly tagging my releases, hopefully!

= 2.4.6 =
* Page title doesnt show html special character encoding anymore.

= 2.4.5 =
* Fixed back button again and again and again and again (sorry my bad)

= 2.4.0 =
* onpopstate fixed, sometimes clicking back on the browser would not work... Now it should!
* The bindings to the search form were pretty poor (original authors code), I have re-written this and it should now work much better, still needs a little improving though.
* Ajax requesting code completely re-written to use jQuery's library, this should offer better compatibility between browsers, makes the code neater and offers more options such as caching.
* Ajax requests are not cached, and error catching is more reliable (you dont see it randomly when the page is in fact loading correctly).
* New 'warnings' system implemented to give you debug if you set 'showWarnings' to 'true' in the .js file... This could help us BOTH ;)

= 2.3.0 =
* Load current menu item (thanks to euphoriuhh).
* nivoslider example reload code added.
* IE fix for browser history (thanks to euphoriuhh).
* Now sets page title when you change page.

= 2.2.2 =
* Removed link-back as it is against wordpress TOC

= 2.2.1 =
* Fixed small problems checking if jQuery is called.

= 2.2.0 =
* Some workaround code so things like jscrollpane can work properly.

= 2.1.0 =
* jQuery check to make sure it has not already been included
* jQuery var in advanced-ajax-page-loader.php, set this to false to stop jQuery being included altogether.
* When content is loaded you can optionally call document.ready, change the ready variable in javascript file.

= 2.0.1 =
* Yup some fixes for the readme.

= 2.0.0 =
* First release by Dean williams with a huge improvement...
* Using jQuery more than pure javascript to help compatibility and code layout.
* Updated jquery to the latest 1.7 release.
* fade transitions used when ajax is loading the page.
* If a page fails to load it shows on the page (no ugly message box's)
* When a page is loaded the URL bar is updated on the browser for easy copying or bookmarking of links.
* When a page is loaded the browsers history is updated so that the user can go back/forward between pages.
* Easier to edit the used id for content area, some themes differ on this one so it's useful.

= 1.0.0 =
* First release by Luke Howell.














== Upgrade Notice ==

= 2.6.8 =
This version is GPL'd, a new PAID version will be released soon!

= 2.6.3 =
Options panel changes and new scroll method that is smooth!

= 2.6.1 =
Some small updates - fixes for the search form again - good idea to update!

= 2.6.0 =
new features for you guys :)

= 2.5.17 =
jquery 1.8.1 implemented.

= 2.5.15 =
last release was broke.

= 2.5.14 =
jquery warning no longer appears unless you have debug enabled!

= 2.5.13 =
if you have 2.5.12 - no immediate need to update as this is only a tag update for latest wordpress.

= 2.5.12 =
Very small - minor fixes, upgrading would be nice :) So would a donation?

= 2.5.11 =
I fell for it... accidently used $ instead of jQuery for reference. Fixed!

= 2.5.10 =
Title encoding fix and new "data code" feature.

= 2.5.9 =
Fixed a problem with the script breaking if you have wordpress debug enabled (perhaps in other cases too) - recommended to update, especially if the plugin will not work currently.

= 2.5.7 =
Major jQuery change in this release, change all '&' to 'jQuery' in your reload code AND please try turning off the "check for jquery" in the options. If it breaks turn it on, if it is still broken please ocntact me ASAP.

= 2.5.6 =
Sorry for the rapid development, this release brings google analytics support and better search box integration!

= 2.5.5 =
IE7/8 bug now fixed and plugin works again in these browsers, also people having trouble with errors after updating should not anymore.

= 2.5.4 =
Plugin is disabled for IE7 and IE8 due to the peakaboo rendering bug in these versions.

= 2.5.3 =
More editable features in the admin panel.

= 2.5.2 =
omg more back button fixes!

= 2.5.1 =
small fix for reload code (well more of a confusion fix).

= 2.5.0 =
majour changes, <b>MAKE SURE YOU BACKUP YOUR PLUGIN BEFORE UPGRADE JUST INCASE!</b> There is now an admin panel for settings (settings will be retained on update!).

= 2.4.8 =
fixes for # links and general plugin improvements!

= 2.4.7 =
Page titles will display better, and if you have any links with an anchor reference (# hash) they will be ignored.

= 2.4.6 =
Fixed back button again and again and again and again (sorry my bad)

= 2.4.0 =
This version brings better cross-browser compatiblity, includes better error checking and fixes some annoying bugs.

= 2.3.0 = 
This version fix's IE issues and updates the page title. dont forget to backup any custom inserted code in the JS file before updating.



== Installation ==

1. Upload `advanced-ajax-page-loader` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Goto "Settings" > "Advanced Ajax Page Loader" and review the settings, click save.
3. make sure your theme has the content area wrapped in a tag such as a DIV with an id attribute called "content". (data within this div will be replaced with AJAX content). SEE THEME GUIDE IN FAQ.

== Frequently Asked Questions ==

Q: The plugin isn't working right,  HHEEEELLLPPPP!!!!!!!!

A: You may need to use the Theme Support Guide in order to use AJAX Page Loader with your custom theme (SEE BELOW). But try turning on "Debug Messages" in the admin settings first, report any messages to the wordpress forums.

Q: I have content that usually uses JavaScript but now that is not working when loaded with ajax.

A: Unfortunately any inline javascript in the HTML being loaded into the page is ignored by your browser, 
also any bindings for lightbox for example need to be reloaded when new HTML is put into the page. 
You need to take the code used to bind the javascript to your elements and re-call them after the content
changes... Put the binding code in the JS file after the line which says "DROP YOUR RELOAD CODES BELOW HERE"

I have included a few example codes for nivoslider and jScrollPane, if you have other code please let me know and I can add it to the list.

http://software.resplace.net/WordPress/AjaxPageLoader.php

----Theme Support----

This edit may be required by some users with certain themes that cause AJAX Page Loader to reload the sidebar along with the content.

1. Open your theme's index.php file.
2.  find the "div" tag that contains the following inside a php tag: " if (have_posts()) : while (have_posts()) : the_post(); " . 
3. Give this "div" tag a unique ID. (Default: div id='content')
4. Check the AAPL settings page on your WordPress admin panel and make sure the content DIV id match, if you used 'content' this should be the default shown.

If you theme's search function stops working or causes the page to reload, then you'll need to edit index.php (and any other pages the search box appears) and give the <form> a class (default: class='searchform'). Make sure "Search form Class" matches this for AAPL to find it.

----Debugger----

Some people have been concerned by the debugger, this has been cleared up a little with some extra information and a heading which I will explain below:

Information - Anything with this is informative only, definately nothing to worry about.
Warning - Anything with this is a warning, it may affect functionality but it may be working exactly as planned, for instance if your wordpress doesnt have a search form, this will show to say AAPL could not find it. It's fine.
Error - Anything with this is an error, and should be diagnosed ASAP - it WILL affect the functionality of AAPL.

Please switch off debug on a live site else everyone will be getting these messages. Only use this if you have a problem and wish to seek help on the forums.


== Donations ==

I have got to give a very special thanks to the people mentioned below who have offered their support with donations, let me also take the time to thank others who report problems, offer solutions and help debug the plugin. I really appreciate the excellent support and feedback :).

* Travis Avery (travisavery)
* AlohaThemes (http://alohathemes.com/)

Please send your donations to:

dt_8792@yahoo.co.uk