<?php
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
echo '<div class="edublink-single-course course-style-' . esc_attr( $block_data['style'] ) . '">';
    echo '<div class="inner">';
        echo '<div class="thumbnail">';
            echo '<a class="course-thumb" href="' . esc_url( get_the_permalink() ) . '">';
                echo '<img class="w-100" src="' . esc_url( $block_data['thumb_url'] ) . '" alt="' . esc_attr( edublink_thumbanil_alt_text( get_post_thumbnail_id( get_the_id() ) ) ). '">';
            echo '</a>';

            LP()->template( 'course' )->courses_loop_item_price();

            echo '<div class="read-more-btn">';
                echo '<a class="btn-icon-round" href="' . esc_url( get_the_permalink() ) . '"><i class="icon-4"></i></a>';
            echo '</div>';
        echo '</div>';

        echo '<div class="content-inner">';
            echo '<div class="instructor">';
                echo get_avatar( get_the_author_meta( 'ID' ), 40 );
                echo '<h6 class="instructor-name">';
                    the_author();
                echo '</h6>';
            echo '</div>';

            echo '<div class="content">';
                echo edublink_get_title();

                if ( true === $block_data['enable_excerpt'] ) :
                    echo wpautop( wp_trim_words( wp_kses_post( get_the_excerpt() ), esc_html( $block_data['excerpt_length'] ), esc_html( $block_data['excerpt_end'] ) ) );
                endif;
            echo '</div>';
        echo '</div>';
    echo '</div>';
echo '</div>';