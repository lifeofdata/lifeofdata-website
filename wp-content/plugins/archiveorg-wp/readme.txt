=== Archive.org WP ===
Contributors: nductiv
Donate link: http://nductiv.com/
Tags: Archive.Org, embed video, embed audio
Requires at least: 3.0
Tested up to: 3.4
Stable tag: /trunk/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Archive.org offers free streaming for thousands of moving image and audio playlists.
This plugin offers a shortcode to insert these into pages/posts.

== Description ==

Archive.org offers free streaming of thousands of moving image selections and even
more audio materials. The archive-org plugin offers a simple shortcode syntax for
embedded video, audio, and playlists into Wordpress post/pages.

Usage:

Place the shortcode [archive-org embed=embedID] into any post or page. embedID can be found on the Archive.org website at the tail end of the URL for a video or audio item or playlist. The plugin automatically recognizes playlists.

Optional Parameters:

    width = width in pixels of the embedded player
    height = height in pixels of the embedded player
    playlist = true/false - determines whether a dropdown list of all the files is displayed

Example:

An example using all the options might look like:

    [archive-org embed=VariousBannedAndCensoredCartoons width=640 height=480 playlist=true]

Audio playlists include the dropdown list within the vertical dimension you've specified. If you don't want a dropdown, set the height=30. However, if you desire the dropdown playlist, you should probably set height to at least 380 pixels, as Archive.org has a bug in audio playlists which make the scrollbar troublesome at smaller heights.

If you set playlist=false, or omit the playlist parameter, you won't get the dropdown. However you can move to different playlist selections by using the track forward/backward buttons in the player. With playlist=false on audio files/playlists, it's best to set height=30.

== Installation ==

1. Use the standard Wordpress Installer to upload and unzip the plugin
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= What usage does Archive.org allow for their materials? =
Please abide by Archive.org Terms of Use: http://archive.org/about/terms.php

== Screenshots ==

1. How to find the embed ID

== Changelog ==

= 1.0 =
* Initial Release.

== Upgrade Notice ==

= 1.0 =
Initial Release.
