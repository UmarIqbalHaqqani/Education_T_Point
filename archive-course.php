<?php
/**
 * Template for displaying content of single course.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

use \EduBlink\Filter;
get_header();

if ( ! isset( $block_data ) ) :
    $block_data = array();
endif;

if ( ! isset( $style ) ) :
    $style = edublink_set_value( 'lp_course_style', 1 );
endif;

if ( isset( $_GET['course_preset'] ) ) :
    $style = Filter::grid_layout_keys();
endif;

$default_data = array(
    'style' => $style
);

$block_data = wp_parse_args( $block_data, $default_data );

$edublink_course_container = array();
$masonry_status = edublink_set_value( 'lp_course_masonry_layout', false );
$wrapper = 'edublink-lms-courses-grid edublink-row edublink-course-archive';

if ( $masonry_status || isset( $_GET['masonry'] ) ) :
	$wrapper = $wrapper . ' ' . 'eb-masonry-grid-wrapper';
    $edublink_course_container[] = 'eb-masonry-item';
endif;

if ( ! isset( $column ) ) :
    $column = apply_filters( 'edublink_course_archive_grid_column', array( 'edublink-col-lg-4 edublink-col-md-6 edublink-col-sm-12' ) );
endif;

if ( isset( $_GET['column'] ) ) :
    if ( $_GET['column'] == 2 ) :
        $column = array( 'edublink-col-lg-6 edublink-col-md-6 edublink-col-sm-12' );
    endif;
endif;

if ( isset( $_GET['active_white_bg'] ) || edublink_set_value( 'lp_course_white_bg' ) ) :
    $edublink_course_container[] = 'active-white-bg';
endif;

$edublink_course_container[] = 'edublink-course-style-' . esc_attr( $style );

$edublink_course_container = array_merge( $edublink_course_container, $column );

$args = array( 
    'post_type'      => LP_COURSE_CPT,
    'order'          => 'DESC',
    'paged'          => get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1,
    'posts_per_page' => LP()->settings->get( 'learn_press_archive_course_limit' ) 
);

$args = apply_filters( 'edublink_lp_course_archive_args', $args );
$query = new WP_Query( $args );

echo '<div class="site-content-inner' . esc_attr( apply_filters( 'edublink_container_class', ' edublink-container' ) ) . '">';
	do_action( 'edublink_before_content' );

    edublink_lp_course_header_top_bar( $query );

    if ( $query->have_posts() ) :
        echo '<div class="' . esc_attr( $wrapper ) . '">';
            while ( $query->have_posts() ) : $query->the_post(); ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class( apply_filters( 'edublink_course_loop_classes', $edublink_course_container ) ); ?> data-sal>
                    <?php
                        learn_press_get_template( 'custom/course-block/blocks.php', compact( 'block_data' ) );
                    ?>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        echo '</div>';
        
        $GLOBALS['wp_query']->max_num_pages = $query->max_num_pages;
        edublink_numeric_pagination();
    else :
        _e( 'Sorry, No Course Found.', 'edublink' );
    endif;

    do_action( 'edublink_after_content' );
echo '</div>';

get_footer();