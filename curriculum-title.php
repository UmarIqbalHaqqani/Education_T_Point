<?php
defined( 'ABSPATH' ) || exit();
$course = learn_press_get_the_course();

if ( ! isset( $section ) ) :
	return;
endif;

$title = $section->get_title();

echo '<div class="section-header">';
    echo '<div class="section-left">';
		if ( $title ) :
            echo '<h5 class="section-title">' . esc_html( $title ) . '</h5>';
		endif;

		if ( $description = $section->get_description() ) :
            echo '<p class="section-desc">' . wp_kses_post( $description ) . '</p>';
		endif;

    echo '</div>';
echo '</div>';