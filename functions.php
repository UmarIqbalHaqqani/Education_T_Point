<?php

remove_action( 'admin_footer', 'learn_press_footer_advertisement', - 10 );

remove_action( 'learn-press/user-profile', LP()->template( 'profile' )->func( 'login_form' ), 10 );
remove_action( 'learn-press/user-profile', LP()->template( 'profile' )->func( 'register_form' ), 15 );

// remove breadcrumbs
remove_action( 'learn-press/before-main-content', LP()->template( 'general' )->func( 'breadcrumb' ) );
remove_action( 'learn-press/before-main-content', 'learn_press_breadcrumb', 10 );
remove_action( 'learn-press/before-main-content', 'learn_press_search_form', 15 );

remove_all_actions( 'learn-press/course-content-summary', 10 );
remove_all_actions( 'learn-press/course-content-summary', 15 );
remove_all_actions( 'learn-press/course-content-summary', 30 );
remove_all_actions( 'learn-press/course-content-summary', 35 );
remove_all_actions( 'learn-press/course-content-summary', 40 );
remove_all_actions( 'learn-press/course-content-summary', 80 );

// remove course sidebar
remove_all_actions( 'learn-press/course-content-summary', 85 );
remove_all_actions( 'learn-press/course-content-summary', 100 );

remove_action( 'learn-press/after-courses-loop-item', 'learn_press_course_loop_item_buttons', 35 );
remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_price', 20 );
remove_action( 'learn-press/after-courses-loop-item', 'learn_press_courses_loop_item_instructor', 25 );
remove_action( 'learn-press/courses-loop-item-title', 'learn_press_courses_loop_item_thumbnail', 10 );
remove_action( 'learn-press/courses-loop-item-title', 'learn_press_courses_loop_item_title', 15 );

/* 
 * Course Sidebar Button 
 */
if ( class_exists( 'LP_Prere_Course_Hooks' ) ) :
	$edublink_lp_prerequisite_plugin = LP_Prere_Course_Hooks::get_instance();
	remove_action( 'learn-press/course-buttons', [$edublink_lp_prerequisite_plugin, 'check_condition'], 1 );
endif;

/* 
 * Remove Wishlist Button From Sidebar 
 */
add_action( 'edublink_course_sidebar_lp_button', LearnPress::instance()->template( 'course' )->func( 'course_enroll_button' ), 5 );
add_action( 'edublink_course_sidebar_lp_button', LearnPress::instance()->template( 'course' )->func( 'course_purchase_button' ), 10 );
add_action( 'edublink_course_sidebar_lp_button', LearnPress::instance()->template( 'course' )->func( 'course_external_button' ), 15 );
add_action( 'edublink_course_sidebar_lp_button', LearnPress::instance()->template( 'course' )->func( 'button_retry' ), 20 );
add_action( 'edublink_course_sidebar_lp_button', LearnPress::instance()->template( 'course' )->func( 'course_continue_button' ), 25 );
add_action( 'edublink_course_sidebar_lp_button', LearnPress::instance()->template( 'course' )->func( 'course_finish_button' ), 30 );

/* 
 * Course Price With Deciaml Separator 
 */
add_filter( 'learn_press_course_origin_price_html', 'edublink_lp_course_price_decimal_separator', 99, 1 );
add_filter( 'learn_press_course_price_html', 'edublink_lp_course_price_decimal_separator', 99, 1 );

/**
 * LearnPress specific scripts & stylesheets.
 *
 * @return void
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'edublink_lp_scripts' ) ) :
	function edublink_lp_scripts() {
		wp_enqueue_style( 'edublink-lp-style', esc_url( get_template_directory_uri() . '/assets/css/learnpress.css' ), array( 'learnpress' ), EDUBLINK_THEME_VERSION );

		if ( is_singular( LP_COURSE_CPT ) ) :
			wp_enqueue_style( 'jquery-fancybox' );
			wp_enqueue_script( 'jquery-fancybox' );
		endif;
	}
endif;
add_action( 'wp_enqueue_scripts', 'edublink_lp_scripts' );

/**
 * Course Page Container Class
 *
 * @since 1.0.0
 */
add_filter( 'edublink_container_class', 'edublink_lp_course_container_class' );
if ( ! function_exists( 'edublink_lp_course_container_class' ) ) :
	function edublink_lp_course_container_class ( $class ) {
		if ( is_singular( LP_COURSE_CPT ) ) :
			return ' edublink-container edublink-lp-course-details-page';
		else :
			return $class;
		endif;
	}
endif;

/**
 * Content area class
 */
add_filter( 'edublink_content_area_class', 'edublink_lp_content_area_class' );
if ( ! function_exists( 'edublink_lp_content_area_class' ) ) :
	function edublink_lp_content_area_class ( $class ) {

		if ( is_post_type_archive( LP_COURSE_CPT ) || is_tax( 'course_category' ) ) :

			$course_layout = 'full_width';

			if ( 'right' === $course_layout ) :
				$class = 'edublink-col-lg-9';
			elseif ( 'left' === $course_layout ) :
				$class = 'edublink-col-lg-9 edublink-order-1';
			elseif ( 'full_width' === $course_layout ) :
				$class = 'edublink-col-lg-12';
			endif;
		endif;

		if ( is_singular( LP_COURSE_CPT ) ) :
			
			$single_course_layout = 'full_width';

			if ( 'right' ===  $single_course_layout ) :
				$class = 'edublink-col-lg-9';
			elseif ( 'left' === $single_course_layout ) :
				$class = 'edublink-col-lg-9 edublink-order-1';
			elseif ( 'full_width' === $single_course_layout ) :
				$class = 'edublink-col-lg-12';
			endif;
		endif;

		return $class;
	}
endif;

/**
 * Widget area class
 */
add_filter( 'edublink_widget_area_class', 'edublink_lp_widget_area_class' );

if ( ! function_exists( 'edublink_lp_widget_area_class' ) ) :
	function edublink_lp_widget_area_class ( $class ) {

		if ( is_post_type_archive( LP_COURSE_CPT ) || is_tax( 'course_category' ) ) :

			$course_layout = 'full_width';

			if ( 'right' === $course_layout ) :
				$class = 'edublink-col-lg-3';
			elseif ( 'left' === $course_layout ) :
				$class = 'edublink-col-lg-3 edublink-order-2';
			elseif ( 'full_width' === $course_layout ) :
				$class = '';
			endif;
		endif;

		if ( is_singular( LP_COURSE_CPT ) ) :
			
			$single_course_layout = 'full_width';

			if ( 'right' === $single_course_layout ) :
				$class = 'edublink-col-lg-3';
			elseif ( 'left' === $single_course_layout ) :
				$class = 'edublink-col-lg-3 edublink-order-2';
			elseif ( 'full_width' === $single_course_layout ) :
				$class = '';
			endif;
		endif;
		
		return $class;

	}
endif;

/**
 * Sale tag for promotional courses
 */
if ( ! function_exists( 'edublink_lp_course_sale_tag' ) ) :
	function edublink_lp_course_sale_tag() {

		$course = LP_Global::course();
		if ( $course->get_origin_price() != $course->get_price() ) :
			printf( '<span class="label">%s</span>', apply_filters( 'edublink_course_sale_tag_text', __( 'Sale', 'edublink' ) ) );
		endif;
	}
endif;

/**
 * Sale percentage tag for promotional courses
 */
if ( ! function_exists( 'edublink_lp_course_sale_offer_in_percentage' ) ) :
	function edublink_lp_course_sale_offer_in_percentage() {

		$course = LP_Global::course();
		$discount = round( 100 * ($course->get_origin_price() - $course->get_price()) / $course->get_origin_price() );
		$offer = apply_filters( 'edublink_course_sale_offer_text', __( 'Off', 'edublink' ) );
		return $discount.'%' . ' ' . $offer;
	}
endif;

/**
 * Add html span tag to wrap decimal separator.
 */
if ( ! function_exists( 'edublink_lp_course_price_decimal_separator' ) ) :
	function edublink_lp_course_price_decimal_separator( $origin_price ) {
		$decimal_number    = intval( LP()->settings->get( 'number_of_decimals' ) );
		$decimal_separator = LP()->settings->get( 'decimals_separator' );

		if ( $decimal_number > 0 && ! empty( $decimal_separator ) ) :
			$decimal_position = strpos( $origin_price, $decimal_separator );
			$decimal_part = substr( $origin_price, $decimal_position, $decimal_number + 1 );
			$decimal_html = '<span class="decimal-separator">' . $decimal_part . '</span>';
			$origin_price = str_replace( $decimal_part, $decimal_html, $origin_price );
		endif;
		return $origin_price;
	}
endif;

/**
 * Right Side Content
 */
if ( ! function_exists( 'edublink_lp_course_content_sidebar' ) ) :
	function edublink_lp_course_content_sidebar() {
		$course = LP_Global::course();
		$style = edublink_set_value( 'lp_course_details_style', '1' );
		$preview_thumb = edublink_set_value( 'lp_course_preview_thumb', true );
		$heading_status = edublink_set_value( 'lp_course_sidebar_heading_students', true );
		$button_status = edublink_set_value( 'lp_course_sidebar_button', true );
		$social_share_status = edublink_set_value( 'lp_course_sidebar_social_share', true );
		$heading = edublink_set_value( 'lp_course_sidebar_heading_text', __( 'Course Includes:', 'edublink') );
		$extra_class = $preview_thumb ? 'enable' : 'disable';

		if ( isset( $_GET['course_details'] ) ) :
			$style = in_array( $_GET['course_details'], array( 1, 2, 3, 4, 5, 6 ) ) ? $_GET['course_details'] : 1;
		endif;

		echo '<div class="edublink-course-details-sidebar eb-course-single-' . esc_attr( $style ) . ' sidebar-' . esc_attr( $extra_class ) . '">';
			echo '<div class="edublink-course-details-sidebar-inner">';
				if ( $preview_thumb && '4' != $style ) :
					edublink_lp_course_preview();
				endif;

				echo '<div class="edublink-course-details-sidebar-content">';
					if ( $heading_status && $heading ) :
						echo '<h4 class="widget-title">' . esc_html( $heading ). '</h4>';
					endif;

					edublink_lp_course_meta_data();

					do_action( 'edublink_lp_course_sidebar_after_meta' );

					if ( $button_status ) :
						echo '<div class="edublink-course-details-sidebar-buttons">';
							LearnPress::instance()->template( 'course' )->course_buttons();
						echo '</div>';
					endif;

					do_action( 'edublink_lp_course_sidebar_after_button' );

					if ( $social_share_status ) :
						$social_heading = edublink_set_value( 'lp_course_sidebar_social_share_heading', __( 'Share On:', 'edublink') );
						echo '<div class="edublink-single-event-social-share">';
							echo '<h4 class="share-title">' . esc_html( $social_heading ) . '</h4>';
							get_template_part( 'template-parts/social', 'share' );
						echo '</div>';
					endif;

					do_action( 'edublink_lp_course_sidebar_after_social_share' );
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
endif;

/**
 * Right Side Course Preview
 */
if ( ! function_exists( 'edublink_lp_course_preview' ) ) :
	function edublink_lp_course_preview() {
		$preview_video = get_post_meta( get_the_ID(), 'edublink_lp_course_preview_video_link', true );
		$preview_image = get_post_meta( get_the_ID(), 'edublink_lp_course_preview_image', true );
		$video_status = edublink_set_value( 'lp_course_preview_video_popup', true );

		if ( empty( $preview_image ) ) :
			$preview_image = apply_filters( 'edublink_lp_course_default_preview_image', esc_url( get_template_directory_uri() . '/assets/images/course-preview.jpg' ) );
		endif;
		echo '<div class="edublink-course-details-card-preview" style="background-image: url(' . esc_url( $preview_image ) . ')">';
			if ( $video_status ) :
				echo '<div class="edublink-course-video-preview-area">';
					if ( ! empty( $preview_video ) ) :
						echo '<a data-fancybox href="' . esc_url( $preview_video ) . '" class="edublink-course-video-popup">';
							echo '<i class="icon-18"></i>';
						echo '</a>';
					endif;
				echo '</div>';
			endif;
		echo '</div>';
	}
endif;

/**
 * Right Side Meta Data
 */
if ( ! function_exists( 'edublink_lp_course_meta_data' ) ) :
	function edublink_lp_course_meta_data() {
		$course        = \LP_Global::course();
		$enrolled      = $course->get_users_enrolled();
		$lessons       = $course->get_curriculum_items( 'lp_lesson' ) ? count( $course->get_curriculum_items( 'lp_lesson' ) ) : 0;
		$quiz          = $course->get_curriculum_items( 'lp_quiz' ) ? count( $course->get_curriculum_items( 'lp_quiz' ) ) : 0;
		$pass_mark     = get_post_meta( get_the_ID(), '_lp_passing_condition', true );
		$level         = get_post_meta( get_the_ID(), '_lp_level', true);
		$skill         = $level ? $level : __( 'All Levels', 'edublink');
		$certificate   = 'on' === get_post_meta( get_the_ID(), 'edublink_lp_course_certificate', true ) ? __( 'Yes', 'edublink' ) : __( 'No', 'edublink' );	
		$language      = get_post_meta( get_the_ID(), 'edublink_lp_course_language', true );
		$duration_main = get_post_meta( get_the_ID(), '_lp_duration', true );
		$duration      = edublink_lp_course_duration_customize( $duration_main );
		$extra_meta = get_post_meta( get_the_ID(), 'edublink_lp_course_extra_meta_fields', true ); 

		echo '<ul class="edublink-course-meta-informations">';
			do_action( 'edublink_lp_course_meta_before' );

			if ( edublink_set_value( 'lp_course_sidebar_price_status', true ) ) :
				$price_label = edublink_set_value( 'lp_course_sidebar_price_label' ) ? edublink_set_value( 'lp_course_sidebar_price_label' ) : __( 'Price:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-price">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-60"></i>';
						echo esc_html( $price_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						LP()->template( 'course' )->courses_loop_item_price();
					echo '</span>';
				echo '</li>';
			endif;

			if ( edublink_set_value( 'lp_course_instructor', true ) ) :
				$instructor_label = edublink_set_value( 'lp_course_instructor_label' ) ? edublink_set_value( 'lp_course_instructor_label' ) : __( 'Instructor:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-instructor">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-62"></i>';
						echo esc_html( $instructor_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo wp_kses_post( get_the_author() );
					echo '</span>';
				echo '</li>';
			endif;

			if ( ! empty( $duration ) && edublink_set_value( 'lp_course_duration', true ) ) :
				$duration_label = edublink_set_value( 'lp_course_duration_label' ) ? edublink_set_value( 'lp_course_duration_label' ) : __( 'Duration:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-duration">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-61"></i>';
						echo esc_html( $duration_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo esc_html( $duration );
					echo '</span>';
				echo '</li>';
			endif;

			if ( edublink_set_value( 'lp_course_lessons', true ) ) :
				$lessons_label = edublink_set_value( 'lp_course_lessons_label' ) ? edublink_set_value( 'lp_course_lessons_label' ) : __( 'Lessons:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-lesson">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/icons/books.svg' ) . '" class="edublink-course-sidebar-img-icon">';
						echo esc_html( $lessons_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo esc_html( $lessons );
					echo '</span>';
				echo '</li>';
			endif;

			if ( edublink_set_value( 'lp_course_students', true ) ) :
				$students_label = edublink_set_value( 'lp_course_students_label' ) ? edublink_set_value( 'lp_course_students_label' ) : __( 'Students:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-student">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-63"></i>';
						echo esc_html( $students_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo esc_html( $enrolled );
					echo '</span>';
				echo '</li>';
			endif;

			if ( ! empty( $language ) && edublink_set_value( 'lp_course_language', true ) ) :
				$language_label = edublink_set_value( 'lp_course_language_label' ) ? edublink_set_value( 'lp_course_language_label' ) : __( 'Language:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-language">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-59"></i>';
						echo esc_html( $language_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo esc_html( $language );
					echo '</span>';
				echo '</li>';
			endif;

			if ( ! empty( $certificate ) && edublink_set_value( 'lp_course_certificate', true ) ) :
				$certificate_label = edublink_set_value( 'lp_course_certificate_label' ) ? edublink_set_value( 'lp_course_certificate_label' ) : __( 'Certifications:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-certificate">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-64"></i>';
						echo esc_html( $certificate_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo esc_html( $certificate );
					echo '</span>';
				echo '</li>';
			endif;

			if ( isset( $extra_meta ) && is_array( $extra_meta ) ) :
				foreach ( $extra_meta as $key => $meta ) :
					if ( $meta['label'] ) :
						$wrapper_class = '';
						if ( isset( $meta['wrapper_class'] ) && ! empty( $meta['wrapper_class'] ) ) :
							$wrapper_class = ' ' . $meta['wrapper_class'];
						endif;
						echo '<li class="edublink-course-details-features-item' . esc_attr( $wrapper_class ) . '">';
							echo '<span class="edublink-course-feature-item-label">';
								if (  isset( $meta['icon_class'] ) && ! empty( $meta['icon_class'] ) ) :
									echo '<i class="' . esc_attr( $meta['icon_class'] ) . '"></i>';
								else :
									echo '<i class="ri-check-fill"></i>';
								endif;
								echo $meta['label'] ? esc_html( $meta['label'] ) : '';
							echo '</span>';

							echo $meta['value'] ? '<span class="edublink-course-feature-item-value">' . esc_html( $meta['value'] ) . '</span>' : '';
						echo '</li>';
					endif;
				endforeach;
			endif;

			do_action( 'edublink_lp_course_meta_after' );
		echo '</ul>';
	}
endif;

/**
 * Course instructor
 */
if ( ! function_exists( 'edublink_lp_course_instructor' ) ) :
	function edublink_lp_course_instructor( $thumb_size = 60 ) {
		echo '<div class="course-author" itemscope="" itemtype="http://schema.org/Person">';
			printf( get_avatar( get_the_author_meta( 'ID' ), $thumb_size ) );	
			echo '<div class="author-contain">';
				echo '<label itemprop="jobTitle">' . __( 'Teacher', 'edublink' ) . '</label>';
				echo '<div class="value" itemprop="name">';
					the_author();
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
endif;

/**
 * Course category
 */
if ( ! function_exists( 'edublink_lp_course_first_category' ) ) :
	function edublink_lp_course_first_category() {
		$first_cat = edublink_category_by_id( get_the_id(), 'course_category' );
		if ( ! empty( $first_cat) ) :
			echo '<div class="course-categories">';
				echo '<label>' . __( 'Categories', 'edublink' ) . '</label>';
				echo '<div class="value">';
					echo '<span class="cat-links">';
						echo wp_kses_post( $first_cat );
					echo '</span>';
				echo '</div>';
			echo '</div>';
		endif;
	}
endif;

/**
 * Display course ratings
 */
if ( ! function_exists( 'edublink_lp_course_ratings' ) ) :
	function edublink_lp_course_ratings() {
		if ( ! class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
			return;
		endif;

		$course_rate_res = learn_press_get_course_rate( get_the_ID(), false );
		$course_rate     = $course_rate_res['rated'];
		$total           = $course_rate_res['total'];
		$ratings         = learn_press_get_course_rate_total( get_the_ID() );
		$single_rating_text = edublink_set_value( 'text_for_rating' ) ? edublink_set_value( 'text_for_rating', __( 'Rating', 'edublink' ) ) : __( 'Rating', 'edublink' );
		$plural_rating_text = edublink_set_value( 'text_for_ratings' ) ? edublink_set_value( 'text_for_ratings', __( 'Ratings', 'edublink' ) ) : __( 'Ratings', 'edublink' );
		
		echo '<div class="edublink-course-review-wrapper">';
			learn_press_course_review_template( 'rating-stars.php', array( 'rated' => $course_rate ) );

			echo '<span>';
				echo esc_html( '(' . number_format( $course_rate, 1 ) ) . '/ ';
				echo esc_html( $ratings ) . ' ';
				if ( (int)$ratings > 1 ) :
					echo esc_html( $plural_rating_text );
				else :
					echo esc_html( $single_rating_text );
				endif;
			echo ')</span>';
		echo '</div>';
	}
endif;

/**
 * Display course ratings alter
 */
if ( ! function_exists( 'edublink_lp_course_ratings_alter' ) ) :
	function edublink_lp_course_ratings_alter( $show_rating = false ) {
		if ( ! class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
			return;
		endif;

		$course_rate_res = learn_press_get_course_rate( get_the_ID(), false );
		$course_rate     = $course_rate_res['rated'];
		$total           = $course_rate_res['total'];
		$ratings         = learn_press_get_course_rate_total( get_the_ID() );
		echo '<div class="edublink-course-review-wrapper">';
			learn_press_course_review_template( 'rating-stars.php', array( 'rated' => $course_rate ) );

			if ( $show_rating ) :
				echo '<span>';
					echo esc_html( '(' . number_format( $course_rate, 1 ) . ')' );
				echo '</span>';
			else :
				echo '<span>';
					printf( _nx( '(%s Review)', '(%s Reviews)', $ratings, 'Ratings', 'edublink' ), number_format_i18n( $ratings ) );
				echo '</span>';
			endif;
		echo '</div>';
	}
endif;

/**
 * Display course rating value only
 */
if ( ! function_exists( 'edublink_lp_course_rating_value' ) ) :
	function edublink_lp_course_rating_value() {
		if ( ! class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
			return;
		endif;

		$course_rate_res = learn_press_get_course_rate( get_the_ID(), false );
		$course_rate     = $course_rate_res['rated'];
		$total           = $course_rate_res['total'];
		$ratings         = learn_press_get_course_rate_total( get_the_ID() );
		return number_format( $course_rate, 1 );
	}
endif;

/**
 * Generate wishlist icon
 */
if ( ! function_exists( 'edublink_lp_wishlist_icon' ) ) :
	function edublink_lp_wishlist_icon( $course_id ){
		$user_id = get_current_user_id();

		if ( ! class_exists( 'LP_Addon_Wishlist' ) || ! $course_id ) :
			return;
		endif;

		if ( ! $user_id ) :
			echo '<button class="edublink-wishlist-wrapper edublink-lp-non-logged-user"></button>';
			return;
		endif;

		$classes = array( 'course-wishlist' );
		$state   = learn_press_user_wishlist_has_course( $course_id, $user_id ) ? 'on' : 'off';

		if ( 'on' === $state ) :
			$classes[] = 'on';
		endif;
		$classes = apply_filters( 'learn_press_course_wishlist_button_classes', $classes, $course_id );
		$title   = ( 'on' === $state ) ? __( 'Remove this course from your wishlist', 'edublink' ) : __( 'Add this course to your wishlist', 'edublink' );

		printf(
			'<button class="edublink-wishlist-wrapper learn-press-course-wishlist-button-%2$d %s" data-id="%s" data-nonce="%s" title="%s"></button>',
			join( " ", $classes ),
			$course_id,
			wp_create_nonce( 'course-toggle-wishlist' ),
			$title
		);	

	}
endif;

/**
 * Related Courses
 */
if ( ! function_exists( 'edublink_lp_related_courses' ) ) :
	function edublink_lp_related_courses() {
		$related_courses = edublink_set_value( 'lp_related_courses', true );
		if ( isset( $_GET['disable_related_courses'] ) ) :
			$related_courses = false;
		endif;
		
		if ( $related_courses ) :
			learn_press_get_template( 'custom/courses-related.php' );
		endif;
	}
endif;

/**
 * Curriculum section title
 */
if ( ! function_exists( 'edublink_lp_curriculum_section_title' ) ) :
	function edublink_lp_curriculum_section_title( $section ) {
		learn_press_get_template( 'custom/curriculum-title.php', array( 'section' => $section ) );
	}
endif;

/**
 * LearnPress Course
 * @return boolean
 */
function edublink_is_lp_courses() {
    if ( learn_press_is_courses() || learn_press_is_course_tag() || learn_press_is_course_category() || learn_press_is_course_tax() || learn_press_is_search() ) :
        return true;
    endif;
    return false;
}

/**
 * LP breadcrumb delimiter
 */

add_filter( 'learn_press_breadcrumb_defaults', 'edublink_lp_breadcrumb_delimiter' );

if( ! function_exists( 'edublink_lp_breadcrumb_delimiter' ) ) :
	function edublink_lp_breadcrumb_delimiter( $args ) {
		$args['delimiter'] = '';
		return $args;
	}
endif;

/**
 * indexing result of courses
 */
if( ! function_exists( 'edublink_lp_course_index_result' ) ) :
	function edublink_lp_course_index_result( $total ) {
		if ( 0 === $total ) :
			$result = __( 'There are no available courses!', 'edublink' );	
		elseif ( 1 === $total ) :
			$result = __( 'Showing only one result.', 'edublink' );
		else :
			$courses_per_page = absint( LP()->settings->get( 'archive_course_limit' ) );
			$paged = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;

			$from = 1 + ( $paged - 1 ) * $courses_per_page;
			$to   = ( $paged * $courses_per_page > $total ) ? $total : $paged * $courses_per_page;

			if ( $from == $to ) :
				$result = sprintf( __( 'Showing Last Course Of %s Results', 'edublink' ), $total );
			else :
				$result = sprintf( __( 'Showing %s-%s Of %s Results', 'edublink' ), '<span>' . $from, $to . '</span>', '<span>' . $total . '</span>' );
			endif;
		endif;
		echo wp_kses_post( $result );
	}
endif;

/**
 * Course archive top bar
 */
if( ! function_exists( 'edublink_lp_course_header_top_bar' ) ) :
	function edublink_lp_course_header_top_bar( $query ) {
		global $wp_query;
		$top_bar      = edublink_set_value( 'lp_course_archive_top_bar', true );
		$index      = edublink_set_value( 'lp_course_index', true );
		$search_bar = edublink_set_value( 'lp_course_search_bar', true );

		if ( true == $index && true == $search_bar ) :
			$column = 'edublink-col-md-6';
		else :
			$column = 'edublink-col-md-12';
		endif;

		if ( ( true == $top_bar ) && ( true == $index || true == $search_bar ) ) :
			echo '<div class="edublink-course-archive-top-bar-wrapper">';
				echo '<div class="edublink-course-archive-top-bar edublink-row">';
					if ( true == $index ) :
						echo '<div class="' . esc_attr( $column ) . '">';
							echo '<span class="edublink-course-archive-index-count">';
								edublink_lp_course_index_result( $query->found_posts );
							echo '</span>';
						echo '</div>';
					endif;
					if ( true == $search_bar ) :
						echo '<div class="' . esc_attr( $column ) . '">';
							echo '<div class="edublink-course-archive-search">';
								edublink_lp_course_archive_search_bar();
							echo '</div>';
						echo '</div>';
					endif;
				echo '</div>';
			echo '</div>';
		endif;
	}
endif;

/**
 * Course archive search bar
 */
if( ! function_exists( 'edublink_lp_course_archive_search_bar' ) ) :
	function edublink_lp_course_archive_search_bar() {
		/*
		 * remove param action="' . esc_url( get_post_type_archive_link( LP_COURSE_CPT ) ) . '"
		 * if you don't want to redirect to course category archive
		 */
		echo '<div class="edu-search-box">';
			echo '<form class="edublink-archive-course-search-form" method="get" action="' . esc_url( get_post_type_archive_link( LP_COURSE_CPT ) ) . '">';
				echo '<input type="text" value="" name="search_query" placeholder="'. __( 'Search Courses...', 'edublink' ) . '" class="input-search" autocomplete="off" />';
				echo '<input type="hidden" value="lp_course_search" name="eb_lp_course_filter" />';
				echo '<button class="search-button"><i class="icon-2"></i></button>';
			echo '</form>';
		echo '</div>';
	}
endif;

/**
 * Main Content Wrapper Class for LearnPress 
 * Course Archive & Course Details
 */
add_filter( 'edublink_main_content_inner', 'edublink_lp_main_content_wrapper_class' );
if( ! function_exists( 'edublink_lp_main_content_wrapper_class' ) ) :
	function edublink_lp_main_content_wrapper_class( $class ) {
		if ( learn_press_is_courses() || learn_press_is_course_tag() || learn_press_is_course_category() || learn_press_is_course_tax() || learn_press_is_search() ) :
			$class = '';
		elseif ( is_singular( LP_COURSE_CPT ) ) :
			$class = ' edublink-row';
		endif;
		return $class;
	}
endif;

/**
 * Remove and Modify Tab Items From 
 * LearnPress Course Details Page
 */
add_filter( 'learn-press/course-tabs', 'edublink_lp_instructor_tab_modify' );
if( ! function_exists( 'edublink_lp_instructor_tab_modify' ) ) :
	function edublink_lp_instructor_tab_modify( $tabs ) {
		$overview_tab_title = edublink_set_value( 'lp_overview_tab_title', __( 'Overview', 'edublink' ) );
		$instructor_tab_title = edublink_set_value( 'lp_instructor_tab_title', __( 'Instructor', 'edublink' ) );
		$curriculum_tab_title = edublink_set_value( 'lp_curriculum_tab_title', __( 'Curriculum', 'edublink' ) );
		$faq_tab_title = edublink_set_value( 'lp_faq_tab_title', __( 'FAQs', 'edublink' ) );
		$reviews_tab_title = edublink_set_value( 'lp_reviews_tab_title', __( 'Reviews', 'edublink' ) );
		$instructor_tab = edublink_set_value( 'lp_instructor_tab', true );
		$curriculum_tab = edublink_set_value( 'lp_curriculum_tab', true );
		$faq_tab = edublink_set_value( 'lp_faq_tab', true );
		$reviews_tab = edublink_set_value( 'lp_reviews_tab', true );

		if ( $overview_tab_title ) :
			$tabs['overview']['title'] = $overview_tab_title;
		endif;

		if ( true == $instructor_tab ) :
			if ( $overview_tab_title ) :
				$tabs['instructor']['title'] = $instructor_tab_title; 
			endif;
		else :
			unset( $tabs['instructor'] );
		endif;

		if ( true == $curriculum_tab ) :
			if ( $curriculum_tab_title ) :
				$tabs['curriculum']['title'] = $curriculum_tab_title; 
			endif;
		else :
			unset( $tabs['curriculum'] );
		endif;

		if ( isset( $tabs['faqs'] ) && ! empty( $tabs['faqs'] ) ) :
			if ( true == $faq_tab ) :
				if ( $faq_tab_title ) :
					$tabs['faqs']['title'] = $faq_tab_title; 
				endif;
			else :
				unset( $tabs['faqs'] );
			endif;
		endif;

		if ( class_exists( 'LP_Addon_Course_Review_Preload' ) ) :
			if ( true == $reviews_tab ) :
				if ( $overview_tab_title ) :
					$tabs['reviews']['title'] = $reviews_tab_title; 
				endif;
			else :
				unset( $tabs['reviews'] );
			endif;
		endif;

		return $tabs;
	}
endif;

/**
 * Course Taxonomy Archive Page Query
 * Only for Category( 'course_category' ) and 
 * Tag( 'course_tag' ) Archive Pages
 */
add_filter( 'edublink_lp_course_archive_args', 'edublink_lp_course_taxonomy_filter_archive' );
if( ! function_exists( 'edublink_lp_course_taxonomy_filter_archive' ) ) :
	function edublink_lp_course_taxonomy_filter_archive( $args ) {
		$category = get_queried_object();
		if ( learn_press_is_course_archive() ) :
			if ( isset( $category->taxonomy ) && 'course_category' === $category->taxonomy ) :
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'course_category',
						'field'    => 'term_id',
						'terms'    => array( $category->term_id )
					)
				);
			elseif ( isset( $category->taxonomy ) && 'course_tag' === $category->taxonomy ) :
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'course_tag',
						'field'    => 'term_id',
						'terms'    => array( $category->term_id )
					)
				);
			endif;
		endif;
		return $args;
	}
endif;

/**
 * Course Archive Search Filter
 */
add_filter( 'edublink_lp_course_archive_args', 'edublink_lp_course_search_filter_archive' );
if( ! function_exists( 'edublink_lp_course_search_filter_archive' ) ) :
	function edublink_lp_course_search_filter_archive( $args ) {
		if ( learn_press_is_course_archive() ) :
			if ( isset( $_REQUEST['eb_lp_course_filter'] ) && 'lp_course_search' === $_REQUEST['eb_lp_course_filter'] ) :
				$args['s'] = sanitize_text_field( $_REQUEST['search_query'] );
			endif;
		endif;
		return $args;
	}
endif;

/**
 * Course Archive Main Filter
 */
add_filter( 'edublink_lp_course_archive_args', 'edublink_lp_course_category_filter_archive' );
if( ! function_exists( 'edublink_lp_course_category_filter_archive' ) ) :
	function edublink_lp_course_category_filter_archive( $args ) {
		if ( learn_press_is_course_archive() ) :
			if ( ! empty( $_GET['filter-category'] ) ) :
				if ( is_array( $_GET['filter-category'] ) ) :
					$args['tax_query'] = array(
						array(
						'taxonomy'  => 'course_category',
						'field'     => 'term_id',
						'terms'     => array_map( 'sanitize_text_field', $_GET['filter-category'] ),
						'compare'   => 'IN'
						)
					);
				else :
					$args['tax_query'] = array(
						array(
							'taxonomy'  => 'course_category',
							'field'     => 'term_id',
							'terms'     => sanitize_text_field( $_GET['filter-category'] ),
							'compare'   => '=='
						)
					);
				endif;
			endif;

			if ( ! empty( $_GET['filter-level'] ) ) :
				if ( is_array( $_GET['filter-level'] ) ) :
					$args['meta_query'][] = array(
						'key'     => '_lp_level',
						'value'   => array_map( 'sanitize_text_field', $_GET['filter-level'] ),
						'compare' => 'IN'
					);
				else :
					$args['meta_query'][] = array(
						'key'     => '_lp_level',
						'value'   => sanitize_text_field( $_GET['filter-level'] ),
						'compare' => '='
					);
				endif;
            endif;
        endif;
		return $args;
	}
endif;

/**
 * Course Duration
 *
 */
if( ! function_exists( 'edublink_lp_course_duration_customize' ) ) :
	function edublink_lp_course_duration_customize( $duration ) {
		$duration_number = absint( $duration );
		$duration_text = str_replace( $duration_number, '', $duration );
		$duration_text = trim( $duration_text );

		switch ( $duration_text ) :
			case 'minute':
				$duration_text = $duration_number > 1 ? __( 'minutes', 'edublink' ) : __( 'minute', 'edublink' );
				break;
			case 'hour':
				$duration_text = $duration_number > 1 ? __( 'hours', 'edublink' ) : __( 'hour', 'edublink' );
				break;
			case 'day':
				$duration_text = $duration_number > 1 ? __( 'days', 'edublink' ) : __( 'day', 'edublink' );
				break;
			case 'week':
				$duration_text = $duration_number > 1 ? __( 'weeks', 'edublink' ) : __( 'week', 'edublink' );
				break;
		endswitch;
		return $duration_number . ' ' . $duration_text;
	}
endif;

/**
 * LearnPress External Button Text
 *
 */
add_filter( 'learn-press/course-external-link-text', 'edublink_lp_external_link_text' );
function edublink_lp_external_link_text( $default ) {
	$text = edublink_set_value( 'eb_lp_external_link_text' );
	return $text ? $text : $default;
}

/**
 * LearnPress Purchase Button Text
 *
 */
add_filter( 'learn-press/purchase-course-button-text', 'edublink_lp_course_purchase_button_text' );
function edublink_lp_course_purchase_button_text( $default ) {
	$text = edublink_set_value( 'eb_lp_purchase_button_text' );
	return $text ? $text : $default;
}

/**
 * LearnPress Enroll Button Text
 *
 */
add_filter( 'learn-press/enroll-course-button-text', 'edublink_lp_course_enroll_button_text' );
function edublink_lp_course_enroll_button_text( $default ) {
	$text = edublink_set_value( 'eb_lp_enroll_button_text' );
	return $text ? $text : $default;
}

/**
 * LearnPress Course Details Header
 *
 */
if( ! function_exists( 'edublink_lp_course_details_header' ) ) :
	function edublink_lp_course_details_header( $style ) {
		switch ( $style ):
			case 1:
				edublink_lp_course_details_header_default_style();
				break;
			case 2:
				edublink_lp_course_details_header_default_style( 'dark-version' );
				break;
			case 3:
				edublink_lp_course_details_header_default_style();
				break;
			case 4:
				edublink_lp_course_details_header_style_2();
				break;
			case 5:
				edublink_lp_course_details_header_default_style( 'style-5' );
				break;
			case 6:
				edublink_lp_course_details_header_default_style( 'style-6' );
				break;
			default:
			edublink_lp_course_details_header_default_style();
		endswitch;
	}
endif;

/**
 * LearnPress Course Details Header Default Style
 *
 */
if( ! function_exists( 'edublink_lp_course_details_header_default_style' ) ) :
	function edublink_lp_course_details_header_default_style( $style = null ) {
		$style = $style ? ' ' . esc_attr( $style ) : '';
		echo '<div class="edublink-course-page-header edublink-course-page-header' . esc_attr( $style ) . '">';
			echo '<div class="eb-course-header-breadcrumb">';
				echo '<div class="' . esc_attr( apply_filters( 'edublink_breadcrumb_container_class', 'edublink-container' ) ) . '">';
					do_action( 'edublink_breadcrumb' );
				echo '</div>';
			echo '</div>';

			echo '<div class="eb-course-header-breadcrumb-content">';
				echo '<div class="' . esc_attr( apply_filters( 'edublink_breadcrumb_container_class', 'edublink-container' ) ) . '">';
					echo '<div class="edublink-course-breadcrumb-inner">';
						echo '<div class="edublink-course-title">';
							echo '<h1 class="entry-title">';
								the_title(); 
							echo '</h1>';
						echo '</div>';
						
						echo '<div class="edublink-course-header-meta">';
							edublink_breadcrumb_lp_course_meta();
						echo '</div>';
					echo '</div>';
				echo '</div>';
				if ( ' style-6' === $style  ) :
					edublink_course_breadcrumb_header_6_shapes();
				endif;
			echo '</div>';
			
			if ( ' style-6' !== $style ) :
				edublink_breadcrumb_shapes();
			endif;
		echo '</div>';
	}
endif;

/**
 * LearnPress Course Details Header Style 2
 *
 */
if( ! function_exists( 'edublink_lp_course_details_header_style_2' ) ) :
	function edublink_lp_course_details_header_style_2() {
		$has_bg_image = '';
		$breadcrumb_img   = edublink_set_value( 'lp_course_breadcrumb_image' );
		$title = get_the_title();
		$style = array();
		
		if ( isset( $breadcrumb_img['url'] ) && ! empty( $breadcrumb_img['url'] ) ) :
			$style[] = 'background-image:url(\'' . esc_url( $breadcrumb_img['url'] ) . '\' )';
			$has_bg_image = 'edublink-breadcrumb-has-bg course-header-4';
		else :
			$has_bg_image = 'edublink-breadcrumb-empty-bg course-header-4';
		endif;

		$extra_style = ! empty( $style ) ? ' style="' . implode( "; ", $style ) . '"' : "";

		edublink_breadcrumb_style_1( $title, $has_bg_image, $extra_style );
	}
endif;

/**
 * LearnPress Course Breaecrumb Meta
 *
 */
if( ! function_exists( 'edublink_breadcrumb_lp_course_meta' ) ) :
	function edublink_breadcrumb_lp_course_meta() {
		$category = edublink_category_by_id( get_the_ID(), 'course_category' );
		echo '<ul class="eb-course-header-meta-items">';
			echo '<li class="instructor">';
				echo '<i class="icon-58"></i>';
				_e( 'By', 'edublink' );
				echo ' ';
				the_author();
			echo '</li>';

			if ( $category ) :
				echo '<li class="category"><i class="icon-59"></i>' . wp_kses_post( $category ) . '</li>';
			endif;

			echo '<li class="rating">';
				edublink_lp_course_ratings_alter();
			echo '</li>';
		echo '</ul>';
	}
endif;

/**
 * LearnPress Course Details 
 * Header Style 6 Shapes
 *
 */
if( ! function_exists( 'edublink_course_breadcrumb_header_6_shapes' ) ) :
	function edublink_course_breadcrumb_header_6_shapes() {
		$status = apply_filters( 'edublink_breadcrumb_shape', true );

		if ( $status ) :
			echo '<div class="shape-dot-wrapper shape-wrapper edublink-d-xl-block edublink-d-none">';
				echo '<div class="shape-image eb-mouse-animation shape-a">';
					echo '<span data-depth="2">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/shapes/breadcrumb-shape-1.png' ) . '" alt="Breadcrumb Abstract Shape">';
					echo '</span>';
				echo '</div>';

				echo '<div class="shape-image shape-b">';
					echo '<span>';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/shapes/breadcrumb-shape-4.png' ) . '" alt="Breadcrumb Abstract Shape">';
					echo '</span>';
				echo '</div>';

				echo '<div class="shape-image eb-mouse-animation shape-c">';
					echo '<span data-depth="2">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/shapes/breadcrumb-shape-5.png' ) . '" alt="Breadcrumb Abstract Shape">';
					echo '</span>';
				echo '</div>';

				echo '<div class="shape-image eb-mouse-animation shape-d">';
					echo '<span data-depth="-2">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/shapes/breadcrumb-shape-6.png' ) . '" alt="Breadcrumb Abstract Shape">';
					echo '</span>';
				echo '</div>';
			echo '</div>';
		endif;
	}
endif;

/**
 * Enable templates override
 *
 * @return bool
 * @since 4.0.0
 */

add_filter( 'learn-press/override-templates', 'edublink_lp_override_action' );
if( ! function_exists( 'edublink_lp_override_action' ) ) :
	function edublink_lp_override_action() {
		return true;
	}
endif;
