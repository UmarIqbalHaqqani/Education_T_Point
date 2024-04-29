<?php
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
echo '<div class="edublink-single-course course-style-' . esc_attr( $block_data['style'] ) . '">';
    echo '<div class="inner">';
        echo '<div class="thumbnail">';
            echo '<a class="course-thumb" href="' . esc_url( get_the_permalink() ) . '">';
                echo '<img class="w-100" src="' . esc_url( $block_data['thumb_url'] ) . '" alt="' . esc_attr( edublink_thumbanil_alt_text( get_post_thumbnail_id( get_the_id() ) ) ). '">';
            echo '</a>';

            echo '<div class="time-top">';
                echo '<span class="duration"><i class="icon-61"></i>' . esc_html( $block_data['duration'] ) . '</span>';
            echo '</div>';
        echo '</div>';

        echo '<div class="content">';
            echo $block_data['level'] ? '<span class="course-level">' . esc_html( $block_data['level'] ) . '</span>' : '';

            echo edublink_get_title();

            if ( class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
                echo '<div class="course-rating">';
                    edublink_lp_course_ratings();
                echo '</div>';
            endif;

            LP()->template( 'course' )->courses_loop_item_price();

            echo '<ul class="course-meta">';
                echo '<li>';
                    echo '<i class="icon-24"></i>';
                    echo esc_html( $block_data['lessons'] );
                    _e( ' Lessons', 'edublink' );
                echo '</li>';
                
                echo '<li>';
                    echo '<i class="icon-25"></i>';
                    echo esc_html( $block_data['enrolled'] );
                    _e( ' Students', 'edublink' );
                echo '</li>';
            echo '</ul>';
        echo '</div>';
    echo '</div>';

    echo '<div class="course-hover-content-wrapper">';
        echo '<div class="wishlist-top-right">';
            edublink_lp_wishlist_icon( get_the_ID() );
        echo '</div>';
    echo '</div>';

    echo '<div class="course-hover-content">';
        echo '<div class="content">';

            echo $block_data['level'] ? '<span class="course-level">' . esc_html( $block_data['level'] ) . '</span>' : '';

            echo edublink_get_title();

            if ( class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
                echo '<div class="course-rating">';
                    edublink_lp_course_ratings();
                echo '</div>';
            endif;

            LP()->template( 'course' )->courses_loop_item_price();

            if ( true === $block_data['enable_excerpt'] ) :
                echo wpautop( wp_trim_words( wp_kses_post( get_the_excerpt() ), esc_html( $block_data['excerpt_length'] ), esc_html( $block_data['excerpt_end'] ) ) );
            endif;

            echo '<ul class="course-meta">';
                echo '<li>';
                    echo '<i class="icon-24"></i>';
                    echo esc_html( $block_data['lessons'] );
                    _e( ' Lessons', 'edublink' );
                echo '</li>';

                echo '<li>';
                    echo '<i class="icon-25"></i>';
                    echo esc_html( $block_data['enrolled'] );
                    _e( ' Students', 'edublink' );
                echo '</li>';
            echo '</ul>';

            if ( $block_data['button_text'] ) :
                echo '<a class="edu-btn btn-secondary btn-small" href="' . esc_url( get_the_permalink() ) . '">' . esc_html( $block_data['button_text'] ) . '<i class="icon-4"></i></a>';
            endif;
        echo '</div>';
    echo '</div>';
echo '</div>';