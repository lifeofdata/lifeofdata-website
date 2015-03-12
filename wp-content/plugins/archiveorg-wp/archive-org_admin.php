<?php
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

if ($_POST['arch_hidden'] == 'Y') {
    //Form data sent
    $archive_width = mysql_escape_string($_POST['archive_width']);
    update_option('archive_width', $archive_width);
    $archive_height = mysql_escape_string($_POST['archive_height']);
    update_option('archive_height', $archive_height);
    ?>
    <div class="updated"><p><strong><?php echo('Options saved.'); ?></strong></p></div>
    <?php
} else {
    $archive_height = get_option('archive_height');
    $archive_width = get_option('archive_width');
}
?>

<div class="wrap">
    <?php echo "<h2>" . 'Archive Org Options' . "</h2>"; ?>

    <form name="arch_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <input type="hidden" name="arch_hidden" value="Y">
        <?php echo "<h4>" . 'Embed Default Width and Height Settings' . "</h4>"; ?>
        <p><?php echo("Width: "); ?><input type="text" name="archive_width" value="<?php echo $archive_width; ?>" size="8"></p>
        <p><?php echo("Height: "); ?><input type="text" name="archive_height" value="<?php echo $archive_height; ?>" size="8"></p>
        <p class="submit">
            <input type="submit" name="Submit" value="<?php echo('Update Options') ?>" />
        </p>
    </form>
    <hr />
    <h2>Donate</h2>
    <p>If you find this plugin useful, please <a style="color:red;" target="_blank" href="http://archive.org/donate/index.php">donate generously to Archive.org.</a></p>
    If there's any love left over, buy me a cup of coffee to sip while I listen to another great concert on Archive.org:<form style="display:inline;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="hosted_button_id" value="9GUAZYVD556FC">
        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif"  style="margin-bottom:-8px;" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
        <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    <p>Please abide by Archive.org <a target="_blank" href="http://archive.org/about/terms.php">Terms of Use</a></p>
    <p>&nbsp;</p>
    <hr />
    <h2>Documentation</h2>
    <h4>Usage:</h4>
    <p>Wordpress page and post authors can use the shortcode [archive-org ....] to embed any audio or video file or playlist offered on <a target="_blank" href="http://archive.org">Archive.org</a></p>
    <p>Place the shortcode <strong>[archive-org embed=<i>avtitle</i>]</strong> into any post or page. <i>avtitle</i> can be found on the Archive.org website inside the URL for a particular media (audio or video) selection
        as shown in the diagram below where the <i>avtitle</i> is <strong>VariousBannedAndCensoredCartoons.</strong></p>
    <p><img style="margin-left:50px;" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/archive-org/screenshot-1.jpg"/></p>
    <h4>Optional Parameters:</h4>
    <ul style="margin-left:50px;">
        <li><strong>width</strong> = width in pixels of the embedded video or audio player</li>
        <li><strong>height</strong> = width in pixels of the embedded video or audio player</li>
        <li><strong>playlist</strong> = true or false (defaults to false, if this parameter is omitted.) If the Archive.org item is a group of audio or video files, setting playlist=true will cause a dropdown list to be displayed, allowing the user to choose different audio or video files from the list.</li>
    </ul>
    <h4>Example:</h4>
    <p>An example using all the options might look like: <strong><pre>[archive-org embed=VariousBannedAndCensoredCartoons width=600 height=400 playlist=true]</pre></strong></p>
    <h4>Behavior</h4>
    <p>Audio playlists include the dropdown list within the vertical dimension you've specified. If you don't want a dropdown, set the height=30. However, if you desire the dropdown playlist, you should probably set height to at least 380 pixels, as Archive.org has a bug in audio playlists which make the scrollbar troublesome at smaller heights.</p>
    <p>If you set playlist=false, or omit the playlist parameter, you won't get the dropdown. However you can move to different playlist selections by using the track forward/backward buttons in the player. With playlist=false on audio files/playlists, it's best to set height=30.</p>
</div>
