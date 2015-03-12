<?php
/*
Plugin Name: WPDM - TinyMce Button
Plugin URI: http://www.wpdownloadmanager.com/
Description: TinyMCE Button add-on for WordPress Download Manager v4 and v2.7
Author: Shaon
Version: 2.3.1
Author URI: http://www.wpdownloadmanager.com/
*/

function wpdm_get_packages_list()
{
    global $wpdb;
    echo json_encode($wpdb->get_results("select id, title as label, id as value from {$wpdb->prefix}ahm_files where title like '%$_REQUEST[term]%' or description like '%$_REQUEST[term]%'"));
    die();
}

function wpdm_search_category()
{
    $categories = maybe_unserialize(get_option("_fm_categories"));
    foreach ($categories as $id => $category) {
        if (stripos("--" . $category['title'], $_REQUEST['term']))
            $cts[] = array('id' => $id, 'label' => $category['title'], 'value' => $id);
    }
    echo json_encode($cts);
    die();
}

function wpdm_init_tree()
{
    if (!isset($_GET['task']) || $_GET['task'] != 'wpdm_init_tree') return;
    global $wpdb;

    echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
    // All Cats
    $_POST['dir'] = !isset($_POST['dir']) || $_POST['dir'] == '/' ? null : $_POST['dir'];
    $cats = get_terms('wpdmcategory', array('hide_empty' => false, 'parent' => $_POST['dir']));

    foreach ($cats as $cat) {

            echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . $cat->term_id . "\">" . $cat->name . "</a></li>";
    }

    // All files

    $qparams = array('post_type'=>'wpdmpro', 'posts_per_page'=>9999);

    if ($_POST['dir'])
        $qparams['tax_query'] = array(array('taxonomy'=>'wpdmcategory','terms'=>array($_POST['dir']), 'include_children'=>false));

    $ndata = get_posts($qparams);


    $sap = '?'; //count($_GET)>0?'&':'?';

    foreach ($ndata as $data) {
        $html = '';

        //$link = "<a href='" . get_permalink($data->ID) . "' >".$data->post_title."</a>";
        $exts = function_exists('wpdm_package_filetypes')?wpdm_package_filetypes($data->ID, false):array();
        $ext = (count($exts)>1)?'zip':((count($exts)==0)?"":$exts[0]);
        $template = "<li class=\"wpdm_clink file ext_$ext\"><a href='#' rel='".$data->ID."' >".$data->post_title."</a></li>";
        $html .= $template;


        echo $html;


    }
    echo "</ul>";
    die();


}

add_action('init', 'wpdm_init_tree');

add_action('wp_ajax_wpdm_get_packages_list', 'wpdm_get_packages_list');
add_action('wp_ajax_wpdm_search_category', 'wpdm_search_category');

if (get_post_type() != 'wpdmpro') {
    add_filter('mce_external_plugins', "wpdm_tinyplugin_register");
    add_filter('mce_buttons', 'wpdm_tinyplugin_add_button', 0);
}
function wpdm_tinyplugin_add_button($buttons)
{
    array_push($buttons, "separator", "wpdm_tinyplugin");
    return $buttons;
}

function wpdm_tinyplugin_register($plugin_array)
{
    $url = plugins_url('/wpdm-tinymce-button/editor_plugin.js');

    $plugin_array['wpdm_tinyplugin'] = $url;
    return $plugin_array;
}

function wpdm_mce_tree()
{

    $siteurl = site_url();
    $data = <<<TREE
        
      
    <script language="JavaScript">
    <!--
      jQuery(document).ready( function() {
            jQuery('#tree').fileTree({                
                script: '{$siteurl}/?task=wpdm_init_tree',
                expandSpeed: 1000,
                collapseSpeed: 1000,
                multiFolder: false
            }, function(file) {
                //alert(file);
                //var sfilename = file.split('/');
                //var filename = sfilename[sfilename.length-1];
                //tb_show(jQuery(this).html(),'{$siteurl}/?download='+file+'&modal=1&width=600&height=400');
               var win = window.dialogArguments || opener || parent || top;
               var ltpl = jQuery('#plnk_tpl').val()!=""?' template="'+jQuery('#plnk_tpl').val()+'"':"";
               win.send_to_editor('[wpdm_package id=' + file + ltpl +']');
               tinyMCEPopup.close();
               return false;  
               //location.href=    file; // jQuery(this).attr('data-url');
            });
            
            
      });
    //-->
    </script>    
TREE;

    return $data;
}


function wpdm_tinymce()
{
    global $wpdb;
    if (!isset($_GET['wpdm_action']) || $_GET['wpdm_action'] != 'wpdm_tinymce_button') return false;
    ?>
    <html>
    <head>
    <meta http-equiv="Content-Type"
          content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>"/>
    <title>Download Manager &#187; Insert Package or Category</title>
    <link rel="stylesheet" href="<?php echo plugins_url('/download-manager/bootstrap/css/bootstrap.css'); ?>"/>
    <style type="text/css">
        .w3eden legend {
            font-size: 10pt;
        }

        .w3eden .nav a:active,
        .w3eden .nav a:hover,
        .w3eden .nav a {
            outline: none !important;
        }

        .w3eden button,
        .w3eden input[type=submit],
        .w3eden input[type=button],
        .w3eden input[type=text] {
            line-height: 26px;
            min-height: 26px;
            margin-bottom: 10px;

        }

        .w3eden .btn small {
            font-size: 65%;
        }

        #wpdmacats,
        #wpdmcats {
            height: 280px;
            overflow: hidden;
            border: 1px solid #eeeeee;
            border-radius: 4px;
            margin: 0px;
            padding: 10px;
        }

        #wpdmacats:hover,
        #wpdmcats:hover{
            overflow: auto;
        }

        #wpdmacats li label ,
        #wpdmcats li label {
            display: inline;
            font-size: 11px;
            font-weight: normal;
        }

        #wpdmacats li ,
        #wpdmcats li {
            list-style: none;
        }

        .nav-tabs li a {
            font-weight: 700;
            font-size: 9pt;
        }

    </style>
    <style>
    #wpdm-files_length {
        display: none;
    }

    #wpdm-files_filter {
        margin-bottom: 10px !important;
    }

    .adp-ui-state-highlight {
        width: 50px;
        height: 50px;
        background: #fff;
        float: left;
        padding: 4px;
        border: 1px solid #aaa;
    }

    #wpdm-files tbody .ui-sortable-helper {
        width: 100%;
        background: #444444;

    }

    #wpdm-files tbody .ui-sortable-helper td {
        color: #fff;
        vertical-align: middle;
    }

    input {
        padding: 4px 7px;
    }

    .dfile {
        background: #ffdfdf;
    }

    .cfile {
        cursor: move;
    }

    .cfile img, .dfile img {
        cursor: pointer;
    }

    .inside {
        padding: 10px !important;
    }

    #editorcontainer textarea {
        border: 0px;
        width: 99.9%;
    }

    #icon_uploadUploader, #file_uploadUploader {
        background: transparent url('<?php echo plugins_url(); ?>/download-manager/images/browse.png') left top no-repeat;
    }

    #icon_uploadUploader:hover, #file_uploadUploader:hover {
        background-position: left bottom;
    }

    .frm td {
        line-height: 30px;
        border-bottom: 1px solid #EEEEEE;
        padding: 5px;
        font-size: 9pt;
        font-family: Tahoma;
    }

    .fwpdmlock {
        background: #fff;
        border-bottom: 1px solid #eee;
    }

    .fwpdmlock td {
        border: 0px !important;
    }

    #filelist {
        margin-top: 10px;
    }

    #filelist .file {
        margin-top: 5px;
        padding: 0px 10px;
        color: #444;
        display: block;
        margin-bottom: 5px;
        font-weight: normal;
    }

    table.widefat {
        border-bottom: 0px;
    }

    .genpass {
        cursor: pointer;
    }

    h3,
    h3.handle {
        cursor: default !important;
    }


    #access {
        width: 250px;
    }



    .action #nxt {
        width: 100%;
        position: fixed;
        top: 0px;
        left: 0px;
        z-index: 999999;
    }

    #nxt a {
        font-weight: bold;
        color: #0C490C;
    }

    .action-float {
        position: fixed;
        top: -33px;
        left: 0px;
        width: 100%;
        z-index: 999999;
        text-align: right;
        background: rgba(0, 0, 0, 0.9);
    }

    .action .inside,
    .action-float .inside {
        margin: 0px;
    }

    .action-float #serr {
        width: 500px;
        float: left;
        margin: 4px;
        z-index: 999999;
        margin-top: -50px;
        border: 1px solid #800000;
    }

    .action-float #nxt {
        width: 500px;
        float: left;
        margin: 4px;
        z-index: 999999;
        margin-top: -40px;
        border: 1px solid #008000;
    }

    .wpdm-accordion div {
        padding: 10px;
    }

    .w3eden select{
        border-radius: 3px;
    }

    .wpdmlock {
        opacity: 0;
    }

    .wpdmlock + label {

        width: 16px;
        height: 16px;
        vertical-align: middle;
    }

    .wpdm-unchecked {
        display: inline-block;
        float: left;
        width: 21px;
        height: 21px;
        padding: 0px;
        margin: 0px;
        cursor: hand;
        padding: 3px;
        margin-top: -4px !important;
        background-image: url('<?php echo plugins_url('/download-manager/images/CheckBox.png'); ?>');
        background-position: -21px 0px;
    }

    .wpdm-checked {
        display: inline-block;
        float: left;
        width: 21px;
        height: 21px;
        padding: 0px;
        margin: 0px;
        cursor: hand;
        padding: 3px;
        margin-top: -4px !important;
        background-image: url('<?php echo plugins_url('/download-manager/images/CheckBox.png'); ?>');
        background-position: 0px 0px;
    }

    .cb-enable, .cb-disable, .cb-enable span, .cb-disable span {
        background: url(<?php echo plugins_url('/download-manager/images/switch.gif'); ?>) repeat-x;
        display: block;
        float: left;
    }

    .cb-enable span, .cb-disable span {
        line-height: 30px;
        display: block;
        background-repeat: no-repeat;
        font-weight: bold;
    }

    .cb-enable span {
        background-position: left -90px;
        padding: 0 10px;
    }

    .cb-disable span {
        background-position: right -180px;
        padding: 0 10px;
    }

    .cb-disable.selected {
        background-position: 0 -30px;
    }

    .cb-disable.selected span {
        background-position: right -210px;
        color: #fff;
    }

    .cb-enable.selected {
        background-position: 0 -60px;
    }

    .cb-enable.selected span {
        background-position: left -150px;
        color: #fff;
    }

    .switch label {
        cursor: pointer;
    }

    .switch input {
        display: none;
    }

    p.field.switch {
        margin: 0px;
        display: block;
        float: left;
    }

    .drag-drop-inside {
        text-align: center;
        padding: 10px;
        border: 2px dashed #ddd;
        margin: 10px 0px;
    }

    #wpdm-files li {
        list-style: none;
    }

    .w3eden select{
        padding: 5px;
    }

    .nav-tabs{ margin: 0 !important; }

    .tab-content{
        border: 1px solid #dddddd;
        border-top: 0;
        padding: 10px;
    }

    </style>
    <script type="text/javascript" src="<?php echo includes_url('/js/jquery/jquery.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo plugins_url('/download-manager/bootstrap/js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('/download-manager/js/jquery.form.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo includes_url('/js/tinymce/tiny_mce_popup.js'); ?>"></script>

    </head>
    <body class='w3eden' style="background: #fff;margin:10px;padding:10px;border-radius:4px">
    <div class="tabbable">
    <ul class="nav nav-tabs" style="margin-bottom: 20px">
        <li class="active"><a href="#pkg" data-toggle="tab">Insert Package</a></li>
        <li><a href="#ctg" data-toggle="tab">Insert Category</a></li>
        <li><a href="#osc" data-toggle="tab">Other Short-codes</a></li>
        <!-- li><a href="#qbtn" data-toggle="tab">Quick Add</a></li -->
    </ul>
    <div class="tab-content">
    <div id="pkg" class="tab-pane active">
        <select style="padding: 5px;margin-right: 5px;margin-bottom: 10px" id="plnk_tpl">
            <option value="link-template-default.php"><?php echo __('Link Template:', 'wpdmpro'); ?></option>
            <?php
            $ctpls = scandir(WPDM_BASE_DIR . '/templates/');
            array_shift($ctpls);
            array_shift($ctpls);
            $ptpls = $ctpls;
            foreach ($ctpls as $ctpl) {
                $tmpdata = file_get_contents(WPDM_BASE_DIR . '/templates/' . $ctpl);
                if (preg_match("/WPDM[\s]+Link[\s]+Template[\s]*:([^\-\->]+)/", $tmpdata, $matches)) {

                    ?>
                    <option
                        value="<?php echo str_replace(".php","",$ctpl); ?>" ><?php echo $matches[1]; ?></option>
                <?php
                }
            }
            if ($templates = unserialize(get_option("_fm_link_templates", true))) {
                foreach ($templates as $id => $template) {
                    ?>
                    <option
                        value="<?php echo $id; ?>" ><?php echo $template['title']; ?></option>
                <?php }
            } ?>
        </select><br/>
        <b>Select Package</b>
        <div id="tree" style="height: 400px;overflow: auto;border: 1px solid #eeeeee;padding-left: 10px"></div>
        <br>
        <!--<input type="text" class="input-small" id="wpdmfile" size="20" />
        <input type="submit" id="addtopost" class="btn" name="addtopost" value="Insert into post" />-->
    </div>

    <div id="ctg" class="tab-pane"><b>Select Categories</b><br><br>
        <!-- <input type="text" placeholder="Search Category" id="flc" class="input-large" style="width: 90%;" /> -->
        <ul id="wpdmcats">
            <?php
            //$currentAccesss = maybe_unserialize($file['category']);
            //if (!is_array($currentAccesss)) $currentAccesss = array();
            wpdm_cblist_categories();
            ?>
        </ul>
        <br/>

        <?php



        ?>
        <select style="padding: 5px;margin-right: 5px" id="lnk_tpl" onchange="jQuery('#lerr').remove();">
            <option value="link-template-default.php"><?php echo __('Link Template:', 'wpdmpro'); ?></option>
            <?php
            $ctpls = scandir(WPDM_BASE_DIR . '/templates/');
            array_shift($ctpls);
            array_shift($ctpls);
            $ptpls = $ctpls;
            foreach ($ctpls as $ctpl) {
                $tmpdata = file_get_contents(WPDM_BASE_DIR . '/templates/' . $ctpl);
                if (preg_match("/WPDM[\s]+Link[\s]+Template[\s]*:([^\-\->]+)/", $tmpdata, $matches)) {

                    ?>
                    <option
                        value="<?php echo str_replace(".php","",$ctpl); ?>" ><?php echo $matches[1]; ?></option>
                <?php
                }
            }
            if ($templates = unserialize(get_option("_fm_link_templates", true))) {
                foreach ($templates as $id => $template) {
                    ?>
                    <option
                        value="<?php echo $id; ?>" ><?php echo $template['title']; ?></option>
                <?php }
            } ?>
        </select>
        <select id="ipp" style="padding: 5px;margin-right: 5px">
            <option value="10">Items Per Page</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="8">7</option>
            <option value="9">7</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">18</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
        </select>
        <select id="cols" style="padding: 5px;margin-right: 5px">
            <option value="1">Columns</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        </select><br><br>
        <div class="row"><div class="col-sm-6">
        <label><input type="checkbox" value="1" id="ctitle"> Show Toolbar</label></div><div class="col-sm-6">
        <label><input type="checkbox" value="1" id="cdesc"> Show Category Description</label></div> </div>
        <br>

        <input type="submit" id="addtopostc" class="btn btn-primary" name="addtopost" value="Insert into post"/>
    </div>


    <div id="qbtn" class="tab-pane">
    <form action="admin-ajax.php" id="wpdm-pf">
    <input type="hidden" id="action" name="action" value="quick_add_package"/>
    <input type="hidden" id="act" name="act" value="_ap_wpdm"/>
    <input type="hidden" id="act" name="file[access][]" value="guest"/>
    <input type="hidden" id="act" name="file[page_template]" value="page-template-default.php"/>

    <div class="row-fluid">
        <b>Title:</b><br>
        <input type="text" class="span12" name="pack[title]"/><br>
        <b>Description:</b><br>
        <textarea cols="50" rows="3" class="span12" name="pack[description]"></textarea><br>

        <div style="width: 40%;float:left">
            <b>Download Link Label:</b><br>
            <input type="text" id="act" style="max-width: 100%;" name="pack[link_label]" value="Download"/>
        </div>
        <div style="width: 45%;float: left;margin-left:20px;">
            <b>Link Template:</b><br/>
            <select name="pack[template]" id="lnk_tpl" onchange="jQuery('#lerr').remove();">
                <?php
                $ctpls = scandir(WPDM_BASE_DIR . '/templates/');
                array_shift($ctpls);
                array_shift($ctpls);
                $ptpls = $ctpls;
                foreach ($ctpls as $ctpl) {
                    $tmpdata = file_get_contents(WPDM_BASE_DIR . '/templates/' . $ctpl);
                    if (preg_match("/WPDM[\s]+Link[\s]+Template[\s]*:([^\-\->]+)/", $tmpdata, $matches)) {

                        ?>
                        <option
                            value="<?php echo $ctpl; ?>"  ><?php echo $matches[1]; ?></option>
                    <?php
                    }
                }
                if ($templates = unserialize(get_option("_fm_link_templates", true))) {
                    foreach ($templates as $id => $template) {
                        ?>
                        <option
                            value="<?php echo $id; ?>" ><?php echo $template['title']; ?></option>
                    <?php }
                } ?>
            </select>
        </div>
        <div style="clear: both;"></div>
        <div>
            <ul id="wpdm-files"></ul>

        </div>
        <div class="postbox " id="upload_meta_box">
            <b><?php echo __('Upload file(s) from PC', 'wpdmpro'); ?></b>

            <div class="inside">


                <div id="plupload-upload-ui" class="hide-if-no-js">
                    <div id="drag-drop-area">
                        <div class="drag-drop-inside">
                            <p class="drag-drop-info"><?php _e('Drop files here'); ?></p>

                            <p><?php _ex('or', 'Uploader: Drop files here - or - Select Files'); ?></p>

                            <p class="drag-drop-buttons"><input id="plupload-browse-button" type="button"
                                                                value="<?php esc_attr_e('Select Files'); ?>"
                                                                class="btn btn-danger btn-sm"/></p>
                        </div>
                    </div>
                </div>



                <div id="filelist"></div>

                <div class="clear"></div>
            </div>
        </div>

        <input type="submit" class="btn btn-success" value="Insert into post"/>

        <div id="sving"
             style="float: right;margin-right:10px;padding-left: 20px;background:url('<?php echo admin_url('images/loading.gif'); ?>') left center no-repeat;display: none;">
            Please Wait...
        </div>
    </div>
    </form>
    </div>

    <div class="tab-pane" id="osc">
        <div class="panel panel-default">
            <div class="panel-heading"><b>All Packages Table</b></div>

   <div class="panel-body">
        <i>if you select one or more categories then short-code will show packaged from selected categories only, otherwise all packages</i>
        <ul id="wpdmacats" style="height: 160px !important;">
            <?php
            wpdm_cblist_categories('', 0);
            ?>
        </ul><Br/>
        <select id="iapp" style="padding: 3px">
            <option value="10">Items Per Page</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="8">7</option>
            <option value="9">7</option>
            <option value="10">10</option>
            <option value="11">11</option>
            <option value="12">12</option>
            <option value="13">13</option>
            <option value="14">14</option>
            <option value="15">15</option>
            <option value="16">16</option>
            <option value="17">17</option>
            <option value="18">18</option>
            <option value="19">18</option>
            <option value="20">20</option>
            <option value="21">21</option>
            <option value="22">22</option>
            <option value="23">23</option>
            <option value="24">24</option>
            <option value="25">25</option>
            <option value="26">26</option>
            <option value="27">27</option>
            <option value="28">28</option>
            <option value="29">29</option>
            <option value="30">30</option>
        </select>&nbsp;
    <button class="btn btn-primary btn-xs" id="ads">Insert to Post</button>
   </div></div>

        <div class="panel panel-default">
            <div class="panel-heading"><b>Package List</b></div>
            <div class="panel-body">

                <select style="margin-right: 5px;width: 160px" id="plnk_tpl_pl">
                    <option value="link-template-default.php"><?php echo __('Link Template:', 'wpdmpro'); ?></option>
                    <?php
                    $ctpls = scandir(WPDM_BASE_DIR . '/templates/');
                    array_shift($ctpls);
                    array_shift($ctpls);
                    $ptpls = $ctpls;
                    foreach ($ctpls as $ctpl) {
                        $tmpdata = file_get_contents(WPDM_BASE_DIR . '/templates/' . $ctpl);
                        if (preg_match("/WPDM[\s]+Link[\s]+Template[\s]*:([^\-\->]+)/", $tmpdata, $matches)) {

                            ?>
                            <option
                                value="<?php echo str_replace(".php","",$ctpl); ?>" ><?php echo $matches[1]; ?></option>
                        <?php
                        }
                    }
                    if ($templates = unserialize(get_option("_fm_link_templates", true))) {
                        foreach ($templates as $id => $template) {
                            ?>
                            <option
                                value="<?php echo $id; ?>" ><?php echo $template['title']; ?></option>
                        <?php }
                    } ?>
                </select>
                <select id="plob" style="margin-right: 5px">
                    <option value="post_title">Order By:</option>
                    <option value="post_title">Title</option>
                    <option value="download_count">Downloads</option>
                    <option value="package_size_b">Package Size</option>
                    <option value="view_count">Views</option>
                    <option value="date">Publish Date</option>
                    <option value="modified">Update Date</option>
                </select><select id="plobs" style="margin-right: 5px">
                    <option value="asc">Order:</option>
                    <option value="asc">Asc</option>
                    <option value="desc">Desc</option>
                </select>
                <select id="plpg">
                    <option value="asc">Paging:</option>
                    <option value="1">Show</option>
                    <option value="0">Hide</option>
                </select><br style="display: block;clear: both;margin-top: 5px"/>
                <button class="btn btn-primary btn-sm" id="plps">Insert to Post</button>
                <button class="btn btn-default btn-sm" id="plmd">Most Downloads</button>
                <button class="btn btn-default btn-sm" id="plmv">Most Viewed</button>
                <button class="btn btn-default btn-sm" id="plnp">New Packages</button>
                <script>
                    jQuery('#plps').click(function(){

                        var linkt = ' link_template="' + jQuery('#plnk_tpl_pl').val() + '" ';
                        var acob = ' order_by="' + jQuery('#plob').val() + '" order="' + jQuery('#plobs').val() + '"';
                        var paging = ' paging="' + jQuery('#plpg').val() + '"';
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor('[wpdm_packages' + linkt + acob + paging + ' items_per_page="10" title="" desc="" cols=1 colsphone=1 colspad=1]');
                        tinyMCEPopup.close();
                        return false;
                    });

                    jQuery('#plmd').click(function(){
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor('[wpdm_packages link_template="link-template-panel" order_by="download_count" order="desc" paging="0" items_per_page="10" cols=1 colsphone=1 colspad=1 title="Most Downloaded Packages" desc=""]');
                        tinyMCEPopup.close();
                        return false;
                    });

                    jQuery('#plmv').click(function(){
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor('[wpdm_packages link_template="link-template-panel" order_by="view_count" order="desc" paging="0" items_per_page="10" cols=1 colsphone=1 colspad=1 title="Most Viewed Packages" desc=""]');
                        tinyMCEPopup.close();
                        return false;
                    });

                    jQuery('#plnp').click(function(){
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor('[wpdm_packages link_template="link-template-panel" order_by="date" order="desc" paging="0" items_per_page="10" cols=1 colsphone=1 colspad=1 title="New Packages" desc=""]');
                        tinyMCEPopup.close();
                        return false;
                    });

                </script>
            </div>

        </div>
        <div class="panel panel-default">
            <div class="panel-heading"><b>Front-end UI</b></div>

   <div class="panel-body">

    <button class="btn btn-primary btn-sm" id="fui">( [wpdm_frontend] ) Insert to Post</button>
   </div></div>

        <?php do_action('wpdm_ext_shortcode'); ?>

    </div>

    </div>
    </div>


    <?php
    $treejs = plugins_url() . '/wpdm-tinymce-button/js/jqueryFileTree.js';
    $treecss = plugins_url() . '/wpdm-tinymce-button/css/jqueryFileTree.css';
    $siteurl = site_url();
    $data = <<<TREE
    <script language="JavaScript" src="{$treejs}"></script>     
    <link rel="stylesheet" href="{$treecss}" />
TREE;
    echo $data;
    echo wpdm_mce_tree();
    ?>
    <script type="text/javascript">
            var cats = '';
        jQuery('#ads').click(function(){
            var acts = '';
            jQuery('#wpdmacats input[type=checkbox]').each(function () {
                if (this.checked) acts += jQuery(this).val() + ",";
            });
            cats = acts!=''?'categories="' + acts + '" ':'';
            var win = window.dialogArguments || opener || parent || top;
            win.send_to_editor('[wpdm-all-packages ' + cats + ' items_per_page=' + jQuery('#iapp').val() + ']');
            tinyMCEPopup.close();
            return false;
        });

        jQuery('#fui').click(function () {
            var win = window.dialogArguments || opener || parent || top;
            win.send_to_editor('[wpdm_frontend]');
            tinyMCEPopup.close();
            return false;
        });

        jQuery('#addtopost').click(function () {
            var win = window.dialogArguments || opener || parent || top;
            var ltpl = jQuery('#plnk_tpl').val()!=""?' template='+jQuery('#plnk_tpl').val():"";
            win.send_to_editor('[wpdm_package id=' + jQuery('#wpdmfile').val() + ltpl + ']');
            tinyMCEPopup.close();
            return false;
        });
        jQuery('#addtopostc').click(function () {
            var cts = '';
            jQuery('#wpdmcats input[type=checkbox]').each(function () {

                if (this.checked) cts += jQuery(this).val() + ",";
            });
            var win = window.dialogArguments || opener || parent || top;
            if(cts=='') { alert('You must select one or more ctaegories!'); return false; }
            var ctitle = jQuery('#ctitle').prop('checked') ? 'toolbar=1' : 'toolbar=0';
            var cdesc = jQuery('#cdesc').prop('checked') ? 'desc=1' : '';
            win.send_to_editor('[wpdm_category id="' + cts + '" cols="' + jQuery('#cols').val() + '" ' + ctitle + ' ' + cdesc + ' item_per_page=' + jQuery('#ipp').val() + ' template="' + jQuery('#lnk_tpl').val() + '"]');
            tinyMCEPopup.close();
            return false;
        });
        jQuery('#addtoposth').click(function () {
            var win = window.dialogArguments || opener || parent || top;
            win.send_to_editor('[wpdm_direct_link id=' + jQuery('#pid4hl').val() + ' class="btn ' + jQuery('#color').val() + '" data_icon="' + jQuery('#icon').val() + '" link_label="' + jQuery('#hltitle').val() + '" link_slabel="' + jQuery('#hstitle').val() + '"]');
            tinyMCEPopup.close();
            return false;
        });

    </script>

    </body>
    </html>

    <?php

    //die();
}

function admin_tbcss(){
    ?>
<style>
    .wpdm-mce-ico{
        color: #3399ff !important;
    }
    .wpdm-mce-ico:hover,
    button:hover .wpdm-mce-ico{
        color: #3965ff !important;
    }
</style>
<?php
}


add_action('wp_loaded', 'wpdm_tinymce');
add_action('admin_head', 'admin_tbcss');

