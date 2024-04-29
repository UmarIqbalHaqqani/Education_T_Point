<?php
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
echo '<div class="edublink-single-course course-style-' . esc_attr( $block_data['style'] ) . '">';
    echo '<div class="inner">';
        echo '<div class="thumbnail">';
            echo '<a class="course-thumb" href="' . esc_url( get_the_permalink() ) . '">';
                echo '<img class="w-100" src="' . esc_url( $block_data['thumb_url'] ) . '" alt="' . esc_attr( edublink_thumbanil_alt_text( get_post_thumbnail_id( get_the_id() ) ) ). '">';
            echo '</a>';
        echo '</div>';

        echo '<div class="content">';
            LP()->template( 'course' )->courses_loop_item_price();
            
            if ( $block_data['cat_item'] ) :
                echo '<span class="course-cat">' . wp_kses_post( $block_data['cat_item'] ) . '</span>';
            endif;

            echo edublink_get_title();

            if ( true === $block_data['enable_excerpt'] ) :
                echo wpautop( wp_trim_words( wp_kses_post( get_the_excerpt() ), esc_html( $block_data['excerpt_length'] ), esc_html( $block_data['excerpt_end'] ) ) );
            endif;

            echo '<ul class="course-meta">';
                echo '<li>';
                    echo '<i class="icon-24 icon-course"></i>';
                    echo esc_html( $block_data['lessons'] );
                    _e( ' Lessons', 'edublink' );
                echo '</li>';
                
                echo '<li>';
                    echo '<i class="icon-25 icon-course"></i>';
                    echo esc_html( $block_data['enrolled'] );
                    _e( ' Students', 'edublink' );
                echo '</li>';
            echo '</ul>';
        echo '</div>';
    echo '</div>';
echo '</div>';