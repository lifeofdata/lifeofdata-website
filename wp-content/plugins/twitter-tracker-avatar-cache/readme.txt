=== Twitter Tracker Avatar Cache ===
Contributors: simonwheatley, codeforthepeople, s1m0nd
Tags: cache, avatar, twitter tracker
Requires at least: 3.1.0
Tested up to: 3.3.2
Stable tag: 1.0

Caches Twitter avatars used by the Twitter Tracker widgets to avoid using the Twitter API, which sets cookies.

== Description ==

Caches Twitter avatars used by the [Twitter Tracker](http://wordpress.org/extend/plugins/twitter-tracker/) widgets to avoid using the Twitter API, which sets cookies. This is inline with new directives from the UK government to reduce unnecessary cookie usage, following European legislation on this subject.

In order to maintain site performance, the plugin does not fetch and cache the images during the rendering of the page, instead it spins off a separate operation using the built-in WordPress cache. One slight disadvantage of this approach is that the first time a new Twitter avatar is encountered it can take around a minute for it to be available.

If you want to avoid the use of cookies, and don't need avatars in your widget (or are hiding the avatars with CSS) then you can use the [Twitter Tracker Blank Avatars](http://wordpress.org/extend/plugins/twitter-tracker-blank-avatars/) partner plugin.

== Filters and actions ==

These notes mainly for developers:

`tt_max_cache_count` filter – Use this to influence the total number of avatars the plugin will cache at any one time. If you return false or zero (0) then the number of items is unlimited. Keep in mind that an unlimited cache will continue to grow forever as new avatars are required by a Twitter profile or search widget, so you'll need to be sure you've got the disk space if you choose this option (or simply manually clean it out periodically).

== Installation ==

The plugin is simple to install:

1. Download `twitter-tracker-avatar-cache.zip`
1. Unzip
1. Upload `twitter-tracker-avatar-cache` directory to your `/wp-content/plugins` directory
1. Go to the plugin management page and enable the plugin
1. Give yourself a pat on the back

== Change Log ==

= v1.0 =

* First release!

