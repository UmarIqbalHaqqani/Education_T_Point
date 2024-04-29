<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package EduBlink
 */

		echo '</div>';
		$footer = apply_filters( 'edublink_get_footer_layout', edublink_set_value( 'footer_type' ) );
		$default_footers = array( 'theme-default-footer' );
		$footer_custom_copyright = edublink_set_value( 'footer_custom_copyright_text' );

		if ( 'none' !== $footer ) :
			if ( in_array( $footer, $default_footers ) || empty( $footer ) ) :
				echo '<footer id="colophon" class="edublink-footer-default-wrapper site-footer">';
					echo '<div class="site-info ' . esc_attr( apply_filters( 'edublink_footer_site_info_container_class', 'edublink-container' ) ) . '">';
						echo '<div class="edublink-row">';
							echo '<div class="edublink-col-lg-12">';
								if ( $footer_custom_copyright ) :
									echo wp_kses_post( $footer_custom_copyright );
								else :
									$allowed_html_array = array( 'a' => array( 'href' => array() ) );
									echo wp_kses( sprintf( __( '&copy; %s - EduBlink. All Rights Reserved. Proudly powered by <a href="https://devsblink.com">DevsBlink</a>', 'edublink' ), date( "Y" ) ), $allowed_html_array );
								endif;
							echo '</div>';
						echo '</div>';
					echo '</div>';
				echo '</footer>';
			else :
				edublink_show_footer_builder( $footer );
			endif;
		endif;
	echo '</div>';

	wp_footer();
echo '</body>';
echo '</html>';
