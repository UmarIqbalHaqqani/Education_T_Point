<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package EduBlink
 */

get_header();
	?>
	<section class="edublink-main-content-inner error-page-area">
		<div class="edublink-container">
			<div class="edu-error">
				<div class="thumbnail">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/error/404.png' ); ?>" alt="404 Error">
					<ul class="shape-group">
						<li class="shape-1 shape-image eb-mouse-animation">
							<span data-depth="2">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/error/shape-25.png' ); ?>" alt="Shape">
							</span>
						</li>
						<li class="shape-2 shape-image eb-mouse-animation">
							<span data-depth="-2">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/error/shape-15.png' ); ?>" alt="Shape">
							</span>
						</li>
						<li class="shape-3 shape-image eb-mouse-animation">
							<span data-depth="2">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/error/shape-13.png' ); ?>" alt="Shape">
							</span>
						</li>
						<li class="shape-4 shape-image eb-mouse-animation">
							<span data-depth="-2">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/error/shape-02.png' ); ?>" alt="Shape">
							</span>
						</li>
					</ul>
				</div>

				<div class="content">
					<?php
						$error_page_title = edublink_set_value( 'error_page_title', __( '404 - Page Not Found', 'edublink' ) );
						$error_page_desc = edublink_set_value( 'error_page_description', __( 'The page you are looking for does not exist.', 'edublink' ) );

						if ( ! empty( $error_page_title ) ) :
							echo '<h2 class="title">' . wp_kses_post( $error_page_title ) . '</h2>';
						endif;

						if ( ! empty( $error_page_desc ) ) :
							echo '<h4 class="subtitle">' . wp_kses_post( $error_page_desc ) . '</h4>';
						endif;
					?>
					<a href="<?php echo esc_url( get_site_url() );?>" class="edu-btn"><i class="icon-west"></i><?php echo __( 'Back to Homepage', 'edublink' ); ?></a>
				</div>
			</div>
		</div>
		<ul class="shape-group">
			<li class="shape-1">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/error/map-shape-2.png' ); ?>" alt="Shape">
			</li>
		</ul>
		</section>
	<?php
get_footer();