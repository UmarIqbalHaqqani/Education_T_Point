<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EduBlink
 */

get_header();
if ( ! isset( $edublink_blog_post_style ) ) :
	$edublink_blog_post_style = edublink_set_value( 'blog_post_style', 'standard' );
endif;

if ( isset( $_GET['post_preset'] ) ) :
	$edublink_blog_post_style = in_array( $_GET['post_preset'], array( 1, 2, 3, 'standard' ) ) ? $_GET['post_preset'] : $edublink_blog_post_style;
endif;

$blog_layout = edublink_set_value( 'blog_archive_layout', 'right-sidebar' );
$masonry_status = edublink_set_value( 'blog_post_masonry_layout', false );
$blog_wrapper = 'edublink-row edublink-blog-post-archive-style-' . esc_attr( $edublink_blog_post_style );

if ( ( $masonry_status || isset( $_GET['masonry'] ) ) && ( 'standard' !== $edublink_blog_post_style ) ) :
	$blog_wrapper = $blog_wrapper . ' ' . 'eb-masonry-grid-wrapper';
endif;
	
echo '<div class="site-content-inner' . esc_attr( apply_filters( 'edublink_container_class', ' edublink-container' ) ) . '">';
	do_action( 'edublink_before_content' );

	echo '<div id="primary" class="content-area ' . esc_attr( apply_filters( 'edublink_content_area_class', 'edublink-col-lg-8' ) ) . '">';
		echo '<main id="main" class="site-main">';

			if ( have_posts() ) :
				if ( is_home() && ! is_front_page() ) :
					?>
					<header>
						<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
					</header>
					<?php
				endif;
				echo '<div class="' . esc_attr( $blog_wrapper ) . '">';
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();
						/*
						* Include the Post-Type-specific template for the content.
						* If you want to override this in a child theme, then include a file
						* called content-___.php (where ___ is the Post Type name) and that will be used instead.
						*/
						get_template_part( 'template-parts/posts/post', $edublink_blog_post_style );
					endwhile;
					wp_reset_postdata();
				echo '</div>';

				if ( function_exists( 'edublink_numeric_pagination' ) ) :
					edublink_numeric_pagination();
				else :
					the_posts_navigation();
				endif;
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;

		echo '</main>';
	echo '</div>';

	if ( 'no-sidebar' !== $blog_layout ) :
		get_sidebar();
	endif;

	do_action( 'edublink_after_content' );
echo '</div>';

get_footer();