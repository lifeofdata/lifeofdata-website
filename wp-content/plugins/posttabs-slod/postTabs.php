<?php
/*
Plugin Name: Post Tabs - SLOD
Plugin URI: http://xxx.post-tabs.hacklab.com.br
Description: postTabs allows you to easily split your post/page content into Tabs that will be shown to your visitors
x-Author: Leo Germani, Rodrigo Primo
Author: Dave Mee (based on work by Leo Germani, Rodrigo Primo)
Version: SLOD-2.10.3
Original x-Author URI: http://xxx.hacklab.com.br

    PostTabs - SLOD is released under the GNU General Public License (GPL)
    http://www.gnu.org/licenses/gpl.txt

    
*/

//////////////////////////////////////////////////////////

function postTabs_init(){
	if(!get_option("postTabs")){

		# Load default options
		$options["active_font"] = "#000000";
		$options["active_bg"] = "#fff";
		$options["inactive_font"] = "#666";
		$options["inactive_bg"] = "#f3f3f3";
		$options["over_font"] = "#666";
		$options["over_bg"] = "#fff";
		$options["line"] = "#ccc";
		$options["align"] = "left";
		$options["list_link"] = "hideshow";
		$options["single_link"] = "hideshow";
		$options["show_perma"] = "never";
		$options["TOC"] = "0";
		$options["cookies"] = "1";
		update_option("postTabs", $options);
	}

}


///////////////////// PLUGIN PATH ////////////////////////////
// required for Windows & XAMPP
$myabspath = str_replace("\\","/",ABSPATH);  
define('WINABSPATH', $myabspath);
// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', WINABSPATH . 'wp-content' );
	
define('POSTTABS_FOLDER', plugin_basename( dirname(__FILE__)) );
define('POSTTABS_ABSPATH', WP_CONTENT_DIR.'/plugins/'.plugin_basename( dirname(__FILE__)).'/' );
define('POSTTABS_URLPATH', WP_CONTENT_URL.'/plugins/'.plugin_basename( dirname(__FILE__)).'/' );

//////////////////////////////////////////////////////////////


function postTabs_filter($a){
	
	$b = "[tab:";
	$c = 0;
	$op = '';
	
	#Search for tabs inside the post
	if(is_int(strpos($a, $b, $c))){
		$options = get_option("postTabs");
		global $user_ID;	
		
		# What kind of link should be used?
		if(is_single() || is_page()){
			$linktype = $options["single_link"];
		}else{
			$linktype = $options["list_link"];
		}
		
		$vai = true;
		$results_i = array();
		$results_f = array();
		$results_t = array();
		$post = get_the_ID();

		wp_enqueue_script('postTabs', POSTTABS_URLPATH . 'postTabs.js', array('jquery'));
		wp_localize_script('postTabs', 'postTabs', array('use_cookie' => ($options["cookies"] && !isset($_GET['postTabs'])), 'post_ID' => $post));

		#find the begining, the end and the title fo the tabs
		while ($vai)  {	
			$r = strpos($a, $b, $c);
			if (is_int($r)){
				array_push($results_i, $r);
				$c=$r+1;
				$f = strpos($a, "]", $c);
				if($f){
					array_push($results_f, $f);
					array_push($results_t, substr($a, $r+5, $f-($r+5)));
				}	
			}else $vai = false;		
		};

		#If there is text before the first tab, print it
		if ($results_i[0] > 0) {
			$op .= substr($a, 0, $results_i[0]);
		}
		
		$currentTab = 0;
		if( get_query_var('postTabs') ) {
			$currentTab = urldecode( substr(get_query_var('postTabs'),1) );
			// is this a string?
			if( !is_int( urldecode( substr(get_query_var('postTabs'),1) ) ) ) {
				$currentTab = array_search( $currentTab , $results_t );
			}
		} else {
			$currentTab = 0;
		}

		#Print the list of tabs only when we are not in RSS feed
		if(!is_feed()){
			
			#Print the tabs links
			$op .= "<ul id='postTabs_ul_$post' class='postTabs' style='display:none'>\n";
			
			for ($x = 0; $x < sizeof($results_t); $x++){
				if($results_t[$x]!="END"){
					$op .= "<li id='postTabs_li_".$x."_$post' ";

					if ($x == $currentTab) {
						$op .= "class='postTabs_curr'";
					}		
							
					$link = ($linktype=="permalink") ? "href='" . get_postTabs_permalink($results_t[$x]) ."'" : " class='postTabsLinks'";		

					$op .= "><a  id=\"" . $post . "_$x\" onMouseOver=\"posTabsShowLinks('".$results_t[$x]."'); return true;\"  onMouseOut=\"posTabsShowLinks();\" $link>".$results_t[$x]."</a></li>\n";
				}		
			}
			$op .= "</ul>\n\n";
		}

		#print tabs content
		for ($x=0; $x<sizeof($results_t); $x++){
			
			#if tab title is END, just print the rest of the post
			if ($results_t[$x]=="END") {
				
				## Prints the table of contents
				if(!is_feed() && $options["TOC"]=="rightAfter") $op.=postTabs_printTOC($results_t,$post,$linktype,$options["TOC_title"]);
				
				$op .= substr($a, $results_f[$x]+1);
				break;	
			}
			
			$op .= "<div class='postTabs_divs";
			if (($results_t[$x] === $currentTab)||($x === $currentTab)) {
				$op .= " postTabs_curr_div";
			}
			$op .= "' id='postTabs_".$x."_$post'>\n";
			
			#This is the hidden title that only shows up on RSS feed or somewhere outside the context like a print page 
			$op .= "<span class='postTabs_titles'><b>".$results_t[$x]."-".$x."</b></span>";
			
			$ini = $results_f[$x]+1;
			$op.="<div class='tabWrapper'>";
			$op.="<span style='background-color:aqua;'>".(sizeof($results_t)-$x==1)."</span>";
			if (sizeof($results_t)-$x==1){
				$op .= substr($a, $results_f[$x]+1);
			}else{
				$op .= substr($a, $results_f[$x]+1, $results_i[$x+1]-$results_f[$x]-1);
			}
			$op.="</div>";
			
			#Display permalink?
//			if($options["show_perma"]!="never" && (($options["show_perma"]=="all") || ($options["show_perma"]=="registered" && $user_ID)   ) ){
//				$op .= "<span class='postmetadata'>Permalink to this post: " . get_postTabs_permalink($results_t[$x]) . "</span>";
//			}
			
			#Print the navigation
			if(!is_feed() && $options["TOC"]=="navigation"){
				$linkprev = 0;
				$linknext = 0;
				if($x>0)
					#$linkprev = ($linktype=="permalink") ? get_postTabs_permalink($x-1) : "#postTabs_ul_$post' onClick='postTabs_show(".($x-1).",$post)";		
					$linkprev = "#postTabs_ul_$post' onClick='postTabs_show(".($x-1).",$post)";		
				if ($x< (sizeof($results_t)-1)){
					if ($results_t[$x+1]!="END")
						#$linknext = ($linktype=="permalink") ? get_postTabs_permalink($x+1) : "#postTabs_ul_$post' onClick='postTabs_show(".($x+1).",$post)";
						$linknext = "#postTabs_ul_$post' onClick='postTabs_show(".($x+1).",$post)";
				}
				if($linkprev || $linknext){	
					$op .= "<div class='postTabsNavigation' style='display:none'>";
					if ($linkprev)
						$op .= "<span class='postTabs_nav_prev'><a href='$linkprev'>&lt;&lt; ".$results_t[$x-1]."</a></span>";
					if ($linknext)
						$op .= "<span class='postTabs_nav_next'><a href='$linknext'>".$results_t[$x+1]." &gt;&gt;</a></span>";
					$op .= "</div>";
				}
			}
			$op .= "</div>\n\n";
		}
		
		## Prints the table of contents
		if (!is_feed() && $options["TOC"]=="END") {
			$op.=postTabs_printTOC($results_t,$post,$linktype,$options["TOC_title"]);
		}
		
		return $op;
	}else{
		return $a;	
	}

}


function get_postTabs_permalink($tab){
	$link = get_permalink();
	$parsed_url = parse_url($link);

	$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
	$host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
	$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
	$user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
	$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
	$pass     = ($user || $pass) ? "$pass@" : ''; 
	$path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
	$path 	  = $path.urlencode($tab)."/";
	$query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
	$back =  "$scheme$user$pass$host$port$path$query$fragment"; 
	return($back);
}

function postTabs_printTOC($results_t,$post,$linktype,$title=""){
	
	if ($title) $op .= "<br><b>$title</b>";
	$op .= "<ul class='postTabs_TOC'>\n";
			
	for ($x=0; $x<sizeof($results_t); $x++){
		if($results_t[$x]!="END"){
			$op .= "<li id='postTabs_li_".$x."_$post' ";
			//if ($x==0) $op .= "class='postTabs_curr'";		
			#$link = ($linktype=="permalink") ? get_postTabs_permalink($x) : "#postTabs_ul_$post' onClick='postTabs_show($x,$post)";		
			$link = ($linktype=="permalink") ? get_postTabs_permalink($x) : "#postTabs_ul_$post' class='postTabsLinks'";		
			$op .= "><a id=\"" . $post . "_$x\" onMouseOver=\"posTabsShowLinks('".$x."'); return true;\"  onMouseOut=\"posTabsShowLinks();\" href='$link'>".$results_t[$x]."</a></li>\n";
		}		
	}
	$op .= "</ul>\n\n";
	return $op;

}


function postTabs_addCSS(){
	$postTabs_options=get_option("postTabs");
	?>
	<style type="text/css">
	    <?php require_once("style.php"); ?>
	</style>
	<?php
}

function postTabs_admin_addCSS(){
	$postTabs_options=get_option("postTabs");
	?>
	<style type="text/css">
	<?php require_once("style_admin.php"); ?>
	</style>
	<?php

}

function postTabs_admin_addJS() {
	wp_enqueue_script('postTabsColorpicker', POSTTABS_URLPATH . '301a.js'); 
}

function postTabs_admin() {
	$page_hook_suffix = add_options_page('postTabs Options', 'postTabs', 'manage_options', basename(__FILE__), 'postTabs_admin_page');
	add_action('admin_print_scripts-' . $page_hook_suffix, 'postTabs_admin_addJS');
}

function postTabs_admin_page() {
	
	require_once("postTabs_admin.php");

}

register_activation_hook( __FILE__, 'postTabs_init' );

add_filter('the_content', 'postTabs_filter');

add_action('wp_head','postTabs_addCSS');
add_action('admin_head','postTabs_admin_addCSS');

add_action('admin_menu','postTabs_admin');
