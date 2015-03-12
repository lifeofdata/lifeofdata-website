  
<?php 
wp_enqueue_script('jquery');
/*if($file[preview]!='')
$file['thumb'] = "<img src='".plugins_url().'/download-manager/timthumb.php?w='.get_option('_wpdm_pthumb_w').'&h='.get_option('_wpdm_pthumb_h').'&zc=1&src='.$file[preview]."'/>";
else
$file['thumb'] = "";
//$file['thumb'] = $thumb;
$file['files'] = maybe_unserialize($file[files]);     
$file['file_count'] = count($file['files']);
$fhtml = "<ul class='wpdm-filelist'>";
$idvdl = get_wpdm_meta($file['id'],'individual_download');
 
foreach($file['files'] as $ind=>$sfile){    
    $sfile = preg_replace("/([0-9]+)_/","",$sfile);
    if($idvdl==1)   
    $fhtml .= "<li><a rel='noindex nofollow' href='".wpdm_download_url($file)."&ind=".$ind."' class='ind-download'>$sfile</a></li>";
    else
    $fhtml .= "<li>$sfile</li>";
}
$fhtml .= "</ul>";

$file['file_list'] =  $fhtml;
$file['description'] = stripcslashes($file['description']);
$file['page_template'] = stripcslashes($file['page_template']);
$k = 1;
$file['additional_previews'] = get_wpdm_meta($file[id],'more_previews');         
$img = "<img id='more_previews_{$k}' title='' class='more_previews' src='".plugins_url()."/download-manager/timthumb.php?w=575&h=170&zc=1&src={$file[preview]}'/>\n";
$tmb = "<a href='#more_previews_{$k}' class='spt'><img title='' src='".plugins_url()."/download-manager/timthumb.php?w=100&h=45&zc=1&src={$file[preview]}'/></a>\n";
if($file['additional_previews']){
    foreach($file['additional_previews'] as $p){
        ++$k;
        $img .= "<img style='display:none;position:absolute' id='more_previews_{$k}' class='more_previews' title='' src='".plugins_url().'/download-manager/timthumb.php?w=575&h=170&zc=1&src=wp-content/plugins/download-manager/preview/'.$p."'/>\n";
        $tmb .= "<a href='#more_previews_{$k}' class='spt'><img id='more_previews_{$k}' title='' src='".plugins_url().'/download-manager/timthumb.php?w=100&h=45&zc=1&src=wp-content/plugins/download-manager/preview/'.$p."'/></a>\n";
    }}
$file['slider-previews'] = "<div class='slider' style='height:180px;'>".$img."</div><div class='tmbs'>$tmb</div>";
  */
echo FetchTemplate($file['page_template'],$file,'popup');

?>
<br>
<div style="clear: both;"></div><br>
<br>

  
<?php die(); ?>