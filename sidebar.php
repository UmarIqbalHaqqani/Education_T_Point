<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package EduBlink
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$sidebar = apply_filters( 'edublink_get_sidebar', 'sidebar-default' );

if ( ! is_active_sidebar( $sidebar ) || isset( $_GET['sidebar_disable'] ) ) :
	return;
endif;

echo '<aside id="secondary" class="widget-area eb-sidebar-widget ' . esc_attr( apply_filters( 'edublink_widget_area_class', 'edublink-col-lg-4' ) ) . '">';
	echo '<div class="widget-area-wrapper">';
		do_action( 'edublink_sidebar_before' );
		dynamic_sidebar( $sidebar );
		do_action( 'edublink_sidebar_after' );
	echo '</div>';
echo '</aside>';
