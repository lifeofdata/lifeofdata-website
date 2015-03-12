=== Auto-Close Comments, Pingbacks and Trackbacks ===
Tags: autoclose, comments, pingback, trackback, spam, anti-spam
Contributors: Ajay
Donate link: http://ajaydsouza.com/donate/
Stable tag: trunk
Requires at least: 3.0
Tested up to: 4.0
License: GPL v2 or later

Close comments, pingbacks and trackbacks on your posts automatically at intervals set by you

== Description ==

Spammers target old posts in a hope that you won't notice the comments on them. Why not stop them in their tracks by just shutting off comments and pingbacks? Auto-Close let's you automatically close comments, pingbacks and trackbacks on your posts, pages and custom post types.

You can also choose to keep comments / pingbacks / trackbacks open on certain posts, page or custom post types. Just enter a comma-separated list of post IDs in the Settings page.

An extra feature is the ability to delete post revisions that were introduced in WordPress v2.6 onwards.


= Key features =

* **Close comments**: Automatically close comments on posts, pages, attachments and even Custom Post Types!
* **Close pingbacks and trackbacks**: Automatically close pingbacks and trackbacks as well
* **Choose how old**: Choose a custom time period as to when the comments, pingbacks and trackbacks need to be closed
* **Scheduling**: You can also schedule a cron job to automatically close comments, pingbacks and trackbacks daily
* **Bonus**: Delete post revisions


== Installation ==

= WordPress install =
1. Navigate to Plugins within your WordPress Admin Area

2. Click "Add new" and in the search box enter "autoclose"

3. Find the plugin in the list (usually the first result) and click "Install Now"

= Manual install =
1. Download the plugin

2. Extract the contents of autoclose.zip to wp-content/plugins/ folder. You should get a folder called autoclose.

3. Activate the Plugin in WP-Admin.

4. Goto **Settings &raquo; Auto-Close** to configure


== Upgrade Notice ==

= 1.5 =
* Custom post type support, Modified Language initialisation;
Check the ChangeLog for details


== Changelog ==

= 1.5 =
* New: Custom post type support. Now close comments on posts, pages, attachments and your custom post types!
* Modified: Language initialisation
* Modified: More code cleaning
* New: Spanish and Serbian languages thanks to <a href="http://firstsiteguide.com/">Ogi Djuraskovic</a>

= 1.4 =
* New: Responsive admin interface
* New: Plugin is now ready to be translated
* Modified: Massive code rewrite and cleanup

= 1.3.1 =
* New: Now separately choose to close on posts and pages. Also added buttons to open all comments and open all pings

= 1.2 =
* New: Option to delete post revisions. Minor bug fix. Includes plugin uninstaller.

= 1.1 =
* New: Option to keep comments / pingbacks / trackbacks on certain posts open

= 1.0 =
* Release


== Screenshots ==

1. Autoclose options in WP-Admin


== Frequently Asked Questions ==

If your question isn't listed here, please post a comment at the <a href="http://wordpress.org/support/plugin/autoclose">WordPress.org support forum</a>. I monitor the forums on an ongoing basis. If you're looking for more advanced support, please see <a href="http://ajaydsouza.com/support/">details here</a>.

= What does "Delete Post Revisions" do?  =

The WordPress revisions system stores a record of each saved draft or published update. This can gather up a lot of overhead in the long run. Use this option to delete old post revisions.

If you enable this option and turn on the cron job then any new revisions will be automatically deleted on a daily basis.
