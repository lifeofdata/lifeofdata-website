=== Storify Stories Slider ===
Contributors: tesial
Tags: storify, stories, slider, carousel, social, twitter, youtube, facebook, instagram, tumblr, embed
Requires at least: 2.7.0
Tested up to: 3.4.2
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Short code that allows you to easily add a slider displaying the last 20 stories/liked stories of a Storify user in a horizontal or vertical slider.

== Description ==

Integrate your **Storify Stories** into your pages, articles or template elements using this smart **short code slider**.

The Plugin parameters allow you to easily customize the Storify Stories Slider by:

 * choosing the Storify account
 * resizing the slider width and height
 * displaying a specific number of stories
 * choosing the slider orientation (vertical or horizontal)
 * displaying the account own stories or liked stories
... and more to come

The Storify Stories Slider plugin was conceived by Damien Van Achter [Lab.Davanac](http://www.davanac.me/), implemented by [Tesial](http://www.tesial.be) with the support of the [Storify](http://storify.com/)'s Team.

== Installation ==

1. Upload the short code plugin using the Plugin Manager built into WordPress
1. Activate the short code plugin through the 'Plugins' menu in WordPress
1. Use the short code plugin by adding it into a page, an article or wherever you want. See the **FAQ** for the available options.

== Frequently Asked Questions ==

= What is storify =

Storify is a way to tell stories using social media such as Tweets, photos and videos. You search multiple social networks from one place, and then drag individual elements into your story. You can re-order the elements and also add text to give context to your readers. See [Storify F.A.Q](http://storify.com/storifyfaq/frequently-asked-questions, "Storify F.A.Q.") for more informations.

= How many stories are displayed =

The stories (or liked stories) are displayed by pages of 20. When reaching the end of the page, the 20 next stories will be loaded.

= What are the available options =

There are two mandatory options. The omission of one of these parameters will prevent the plugin work:

  * **username**: the username from whom the stories will be gathered
 
Other optional options are:

 * **name**: the name of the widget, must be unique and contains only alphanumeric characters. Only usefull for post-processing using jQuery.
 * **width**: the width of the whole widget, by default, the tile of a story is 330x200
 * **height**: the height of the whole widget 
 * **entries**: the number of stories to show. The size of an entry will then directly depend on the size of the whole widget
 * **scroll**: the number of stories to scroll, this must not exceed the number of entries shown
 * **orientation**: the orientation of the widget, either *horizontal* or *vertical*
 * **type**: the type of stories to gather, either *stories* or *likes*
 * **sorting**: the order of the stories, either *asc* or *desc*. Default value is desc and shows last entry first
 
= Where and how do i add a short code =

You can add a short code pretty much anywere. Create a page and past this sample code:

**[storify-stories-slider username="your_storify_username_here"]**

Change the username and then go and check out the page to see the slider working. To add an option, just edit the short code you've created and add the option you want. Both option name and value must be surrounded by coma.
For instance the following short code will create a vertical slider which display 4 stories ordered by date (first is the oldest), scroll by one and have a size of 400x800:

**[storify-stories-slider username="your_storify_username_here" sorting="asc" entries="4" scroll="1" orientation="vertical" width="400" height="800" ]** 

== Screenshots ==

1. An example of two horizontal widget with various configuration. The actual example can be seen on [Tesial Labs](http://www.tesial.be/labs/storify-stories-slider-examples/).
2. An example of two vertical widget with various configuration. The actual example can be seen on [Tesial Labs](http://www.tesial.be/labs/storify-stories-slider-examples/).

== Changelog ==

= 1.1 =
* Added pagination support, get more than the 20th latest stories
* Added sorting order, either ascending on descending based on the dates
* More flexibility and robustness

= 1.0 =
* Initial version
* Gather Storify stories and organize them in a slider