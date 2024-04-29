<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EduBlink
 */

get_header();

echo '<div class="site-content-inner' . esc_attr( apply_filters( 'edublink_container_class', ' edublink-container' ) ) . '">';
	do_action( 'edublink_before_content' );
	
	echo '<div id="primary" class="content-area ' . esc_attr( apply_filters( 'edublink_content_area_class', 'edublink-col-lg-8' ) ) . '">';
		echo '<main id="main" class="site-main">';
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile; // End of the loop.
		echo '</main>';
	echo '</div>';

	$page_layout  = get_post_meta( get_the_ID(), 'edublink_page_layout_type', true );
	$content_type = get_post_meta( get_the_ID(), 'edublink_page_content_type', true );
	if ( 'boxed' === $page_layout && 'no-sidebar' !== $content_type ) :
		get_sidebar();
	endif;

	do_action( 'edublink_after_content' );
echo '</div>';
get_footer();
