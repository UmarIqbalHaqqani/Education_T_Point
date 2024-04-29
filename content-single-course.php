<?php
/**
 * Template for displaying content of course without header and footer
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit();

/**
 * If course has set password
 */
if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

$course_details_style = edublink_set_value( 'lp_course_details_style', 1 );
$course_details_sidebar = edublink_set_value( 'lp_course_details_sidebar_status', true );
$course_sidebar_sticky = edublink_set_value( 'lp_course_details_sidebar_sticky', true );
$course_details_column = 'edublink-col-lg-12';
$siebar_main_content = 'course-summary';
$course_sidebar_class = 'ed-course-sidebar';

if ( isset( $_GET['course_details'] ) ) :
	$course_details_style = in_array( $_GET['course_details'], array( 1, 2, 3, 4, 5, 6 ) ) ? $_GET['course_details'] : 1;
endif;

if ( $course_details_sidebar ) :
	$course_details_column = 'edublink-col-lg-8';
endif;

if ( isset( $_GET['disable_sidebar'] ) ) :
	$course_details_column = 'edublink-col-lg-12';
	$course_details_sidebar = false;
endif;

if ( isset( $_GET['sidebar_sticky'] ) ) :
	$course_sidebar_sticky = true;
endif;

if ( in_array( $course_details_style, array( 3, 4 ) ) ) :
	if ( $course_sidebar_sticky ) :
		wp_enqueue_script( 'theia-sticky-sidebar' );
		$siebar_main_content .= $siebar_main_content . ' ' . 'eb-sticky-sidebar-parallal-content';
		$course_sidebar_class = $course_sidebar_class . ' ' . 'eb-sidebar-sticky';
	endif;
endif;

edublink_lp_course_details_header( $course_details_style );

/**
 * LP Hook
 */
do_action( 'learn-press/before-single-course' );

echo '<div class="edublink-course-details-page lp-course-single-page eb-course-single-style-' . esc_attr( $course_details_style ) . '">';
	echo '<div class="edublink-container">';
		echo '<div class="edublink-row">';
			echo '<div id="learn-press-course" class="' . esc_attr( $siebar_main_content ) . ' ' . apply_filters( 'courese_details_columnn', $course_details_column ) . '">';
				echo '<div class="eb-course-details-page-content">';

					learn_press_get_template( 'custom/course-details/top-content' );

					if ( $course_details_style == '2' || $course_details_style == '3' || $course_details_style == '5' ) :
						learn_press_get_template( 'custom/course-details/style-2' );
					elseif ( $course_details_style == '4' ) :
						learn_press_get_template( 'custom/course-details/style-4' );
					else :
						/**
						 * @since 3.0.0
						 *
						 * @called single-course/content.php
						 * @called single-course/sidebar.php
						 */
						do_action( 'learn-press/single-course-summary' );
					endif;
				echo '</div>';
			echo '</div>';

			if ( $course_details_sidebar ) :
				echo '<div class="' . esc_attr( $course_sidebar_class ) . ' ' . apply_filters( 'courese_details_sidebar_columnn', 'edublink-col-lg-4' ) . '">';
					edublink_lp_course_content_sidebar();
				echo '</div>';
			endif;
		echo '</div>';
	echo '</div>';
echo '</div>';

edublink_lp_related_courses();

/**
 * LP Hook
 */
do_action( 'learn-press/after-single-course' );
