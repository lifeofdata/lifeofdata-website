=== Plugin Name ===
Contributors: cgrymala
Tags: attachments, shortcode, list
Requires at least: 3.0
Tested up to: 3.5.1
Stable tag: 0.4a

Adds a shortcode to display a list of files attached to a post.

== Description ==

This WordPress plug-in allows users to easily list all of the attachments associated with a specific post or page within WordPress. For instance, if you want to create a long list of Word documents or PDF documents, you can simply attach them to the page or post, then insert the shortcode where you'd like that list to appear.

= Usage =

The simplest way to use this plug-in is just to use the shortcode. That will, by default, list the attachments with the most recent upload at the top of the list and the oldest upload at the end of the list. No heading will be included above the list. The list will be created as an unordered list with the CSS class of "attachment-list".

[list-attachments]

= Options =

* type - a comma-separated list of file extensions that should be included in the list. If this is left empty, all attachments will be included
orderby - any of the values that can be used with the WordPress query_posts() function
* order - indicate whether the list should be sorted in ascending or descending order
* groupby - if you would like the list split into specific groups, you can indicate any of the WordPress post object parameters as the value of this property. For instance, you can use a common "description" for your attachments to organize them into groups. In that case, you would use "post_content" as the "groupby" parameter for this shortcode
* before_list - any HTML code you want to appear before the list begins
opening - the opening tag(s) for the list (defaults to &lt;ul class="attachment-list"&gt;)
* closing - the closing tag(s) for the list (defaults to &lt;/ul&gt;)
* before_item - the opening tag(s) for each item of the list (defaults to &lt;li&gt;)
* after_item - the closing tag(s) for each item of the list (defaults to &lt;/li&gt;)

This is the first version of this plug-in, so I'm certain there are a lot of features that still need to be added and bugs that need to be worked out. If you try out the plug-in and have any suggestions or notice any issues, please comment on this post and let us know.

== Installation ==

= Automatic Installation =

The easiest way to install this plugin automatically from within your administration area.

1. Go to Plugins -&gt; Add New in your administration area, then search for the plugin "List Attachments Shortcode".
1. Click the "Install" button.
1. Go to the Plugins dashboard and "Activate" the plugin (for MultiSite users, you can safely "Network Activate" this plugin).

= Manual Installation =

If that doesn't work, or if you prefer to install it manually, you have two options.

**Upload the ZIP**

1. Download the ZIP file from the WordPress plugin repository.
1. Go to Plugins -&gt; Add New -&gt; Upload in your administration area.
1. Click the "Browse" (or "Choose File") button and find the ZIP file you downloaded.
1. Click the "Upload" button.
1. Go to the Plugins dashboard and "Activate" the plugin (for MultiSite users, you can safely "Network Activate" this plugin).

**FTP Installation**

1. Download the ZIP file from the WordPress plugin repository.
1. Unzip the file somewhere on your harddrive.
1. FTP into your Web server and navigate to the /wp-content/plugins directory.
1. Upload the listAttachments folder and all of its contents into your plugins directory.
1. Go to the Plugins dashboard and "Activate" the plugin (for MultiSite users, you can safely "Network Activate" this plugin).

= Must-Use Installation =

If you would like to **force** this plugin to be active (generally only useful for Multi Site installations) without an option to deactivate it, you can upload the listAttachments.php to your /wp-content/mu-plugins folder. If that folder does not exist, you can safely create it. Make sure **not** to upload the listAttachments *folder* into your mu-plugins directory, as "Must Use" plugins must reside in the root mu-plugins directory in order to work.

== Known Issues ==

* The HTML code arguments (before_list, after_list, before_item, after_item, etc.) only work when added through the visual editor. When added through the HTML editor, they are not sent properly through the shortcode.

== To Do ==

* Re-do the method of handling the HTML code arguments
* Remove the handful of hard-coded HTML elements that are in the plug-in, making them configurable through shortcode arguments
* Add a widget to the plugin

== Changelog ==

= 0.4.1a =
* Verified compatibility with WordPress 3.5.1
* Allow uppercase/mixed-case usage of order property (previously, the plugin ignored the property if it wasn't lowercase)

= 0.4a =
* Fixed the usage of the "orderby" argument. Was previously only working with the "groupby" argument set. Now works as it should.

= 0.3a =
* Added option to display the size of each attached file
* Fixed the usage of the 'type' argument
* Adjusted the way the HTML code arguments are handled
* Added the plugin to the WordPress plugin repository

= 0.2a =
* Fixed the usage of links to each attachment
* Prepared the plugin for public release in the WordPress repository

= 0.1a =
* First version of the plugin
