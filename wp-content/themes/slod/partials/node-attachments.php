<?php 
/*
  $attachments = get_posts( array(
      'post_type' => 'attachment',
      'posts_per_page' => -1,
      'post_parent' => $post->ID,
      'exclude'     => get_post_thumbnail_id(),
      
  ) );

  if ( $attachments ) {
      foreach ( $attachments as $attachment ) {
          $class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
          $thumbimg = wp_get_attachment_link( $attachment->ID, 'thumbnail-size', true );
          echo '<li class="' . $class . ' ">' . $thumbimg . '</li>';
      }
  }
*/



global $post;
$post_parent_id=$post->ID;
$post_parent_thumbnail_id=get_post_thumbnail_id($post_parent_id);

$alltags = get_terms('post_tag');
$cluster_count=0;
if ($alltags){
  foreach( $alltags as $tag ) {
    $args=array(
      'tag__in' => array($tag->term_id),
      'post_type' => 'attachment',
      'post_status' => 'inherit',
      'posts_per_page' => -1,
//      'showposts' => -1,
      'post_parent' => $post_parent_id,
//      'caller_get_posts'=> 1,
      'exclude'     => $post_parent_thumbnail_id
      );

//    $my_query = null;
//    $my_query = new WP_Query($args);
		$attachments=get_children($args);
//    if( $my_query->have_posts() ) {
			if($attachments) {
				$cluster_count+=1;
?>
			<ul class="attachments span-4 append-1<?php echo($cluster_count%3==0?" last":""); ?>">
				<h4><?php echo($tag->name); ?></h4>
<?php

//      while ($my_query->have_posts()) : $my_query->the_post(); ? >
				foreach($attachments as $id=>$attachment) {
//					var_dump($attachment);
//					$img = wp_get_attachment_thumb_url( $attachment->ID );
					$img=wp_get_attachment_image($id, array(10,10), 1);
					$img_url=wp_get_attachment_image_src($attachment->ID,array(32,32),1);//wp_get_attachment_thumb_url($attachment->ID);
					$link = get_permalink( $id );
					$caption = $attachment->post_content;
//					print "\n\n" . '<img class="thumb" src="' . $img . '" alt="" />';

					$class = "post-attachment mime-" . sanitize_title( $attachment->post_mime_type );
					$title = wp_get_attachment_link( $attachment->ID, false );
					$content = "<li style=\"background-image:url('".$img_url[0]."');\" class=\"" . $class . "\">" .  $title . '<span class="caption">'.$caption.'</li>';


?>				
        <?php /*<li><a href="<?php echo($link); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo($img); //var_dump($attachment); ?></a><span class="caption"><?php echo($caption); ?></span></li> */ echo($content); ?>
       <?php
      }
    }
?>
			</ul>
<?php
  }
}
wp_reset_query();  // Restore global post data stomped by the_post().

?>
