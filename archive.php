<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EduBlink
 */

if ( is_tax( 'tp_event_tag' ) ) :
	get_template_part( 'wp-events-manager/archive', 'event' );
	return;
endif;

$author_redirect_to_courses = apply_filters( 'edublink_ld_author_redirect_to_course', false );
if ( isset( $_GET['ldauthor'] ) ) :
	$ldauthor = $_GET['ldauthor'];
else :
	$ldauthor = false;
endif;

get_header();

$blog_layout = apply_filters( 'edublink_archive_sidebar_layout', edublink_set_value( 'blog_archive_layout', 'right-sidebar' ) );
echo '<div class="site-content-inner' . esc_attr( apply_filters( 'edublink_container_class', ' edublink-container' ) ) . '">';
	do_action( 'edublink_before_content' );

	echo '<div id="primary" class="edublink-' . get_post_type() . '-archive-wrapper content-area ' . esc_attr( apply_filters( 'edublink_content_area_class', 'edublink-col-lg-8' ) ) . '">';
		echo '<main id="main" class="site-main">';
			if ( 'sfwd-courses' === get_post_type() ) :
				get_template_part( 'template-parts/archive/content', 'sfwd-courses' );
			else :
				get_template_part( 'template-parts/content', 'blog' );
			endif;
		echo '</main>';
	echo '</div>';

	if ( 'no-sidebar' !== $blog_layout ) :
		if ( ( true != $author_redirect_to_courses ) || ( true == $author_redirect_to_courses && 'true' != $ldauthor ) ) :
			get_sidebar();
		endif;
	endif;

	do_action( 'edublink_after_content' );
echo '</div>';

get_footer();