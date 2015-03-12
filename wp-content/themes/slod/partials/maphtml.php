<?php

/*

                       _                    _         _          
                      | |                  | |       | |         
 _  _  _    __,    _  | |   _|_  _  _  _   | |    _  | |      _  
/ |/ |/ |  /  |  |/ \_|/ \   |  / |/ |/ |  |/   |/ \_|/ \   |/ \_
  |  |  |_/\_/|_/|__/ |   |_/|_/  |  |  |_/|__/o|__/ |   |_/|__/ 
                /|                             /|          /|    
                \|                             \|          \|   

maphtml.php

    pulls in all "node" items from WP, as well as their custom fields and links
    organises by line and position
    dumps as json 

    version 0.1 { Tuesday, 24 June 2014 17:08:49 }  
        initial creation

*/

?><?php

$__nodes = array();
$__lines = array();

$__slod_mapjson_query = new WP_Query( array( 
    'post_type' => 'node', 
    'nopaging' => true,
    'paged' => 0,
    'posts_per_page' => -1 
) );

if ( $__slod_mapjson_query->have_posts() ) {
?>
<nav>
	<div class="subway-map" data-columns="120" data-rows="120" data-cellsize="4" data-legendId="legend" data-textClass="text" data-gridNumbers="true" data-grids="false">
<?
		$last_category=-1;
		$outer_open=false;
    while ( $__slod_mapjson_query->have_posts() ) {
        global $post;
        $__slod_mapjson_query->the_post();
        $_id = $post->ID;
        $node = array();
        $_field_type = (int)get_field( "type" );
        $_field_types = array( '', 'interchange', '@station' );
        $node["type" ] = $_field_types[ $_field_type ];
        $node["title"] = get_the_title();
        $node["link"] = get_permalink();
        $node["x"] = (int)get_field( "x" ) / 10;
        $node["y"] = (int)get_field( "y" ) / 10;
        // figure out a category for the node
        $terms = get_the_terms( $post->ID , 'node_category' );
        $node["category"] = "";
        foreach ( $terms as $term ) {
            if($term->name) {
                $node["category"] .= ($node["category"]===""?"":" ").$term->name;
            }
        }
        if($node["category"]==="") {
            $node["category"]="None";
        }
        // get path data
        $path="";
				// check if the repeater field has rows of data
?>
	<ul data-color="#ff4db2" data-label="<?php echo($node['category']); ?>">
<?php
				if( have_rows('path') ) {				
				 	// loop through the rows of data
				  while ( have_rows('path') ) {
				  	the_row();
		        // display a sub field value
		        $data_dir = the_sub_field('data_dir')?' data-dir="'.the_sub_field('data_dir').'" ':'';
?>
		<li data-coords="<?php echo(the_sub_field('x')); ?>, <?php echo(the_sub_field('y')); ?>" <?php echo($data_dir); ?> >
<?php
			if($node[link]>"") {
?>
			<a href="<?php echo($node["link"]); ?>"><?php echo($node["title"]); ?></a>
<?php				
				$node[link]=""; // so we don't do it again
			}
?>
		</li>
<?php
				  }
				} else {
				    // no rows found
						echo("<!--no rows!-->\n");
				}

				if($last_category!=$node["category"]) {
					if($outer_open==true) { 						
					} 
					$outer_open=true;
				} 
        if($node["category"]=="None") {
            $last_category=-1;
        } else {
	         $last_category=$node["category"];
	      }	
//	      echo("\n<!--".var_dump($node)."-->");
				?>
		</ul>
<?php
    }
    // done!
    wp_reset_postdata();
?>
    </ul>
</nav>
<?php
} else {
        //<!-- show 404 error here -->
}

?>