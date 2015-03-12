<?php

/*
  Plugin Name: Archive.org WP
  Plugin URI: http://nductiv.com
  Description: Embed video or audio selection or playlist from Archive.org
  Version: 1.0
  Author: Tony Asch
  Author URI: http://nductiv.com
 */

/*
  Archive.org WP (Wordpress Plugin)
  Copyright (C) 2012 Tony Asch
  Contact me at http://nductiv.com

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

//tell wordpress to register the demolistposts shortcode
add_shortcode("archive-org", "archiveorg_handler");

function archiveorg_handler($incoming) {
    $incoming = shortcode_atts(array(
        "embed" => "",
        "width" => get_option('archive_width'),
        "height" => get_option('archive_height'),
        "playlist" => ""
            ), $incoming);
    //run function that actually does the work of the plugin
    if ($incoming["playlist"] == "true")  {
        $playlist = "&playlist=1";
    } else {
        $playlist = "";
    }
    $embed_html = '<div class="arch_org_div"><iframe class="arch_org_iframe" src="http://archive.org/embed/' . strip_tags($incoming["embed"]) . $playlist . '" width="' . strip_tags($incoming["width"]) . '" height="' . strip_tags($incoming["height"]) . '" frameborder="0"></iframe></div>';
    return $embed_html;
}

function archiveorg_actions() {
    add_options_page("Archive Org", "Archive Org", 1, "Archive-Org", "arch_admin");
}

function arch_admin() {
    include('archive-org_admin.php');
}

add_action('admin_menu', 'archiveorg_actions');
?>
