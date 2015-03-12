<?php

/*
                                                _          
                      o                        | |         
 _  _  _    __,    _     ,   __   _  _      _  | |      _  
/ |/ |/ |  /  |  |/ \_| / \_/  \_/ |/ |   |/ \_|/ \   |/ \_
  |  |  |_/\_/|_/|__/ |/ \/ \__/   |  |_/o|__/ |   |_/|__/ 
                /|   /|                  /|          /|    
                \|   \|                  \|          \|    


mapjson.php

    pulls in all "node" items from WP, as well as their custom fields and links
    organises by line and position
    dumps as json 

    version 0.1 { Fri May 23 13:45:39 2014 }  
        initial creation

*/

?>
var _nodes = 
<?php

$__nodes = array();
$__lines = array();

$__slod_mapjson_query = new WP_Query( array( 
    'post_type' => 'node', 
    'nopaging' => true,
    'paged' => 0,
    'posts_per_page' => -1 
) );

p2p_type( 'outbound_node_to_node' )->each_connected( $__slod_mapjson_query );

if ( $__slod_mapjson_query->have_posts() ) {

    while ( $__slod_mapjson_query->have_posts() ) {
        global $post;
        $__slod_mapjson_query->the_post();
        $_id = $post->ID;
        $node = array();
        $node["x"] = get_field( "x" );
        $node["y"] = get_field( "y" );
        $node["type" ] = get_field( "type" );
        $node["title"] = get_the_title();
        $node["link"] = get_permalink();
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
        // get our edges
        $node["edges"]=array();
        foreach ( $post->connected as $post ) {
            setup_postdata( $post );
            $edge=array();
            $edge["from"] = wp_is_post_revision($post->p2p_from)?wp_is_post_revision($post->p2p_from):$post->p2p_from;
            $edge["to"] = wp_is_post_revision($post->p2p_to)?wp_is_post_revision($post->p2p_to):$post->p2p_to;
            $edge["category"] = p2p_get_meta( $post->p2p_id, 'track', true );
            array_push( $node["edges"], $edge);
            wp_reset_postdata(); 
        }
        // store it in the right bucket
        if(!isset($__lines[$node["category"]])) {
            $__lines[$node["category"]]=array();
        }
        $__lines[$node["category"]][$_id] = $node;
    }
    // and dump it - we're done
    echo(json_encode($__lines));
    wp_reset_postdata();
} else {
        //<!-- show 404 error here -->
}

?>