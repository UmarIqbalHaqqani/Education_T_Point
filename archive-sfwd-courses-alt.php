<?php
/**
 * The template for displaying archive pages
 * @package EduBlink
 * Version: 1.0.0
 */

get_header(); 
echo '<div class="site-content-inner' . esc_attr( apply_filters( 'edublink_container_class', ' edublink-container' ) ) . '">';
	do_action( 'edublink_before_content' );
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                echo '<div class="edublink-col-lg-12">';
                    the_title();
                echo '</div>';
            endwhile;
            the_posts_pagination();
        else :
            _e( 'Sorry, No Course Found.', 'edublink' );
        endif;
echo '</div>';

get_footer();
