<?php
/**
 * The template for displaying
 * LearnDashh Category archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package EduBlink
 */
get_header();
use \EduBlink\Filter;

if ( ! isset( $block_data ) ) :
    $block_data = array();
endif;

if ( ! isset( $style ) ) :
    $style = edublink_set_value( 'ld_course_style', 1 );
endif;

if ( isset( $_GET['course_preset'] ) ) :
    $style = Filter::grid_layout_keys();
endif;

$default_data = array(
    'style' => $style
);

$edublink_course_container = array();

if ( ! isset( $column ) ) :
    $column = apply_filters( 'edublink_course_archive_grid_column', array( 'edublink-col-lg-4 edublink-col-md-6 edublink-col-sm-12' ) );
endif;

$edublink_course_container = array_merge( $edublink_course_container, $column );
$args = wp_parse_args( $block_data, $default_data );

echo '<div class="site-content-inner' . esc_attr( apply_filters( 'edublink_container_class', ' edublink-container' ) ) . '">';
	do_action( 'edublink_before_content' );
	echo '<div id="primary" class="edublink-' . get_post_type() . '-archive-wrapper content-area ' . esc_attr( apply_filters( 'edublink_content_area_class', 'edublink-col-lg-8' ) ) . '">';
		echo '<main id="main" class="site-main">';
            if ( have_posts() ) :
                echo '<div class="edublink-lms-courses-grid edublink-row edublink-course-archive">';
                    while ( have_posts() ) : the_post(); 
                        ?>
                        <div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'edublink_course_loop_classes', $edublink_course_container ) ); ?> data-sal>
                            <?php get_template_part( 'learndash/custom/course-block/blocks', '', $args );
                        echo '</div>';
                    endwhile;
                    wp_reset_postdata();
                echo '</div>';
            else :
                _e( 'Sorry, No Course Found.', 'edublink' );
            endif;
		echo '</main>';
	echo '</div>';
	do_action( 'edublink_after_content' );
echo '</div>';

get_footer();