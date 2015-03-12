<?php

/*

                |                         | | o                  | |         
 _  _    __   __|   _      _   __,   ,_   | |     _   ,_      _  | |      _  
/ |/ |  /  \_/  |  |/-----|/  /  |  /  |  |/  |  |/  /  |   |/ \_|/ \   |/ \_
  |  |_/\__/ \_/|_/|__/   |__/\_/|_/   |_/|__/|_/|__/   |_/o|__/ |   |_/|__/ 
                                                           /|          /|    
                                                           \|          \|    

node-earlier.php

		finds 'earlier' nodes for this node and dumps them out as an UL

    version 0.1 
        initial creation
    version 0.2
    		reversed node lookups

*/

?><?php

$__slod_links = new WP_Query( array(
  'post_type' => 'node',
  'connected_type' => 'outbound_node_to_node',
  'connected_from' => $post->ID,
  'connected_items' => get_queried_object(),
  'nopaging' => true,
) );


if ( $__slod_links->have_posts() ) {
?>
<nav>
<h2>Earlier Stations</h2>
	<ul class="earlier sign <?php echo($node['category']); ?>">
<?
		$last_category=-1;
		$outer_open=false;
    while ( $__slod_links->have_posts() ) {
        global $post;
        $__slod_links->the_post();
        $__slod_track = p2p_get_meta( $post->p2p_id, 'track', true );
        $__slod_track_class = slod_CSSify( $__slod_track );
?>
		<li>
			<a class="tile earlier <?php echo($__slod_track_class); ?>" href="<?php echo(get_the_permalink() ); ?>">
				<div class="station-name">
					<span class="direction"><span>←</span></span>
					<span class="name"><?php echo(get_the_title()); ?></span>
					<span class="track"><?php echo($__slod_track); ?></span>
				</div>
			</a>
		</li>
<?php
	}
?>
	</ul>
</nav>
<?php
	wp_reset_postdata();
}
?>
