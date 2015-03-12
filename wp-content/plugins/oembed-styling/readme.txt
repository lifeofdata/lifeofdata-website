=== oEmbed styling ===
Contributors: honza.skypala
Donate link: http://www.honza.info
Tags: oEmbed, YouTube, CSS, center, video, custom style, image
Requires at least: 2.9
Tested up to: 4.1
Stable tag: 1.1

This plugins wraps oEmbed generated HTML with divs that allow for styling them with CSS (style sheet of the theme).


== Description ==

Support for oEmbed, which was added to WordPress in version 2.9, is very nice. Unfortunately the generated oEmbed code is not easy to be centered in the text, which is a common request. This plug-in allows for centering (or even other styling), although it will not solve it by itself, just makes it possible.

This plugin wraps all oEmbed code into div tags with several classes set: 
<ul>
<li>oembed</li>
<li>oembed-mediatype (e.g. oembed-video)</li>
<li>oembed-server (e.g. oembed-youtube-com)</li>
<li>oembed-mediatype-server (e.g. oembed-video-youtube-com)</li>
</ul>
Then you can specify in your theme CSS (in the file style.css) the way you want the embeds to behave. E.g. by adding the following code to style.css:
<pre>
.oembed {
  text-align: center;
}
</pre>
you will make all oembeds (videos and pictures) to be centered; anyway, if you understand CSS, you are not limited by centering, but you can add much more (like frames around pictures etc.)

== Installation ==

1.	Upload the plugin into wp-content/plugins.
2.	Activate it in the plugin administration.
3.	Modify file style.css in your theme and add formatting for oembed classes

== Screenshots ==

1. The HTML code (provided by oEmbed) is wrapped in div tags (marked)

== Changelog ==

= 1.1 =
* converted to class
* small optimizations
* flushing oembed cache converted to dirty, but quick method
* license change
= 1.0 =
* Initial release.

== License ==

WTFPL License 2.0 applies

<code>           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
                   Version 2, December 2004

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

           DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
  TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

 0. You just DO WHAT THE FUCK YOU WANT TO.</code>