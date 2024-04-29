<?php
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
echo '<div class="edublink-single-course course-style-' . esc_attr( $block_data['style'] ) . '">';
    echo '<div class="inner">';
        echo '<div class="thumbnail">';
            echo '<a class="course-thumb" href="' . esc_url( get_the_permalink() ) . '">';
                echo '<img class="w-100" src="' . esc_url( $block_data['thumb_url'] ) . '" alt="' . esc_attr( edublink_thumbanil_alt_text( get_post_thumbnail_id( get_the_id() ) ) ). '">';
            echo '</a>';

            if ( $block_data['class_type'] ) :
                echo '<div class="time-top">';
                    echo '<span class="duration"><i class="icon-61"></i>' . esc_html( $block_data['class_type'] ) . '</span>';
                echo '</div>';
            endif;
        echo '</div>';

        echo '<div class="content">';
            if ( $block_data['cat_item'] ) :
                echo '<span class="course-level">' . wp_kses_post( $block_data['cat_item'] ) . '</span>';
            endif;

            echo edublink_get_title();

            if ( true === $block_data['enable_excerpt'] ) :
                echo wpautop( wp_trim_words( wp_kses_post( get_the_excerpt() ), esc_html( $block_data['excerpt_length'] ), esc_html( $block_data['excerpt_end'] ) ) );
            endif;

            if ( class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
                echo '<div class="course-rating">';
                    edublink_lp_course_ratings();
                echo '</div>';
            endif;

            if ( $block_data['button_text'] ) :
                echo '<div class="read-more-btn">';
                    echo '<a class="edu-btn btn-small btn-secondary" href="' . esc_url( get_the_permalink() ) . '">' . esc_html( $block_data['button_text'] ) . '<i class="icon-4"></i></a>';
                echo '</div>';
            endif;
        echo '</div>';
    echo '</div>';
echo '</div>';