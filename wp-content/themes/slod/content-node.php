<?php
/**
 * The template used for displaying page content
 *
 * @package Twenty_Fourteen
 * @subpackage SLOD
 * @since Twenty Fourteen 1.0
 */
?>

<!--content-node.php-->
<div class="earlier station list span-5">
<?php		get_template_part( 'partials/node', 'earlier' ); ?>
</div>

<article id="post-<?php the_ID(); ?>" <?php post_class("span-14"); ?>>
	<?php
		// Page thumbnail and title.
		twentyfourteen_post_thumbnail();
		the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' );
	?>
	<div class="entry-content">
		<?php
			global $_slod_in_post_body;
			$_slod_in_post_body = true;
			the_content();
			wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfourteen' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
			) );

			edit_post_link( __( 'Edit', 'twentyfourteen' ), '<span class="edit-link">', '</span>' );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->

<div class="later station list span-5 last">
<?php		get_template_part( 'partials/node', 'later' ); ?>
</div>

<div id="attachments" class="span-14 prepend-5 last js-masonry" data-masonry-options='{ columnWidth":"160px", "itemSelector":"ul" }' >
<?php
			global $_slod_in_post_body;
			$_slod_in_post_body = true;
?>
<?php		get_template_part( 'partials/node', 'attachments' ); ?>
</div>