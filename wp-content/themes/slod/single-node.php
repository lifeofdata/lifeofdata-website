<?php
/**
 * The template used for displaying node page content
 *
 * @package Twenty_Fourteen
 * @subpackage SLOD
 * @since Twenty Fourteen 1.0
 */

get_header(); 

?>
<!--single-node-->
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', 'node' );

					// Previous/next post navigation.
					//twentyfourteen_post_nav();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
?>
						<div id="commentblock" class="span-14 prepend-5">
							<?php comments_template(); ?>
						</div>
<?php
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->

<?php
//get_sidebar( 'content' );
//get_sidebar();
get_footer();
