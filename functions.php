<?php


require_once get_template_directory() . '/learndash/custom/review/class-review.php';

require_once get_template_directory() . '/learndash/custom/helper-class.php';

add_filter( 'edublink_currency_symbols', 'edublink_ld_course_currency_symbols' );

add_action( 'pre_get_posts', 'edublink_ld_custom_query_for_author' );
if ( ! function_exists( 'edublink_ld_custom_query_for_author' ) ) :
	function edublink_ld_custom_query_for_author( $query ) {
		$author_redirect_to_courses = apply_filters( 'edublink_ld_author_redirect_to_course', false );
	    if ( is_admin() || ! $query->is_main_query() ) :
	        return;
		endif;

		if ( isset( $_GET['ldauthor'] ) ) :
			$ldauthor = $_GET['ldauthor'];
		else :
			$ldauthor = false;
		endif;

		if ( is_author() && ( 'true' == $ldauthor ) && ( true == $author_redirect_to_courses ) ) :
	        $query->set( 'post_type', array( 'sfwd-courses' ) );
	    endif;
	}
endif;

/**
 * Course Archive Item Per Page
 */
add_action( 'pre_get_posts', 'edublink_archive_course_item_per_page', 15 );
if ( ! function_exists( 'edublink_archive_course_item_per_page' ) ) :
	function edublink_archive_course_item_per_page( $query ) {

		$item = edublink_set_value( 'ld_course_archive_page_items' ) ? edublink_set_value( 'ld_course_archive_page_items' ) : 9;

		if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'sfwd-courses' ) ) :
			$query->set( 'posts_per_page', $item );
		endif;
		
		return;
	}
endif;

/**
 * LearnDash specific scripts & stylesheets.
 *
 * @return void
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'edublink_ld_scripts' ) ) :
	function edublink_ld_scripts() {
		wp_enqueue_style( 'edublink-ld-style', get_template_directory_uri() . '/assets/css/learndash.css', array(), EDUBLINK_THEME_VERSION );
		if ( is_singular( 'sfwd-courses' ) ) :
			wp_enqueue_style( 'jquery-fancybox' );
			wp_enqueue_script( 'jquery-fancybox' );
		endif;
	}
endif;
add_action( 'wp_enqueue_scripts', 'edublink_ld_scripts' );

/**
 * post_class extends for learndash courses
 * 
 * @since 1.0.0
 */
if ( ! function_exists( 'edublink_ld_course_class' ) ) :
    function edublink_ld_course_class( $default = array() ) {
		$terms      = get_the_terms( get_the_ID(), 'ld_course_category' );
		$terms_html = array();
		if ( is_array( $terms ) ) :
			foreach ( $terms as $term ) :
				$terms_html[] = $term->slug;
			endforeach;
		endif;
		$all_classes = array_merge( $terms_html, $default );
		$classes = apply_filters( 'edublink_ld_course_class', $all_classes );
        post_class( $classes );
    }
endif;

/**
 * Content area class
 */
add_filter( 'edublink_content_area_class', 'edublink_ld_content_area_class' );

if ( ! function_exists( 'edublink_ld_content_area_class' ) ) :
	function edublink_ld_content_area_class ( $class ) {

		if ( is_post_type_archive( 'sfwd-courses' ) || is_tax( 'ld_course_category' ) || is_tax( 'ld_course_tag' ) ) :

			$course_layout = 'full_width';

			if ( 'right' === $course_layout ) :
				$class = 'edublink-col-lg-9';
			elseif ( 'left' === $course_layout ) :
				$class = 'edublink-col-lg-9 edublink-order-1';
			elseif ( 'full_width' === $course_layout ) :
				$class = 'edublink-col-lg-12';
			endif;
		endif;

		if ( is_singular( 'sfwd-courses' ) ) :
			
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
 * Content area class for Author( As Instructor ) Archive
 */
add_filter( 'edublink_content_area_class', 'edublink_ld_author_archive_content_area_class' );

if ( ! function_exists( 'edublink_ld_author_archive_content_area_class' ) ) :
	function edublink_ld_author_archive_content_area_class ( $class ) {
		$author_redirect_to_courses = apply_filters( 'edublink_ld_author_redirect_to_course', false );
		if ( isset( $_GET['ldauthor'] ) ) :
			$ldauthor = $_GET['ldauthor'];
		else :
			$ldauthor = false;
		endif;
		if ( true == $author_redirect_to_courses && 'true' == $ldauthor ) :
			$class = 'edublink-col-lg-12';
		endif;

		return $class;
	}
endif;

/**
 * Archive & Single Sidebar Type
 */
add_filter( 'edublink_archive_sidebar_layout', 'edublink_archive_ld_sidebar_layout' );
add_filter( 'edublink_single_sidebar_layout', 'edublink_archive_ld_sidebar_layout' );

if ( ! function_exists( 'edublink_archive_ld_sidebar_layout' ) ) :
	function edublink_archive_ld_sidebar_layout ( $class ) {
		if ( is_post_type_archive( 'sfwd-courses' ) || is_tax( 'ld_course_category' ) || is_tax( 'ld_course_tag' ) || is_singular( 'sfwd-courses' ) ) :
			$class = 'no-sidebar';
		endif;
		return $class;
	}
endif;

/**
 * Single Course Thumbnail
 */
if ( ! function_exists( 'edublink_ld_single_course_thumbnail' ) ) :
	function edublink_ld_single_course_thumbnail(){
		$thumb_src = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'full' );
		if ( isset( $thumb_src ) && ! empty( $thumb_src ) ) :
		    $thumb_url = $thumb_src[0];
		else :
		    $thumb_url = get_template_directory_uri() . '/assets/images/no-image-found.png';
		endif;
		echo '<div class="edublink-single-course-thumbnail" style="background-image: url(' . esc_url( $thumb_url ) . ')"></div>';
	}
endif;

/**
 * Right Side Course Preview
 */
if ( ! function_exists( 'edublink_ld_course_preview' ) ) :
	function edublink_ld_course_preview() {
		$preview_video = get_post_meta( get_the_ID(), 'edublink_ld_course_preview_video_link', true );
		$preview_image = get_post_meta( get_the_ID(), 'edublink_ld_course_preview_image', true );
		$video_status = edublink_set_value( 'ld_course_preview_video_popup', true );

		if ( empty( $preview_image ) ) :
			$preview_image = apply_filters( 'edublink_ld_course_default_preview_image', esc_url( get_template_directory_uri() . '/assets/images/course-preview.jpg' ) );
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
if ( ! function_exists( 'edublink_ld_course_meta_data' ) ) :
	function edublink_ld_course_meta_data() {
		$enrolled  = get_post_meta( get_the_ID(), 'edublink_ld_course_students', true );
		$pass_mark = get_post_meta( get_the_ID(), 'edublink_ld_course_pass_mark', true );
		$duration  = get_post_meta( get_the_ID(), 'edublink_ld_course_duration', true );
		$access    = get_post_meta( get_the_ID(), 'edublink_ld_course_access', true );
		$language  = get_post_meta( get_the_ID(), 'edublink_ld_course_language', true );
		$deadline  = get_post_meta( get_the_ID(), 'edublink_ld_course_deadline', true );
		$skill     = str_replace( '-', ' ', get_post_meta( get_the_ID(), 'edublink_ld_course_level', true ) );
		$lessons   = learndash_get_course_steps( get_the_ID(), array( 'sfwd-lessons' ) );
		$topics    = learndash_get_course_steps( get_the_ID(), array( 'sfwd-topic' ) );
		$extra_meta = get_post_meta( get_the_ID(), 'edublink_ld_course_extra_meta_fields', true ); 
		$certificate   = 'on' === get_post_meta( get_the_ID(), 'edublink_ld_course_certificate', true ) ? __( 'Yes', 'edublink' ) : __( 'No', 'edublink' );	
		$quiz_args = new wp_Query( array(
			'post_type'  => 'sfwd-quiz',							
			'meta_query' => array(
				array( 
					'key'   => 'course_id', 
					'value' => get_the_ID()
				) 
			)						
		) );

		echo '<ul class="edublink-course-meta-informations">';
			do_action( 'edublink_ld_course_meta_before' );

			if ( edublink_set_value( 'ld_course_sidebar_price_status', true ) ) :
				$price_label = edublink_set_value( 'ld_course_sidebar_price_label' ) ? edublink_set_value( 'ld_course_sidebar_price_label' ) : __( 'Price:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-price">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<i class="icon-60"></i>';
						echo esc_html( $price_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo wp_kses_post( EduBlink_LD_Helper::course_price() );
					echo '</span>';
				echo '</li>';
			endif;

			if ( edublink_set_value( 'ld_course_instructor', true ) ) :
				$instructor_label = edublink_set_value( 'ld_course_instructor_label' ) ? edublink_set_value( 'ld_course_instructor_label' ) : __( 'Instructor:', 'edublink' );
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

			if ( ! empty( $duration ) && edublink_set_value( 'ld_course_duration', true ) ) :
				$duration_label = edublink_set_value( 'ld_course_duration_label' ) ? edublink_set_value( 'ld_course_duration_label' ) : __( 'Duration:', 'edublink' );
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

			if ( edublink_set_value( 'ld_course_lessons', true ) ) :
				$lessons_label = edublink_set_value( 'ld_course_lessons_label' ) ? edublink_set_value( 'ld_course_lessons_label' ) : __( 'Lessons:', 'edublink' );
				echo '<li class="edublink-course-details-features-item course-lesson">';
					echo '<span class="edublink-course-feature-item-label">';
						echo '<img src="' . esc_url( get_template_directory_uri() . '/assets/images/icons/books.svg' ) . '" class="edublink-course-sidebar-img-icon">';
						echo esc_html( $lessons_label );
					echo '</span>';

					echo '<span class="edublink-course-feature-item-value">';
						echo esc_html( count( $lessons ) );
					echo '</span>';
				echo '</li>';
			endif;

			if ( edublink_set_value( 'ld_course_students', true ) && $enrolled ) :
				$students_label = edublink_set_value( 'ld_course_students_label' ) ? edublink_set_value( 'ld_course_students_label' ) : __( 'Students:', 'edublink' );
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

			if ( ! empty( $language ) && edublink_set_value( 'ld_course_language', true ) ) :
				$language_label = edublink_set_value( 'ld_course_language_label' ) ? edublink_set_value( 'ld_course_language_label' ) : __( 'Language:', 'edublink' );
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

			if ( ! empty( $certificate ) && edublink_set_value( 'ld_course_certificate', true ) ) :
				$certificate_label = edublink_set_value( 'ld_course_certificate_label' ) ? edublink_set_value( 'ld_course_certificate_label' ) : __( 'Certifications:', 'edublink' );
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
						$wrapper_class = $meta['wrapper_class'] ? ' ' . $meta['wrapper_class'] : '';
						echo '<li class="edublink-course-details-features-item' . esc_attr( $wrapper_class ) . '">';
							echo '<span class="edublink-course-feature-item-label">';
								if ( $meta['icon_class'] ) :
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

			do_action( 'edublink_ld_course_meta_after' );
		echo '</ul>';
	}
endif;

/**
 * Related Courses
 */
if ( ! function_exists( 'edublink_ld_related_courses' ) ) :
	function edublink_ld_related_courses() {
		$related_courses = edublink_set_value( 'ld_related_courses', true );
		if ( isset( $_GET['disable_related_courses'] ) ) :
			$related_courses = false;
		endif;

		if ( $related_courses ) :
			get_template_part( 'learndash/custom/courses', 'related' );
		endif;
	}
endif;

/**
 * Course Search Post Type
 */
add_filter( 'edublink_course_search_post_type', 'edublink_ld_course_search_post_type' );
if ( ! function_exists( 'edublink_ld_course_search_post_type' ) ) :
	function edublink_ld_course_search_post_type() {
		return 'sfwd-courses';
	}
endif;

/**
 * Header Course Category Slug
 */
add_filter( 'edublink_header_course_lms_cat_slug', 'edublink_header_course_ld_cat_slug' );
if ( ! function_exists( 'edublink_header_course_ld_cat_slug' ) ) :
	function edublink_header_course_ld_cat_slug() {
		return 'ld_course_category';
	}
endif;

/**
 * Count Course Data
 */
if ( ! function_exists( 'edublink_ld_count_published_posts' ) ) :
	function edublink_ld_count_published_posts( $post_type ) {

		$count_posts = wp_count_posts( $post_type );

		if ( $count_posts->publish ) :
			return $count_posts->publish;
		else :
			return 0;
		endif;
	}
endif;

/**
 * Course Archive Search Filter
 */
add_filter( 'edublink_ld_course_archive_args', 'edublink_ld_course_search_filter_archive' );
if( ! function_exists( 'edublink_ld_course_search_filter_archive' ) ) :
	function edublink_ld_course_search_filter_archive( $args ) {
		if ( is_post_type_archive( 'sfwd-courses' ) ) :
			if ( isset( $_REQUEST['eb_ld_course_filter'] ) && 'ld_course_search' === $_REQUEST['eb_ld_course_filter'] ) :
				$args['s'] = sanitize_text_field( $_REQUEST['search_query'] );
			endif;
		endif;
		return $args;
	}
endif;

/**
 * indexing result of courses
 */
if( ! function_exists( 'edublink_ld_course_index_result' ) ) :
	function edublink_ld_course_index_result( $total ) {
		if ( 0 === $total ) :
			$result = __( 'There are no available courses!', 'edublink' );	
		elseif ( 1 === $total ) :
			$result = __( 'Showing only one result.', 'edublink' );
		else :
			$courses_per_page = edublink_set_value( 'ld_course_archive_page_items' ) ? edublink_set_value( 'ld_course_archive_page_items' ) : 9;
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
if( ! function_exists( 'edublink_ld_course_header_top_bar' ) ) :
	function edublink_ld_course_header_top_bar( $query ) {
		global $wp_query;
		$top_bar      = edublink_set_value( 'ld_course_archive_top_bar', true );
		$index      = edublink_set_value( 'ld_course_index', true );
		$search_bar = edublink_set_value( 'ld_course_search_bar', true );

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
								edublink_ld_course_index_result( $query->found_posts );
							echo '</span>';
						echo '</div>';
					endif;
					if ( true == $search_bar ) :
						echo '<div class="' . esc_attr( $column ) . '">';
							echo '<div class="edublink-course-archive-search">';
								edublink_ld_course_archive_search_bar();
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
if( ! function_exists( 'edublink_ld_course_archive_search_bar' ) ) :
	function edublink_ld_course_archive_search_bar() {
		/*
		 * remove param action="' . esc_url( get_post_type_archive_link( 'sfwd-courses ) ) . '"
		 * if you don't want to redirect to course category archive
		 */
		echo '<div class="edu-search-box">';
			echo '<form class="edublink-archive-course-search-form" method="get" action="' . esc_url( get_post_type_archive_link( 'sfwd-courses' ) ) . '">';
				echo '<input type="text" value="" name="search_query" placeholder="'. __( 'Search Courses...', 'edublink' ) . '" class="input-search" autocomplete="off" />';
				echo '<input type="hidden" value="ld_course_search" name="eb_ld_course_filter" />';
				echo '<button class="search-button"><i class="icon-2"></i></button>';
			echo '</form>';
		echo '</div>';
	}
endif;

/**
 * LearnDash Course Wishlist Active
 *
 */
if( ! function_exists( 'is_edublink_ld_wishlist_enable' ) ) :
	function is_edublink_ld_wishlist_enable() {
		$status = edublink_set_value( 'ld_course_wishlist_system', true ) ? edublink_set_value( 'ld_course_wishlist_system', true ) : false;
		return $status;
	}
endif;

/**
 * LearnDash Course Rating Active
 *
 */
if( ! function_exists( 'is_edublink_ld_rating_enable' ) ) :
	function is_edublink_ld_rating_enable() {
		$status = edublink_set_value( 'ld_course_rating_system', true ) ? edublink_set_value( 'ld_course_rating_system', true ) : false;
		return $status;
	}
endif;

/**
 * Get Woocommerce course price
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'edublink_get_wc_course_price' ) ) :
	function edublink_get_wc_course_price( $product_id = null ) {
		if ( empty( $product_id ) ) :
			return '';
		endif;
	 
		$product = wc_get_product( $product_id );
	 
		if ( ! $product ) :
			return '';
		endif;
	 
		return $product->get_price_html();
	}
endif;



/**
 * LearnDash Course Details Header
 *
 */
if( ! function_exists( 'edublink_ld_course_details_header' ) ) :
	function edublink_ld_course_details_header( $style ) {
		switch ( $style ):
			case 1:
				edublink_ld_course_details_header_default_style();
				break;
			case 2:
				edublink_ld_course_details_header_default_style( 'dark-version' );
				break;
			case 3:
				edublink_ld_course_details_header_default_style();
				break;
			case 4:
				edublink_ld_course_details_header_style_2();
				break;
			case 5:
				edublink_ld_course_details_header_default_style( 'style-5' );
				break;
			case 6:
				edublink_ld_course_details_header_default_style( 'style-6' );
				break;
			default:
			edublink_ld_course_details_header_default_style();
		endswitch;
	}
endif;

/**
 * LearnDash Course Details Header Default Style
 *
 */
if( ! function_exists( 'edublink_ld_course_details_header_default_style' ) ) :
	function edublink_ld_course_details_header_default_style( $style = null ) {
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
							edublink_breadcrumb_ld_course_meta();
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
 * LearnDash Course Details Header Style 2
 *
 */
if( ! function_exists( 'edublink_ld_course_details_header_style_2' ) ) :
	function edublink_ld_course_details_header_style_2() {
		$has_bg_image = '';
		$breadcrumb_img   = edublink_set_value( 'ld_course_breadcrumb_image' );
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
 * LearnDash Course Breaecrumb Meta
 *
 */
if( ! function_exists( 'edublink_breadcrumb_ld_course_meta' ) ) :
	function edublink_breadcrumb_ld_course_meta() {
		global $post;
		$category = edublink_category_by_id( get_the_ID(), 'ld_course_category' );
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

			if ( is_edublink_ld_rating_enable() ) :
				echo '<li class="rating">';
					$rating	= EduBlink_LD_Course_Review::get_average_ratings( $post->ID );
					EduBlink_LD_Course_Review::display_review( $rating, 'text' );
				echo '</li>';
			endif;
		echo '</ul>';
	}
endif;

/**
 * LearnDash Course Details 
 * Header Style 6 Shapes
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
 * Right Side Content
 */
if ( ! function_exists( 'edublink_ld_course_content_sidebar' ) ) :
	function edublink_ld_course_content_sidebar() {
		$style = edublink_set_value( 'ld_course_details_style', '1' );
		$preview_thumb = edublink_set_value( 'ld_course_preview_thumb', true );
		$heading_status = edublink_set_value( 'ld_course_sidebar_heading_students', true );
		$button_status = edublink_set_value( 'ld_course_sidebar_button', true );
		$button_text = edublink_set_value( 'ld_external_button_text' ) ? edublink_set_value( 'ld_external_button_text' ) : __( 'More Info', 'edublink' );
		$button_url = get_post_meta( get_the_ID(), 'edublink_ld_course_button_url', true );
		$button_tab = edublink_set_value( 'ld_external_button_open_tab', true ) ? '_blank' : '_self';
		$social_share_status = edublink_set_value( 'ld_course_sidebar_social_share', true );
		$heading = edublink_set_value( 'ld_course_sidebar_heading_text', __( 'Course Includes:', 'edublink' ) );
		$extra_class = $preview_thumb ? 'enable' : 'disable';

		if ( isset( $_GET['course_details'] ) ) :
			$style = in_array( $_GET['course_details'], array( 1, 2, 3, 4, 5, 6 ) ) ? $_GET['course_details'] : 1;
		endif;

		echo '<div class="edublink-course-details-sidebar eb-ld-course-sidebar eb-course-single-' . esc_attr( $style ) . ' sidebar-' . esc_attr( $extra_class ) . '">';
			echo '<div class="edublink-course-details-sidebar-inner">';
				if ( $preview_thumb && '4' != $style ) :
					edublink_ld_course_preview();
				endif;

				echo '<div class="edublink-course-details-sidebar-content">';
					if ( $heading_status && $heading ) :
						echo '<h4 class="widget-title">' . esc_html( $heading ). '</h4>';
					endif;

					edublink_ld_course_meta_data();

					do_action( 'edublink_ld_course_sidebar_after_meta' );

					if ( $button_status && $button_text && $button_url ) :
						echo '<div class="edublink-course-details-sidebar-buttons">';
							// echo do_shortcode( '[ld_course_resume]' );
							echo '<a class="edu-btn eb-course-details-btn" href="' . esc_url( $button_url ) . '" target="' . esc_attr( $button_tab ) . '">';
								echo esc_html( $button_text );
							echo '</a>';
						echo '</div>';
					endif;

					do_action( 'edublink_ld_course_sidebar_after_button' );

					if ( $social_share_status ) :
						$social_heading = edublink_set_value( 'ld_course_sidebar_social_share_heading', __( 'Share On:', 'edublink' ) );
						echo '<div class="edublink-single-event-social-share">';
							echo '<h4 class="share-title">' . esc_html( $social_heading ) . '</h4>';
							get_template_part( 'template-parts/social', 'share' );
						echo '</div>';
					endif;

					do_action( 'edublink_ld_course_sidebar_after_social_share' );
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
endif;

/**
 * Currency symbols
 * 
 * @param  string $currency  currency code such as USD, EUR
 * @param  int    $course_id course ID
 * @return string currency symbol
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'edublink_ld_course_currency_symbols' ) ) :
	function edublink_ld_course_currency_symbols( $currency, $course_id = null ) {
		$currency_symbols = apply_filters( 'edublink_ld_course_currency_symbols', array(
			'AED' => '&#x62f;.&#x625;',
			'AFN' => '&#x60b;',
			'ALL' => 'L',
			'AMD' => 'AMD',
			'ANG' => '&fnof;',
			'AOA' => 'Kz',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'AWG' => 'Afl.',
			'AZN' => 'AZN',
			'BAM' => 'KM',
			'BBD' => '&#36;',
			'BDT' => '&#2547;',
			'BGN' => '&#1083;&#1074;.',
			'BHD' => '.&#x62f;.&#x628;',
			'BIF' => 'Fr',
			'BMD' => '&#36;',
			'BND' => '&#36;',
			'BOB' => 'Bs.',
			'BRL' => '&#82;&#36;',
			'BSD' => '&#36;',
			'BTC' => '&#3647;',
			'BTN' => 'Nu.',
			'BWP' => 'P',
			'BYR' => 'Br',
			'BYN' => 'Br',
			'BZD' => '&#36;',
			'CAD' => '&#36;',
			'CDF' => 'Fr',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CRC' => '&#x20a1;',
			'CUC' => '&#36;',
			'CUP' => '&#36;',
			'CVE' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DJF' => 'Fr',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'DZD' => '&#x62f;.&#x62c;',
			'EGP' => 'EGP',
			'ERN' => 'Nfk',
			'ETB' => 'Br',
			'EUR' => '&euro;',
			'FJD' => '&#36;',
			'FKP' => '&pound;',
			'GBP' => '&pound;',
			'GEL' => '&#x20be;',
			'GGP' => '&pound;',
			'GHS' => '&#x20b5;',
			'GIP' => '&pound;',
			'GMD' => 'D',
			'GNF' => 'Fr',
			'GTQ' => 'Q',
			'GYD' => '&#36;',
			'HKD' => '&#36;',
			'HNL' => 'L',
			'HRK' => 'kn',
			'HTG' => 'G',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'IMP' => '&pound;',
			'INR' => '&#8377;',
			'IQD' => '&#x639;.&#x62f;',
			'IRR' => '&#xfdfc;',
			'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;',
			'ISK' => 'kr.',
			'JEP' => '&pound;',
			'JMD' => '&#36;',
			'JOD' => '&#x62f;.&#x627;',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KGS' => '&#x441;&#x43e;&#x43c;',
			'KHR' => '&#x17db;',
			'KMF' => 'Fr',
			'KPW' => '&#x20a9;',
			'KRW' => '&#8361;',
			'KWD' => '&#x62f;.&#x643;',
			'KYD' => '&#36;',
			'KZT' => '&#8376;',
			'LAK' => '&#8365;',
			'LBP' => '&#x644;.&#x644;',
			'LKR' => '&#xdbb;&#xdd4;',
			'LRD' => '&#36;',
			'LSL' => 'L',
			'LYD' => '&#x644;.&#x62f;',
			'MAD' => '&#x62f;.&#x645;.',
			'MDL' => 'MDL',
			'MGA' => 'Ar',
			'MKD' => '&#x434;&#x435;&#x43d;',
			'MMK' => 'Ks',
			'MNT' => '&#x20ae;',
			'MOP' => 'P',
			'MRU' => 'UM',
			'MUR' => '&#x20a8;',
			'MVR' => '.&#x783;',
			'MWK' => 'MK',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'MZN' => 'MT',
			'NAD' => 'N&#36;',
			'NGN' => '&#8358;',
			'NIO' => 'C&#36;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'OMR' => '&#x631;.&#x639;.',
			'PAB' => 'B/.',
			'PEN' => 'S/',
			'PGK' => 'K',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PRB' => '&#x440;.',
			'PYG' => '&#8370;',
			'QAR' => '&#x631;.&#x642;',
			'RMB' => '&yen;',
			'RON' => 'lei',
			'RSD' => '&#1088;&#1089;&#1076;',
			'RUB' => '&#8381;',
			'RWF' => 'Fr',
			'SAR' => '&#x631;.&#x633;',
			'SBD' => '&#36;',
			'SCR' => '&#x20a8;',
			'SDG' => '&#x62c;.&#x633;.',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'SHP' => '&pound;',
			'SLL' => 'Le',
			'SOS' => 'Sh',
			'SRD' => '&#36;',
			'SSP' => '&pound;',
			'STN' => 'Db',
			'SYP' => '&#x644;.&#x633;',
			'SZL' => 'L',
			'THB' => '&#3647;',
			'TJS' => '&#x405;&#x41c;',
			'TMT' => 'm',
			'TND' => '&#x62f;.&#x62a;',
			'TOP' => 'T&#36;',
			'TRY' => '&#8378;',
			'TTD' => '&#36;',
			'TWD' => '&#78;&#84;&#36;',
			'TZS' => 'Sh',
			'UAH' => '&#8372;',
			'UGX' => 'UGX',
			'USD' => '&#36;',
			'UYU' => '&#36;',
			'UZS' => 'UZS',
			'VEF' => 'Bs F',
			'VES' => 'Bs.S',
			'VND' => '&#8363;',
			'VUV' => 'Vt',
			'WST' => 'T',
			'XAF' => 'CFA',
			'XCD' => '&#36;',
			'XOF' => 'CFA',
			'XPF' => 'Fr',
			'YER' => '&#xfdfc;',
			'ZAR' => '&#82;',
			'ZMW' => 'ZK'
		) );
		return isset( $currency_symbols[ $currency ] ) ? $currency_symbols[ $currency ] : $currency;
	}
endif;


function edublink_learndash_get_courses( $args = array() ) {

    $args = wp_parse_args( $args, array(
        'author' => '',
        'fields' => ''
    ) );

    extract($args);
    
    $query_args = array(
        'post_type' => 'sfwd-courses',
        'post_status' => 'publish'
    );

    if ( ! empty( $author ) ) :
        $query_args['author'] = $author;
	endif;

    if ( ! empty( $fields ) ) :
        $query_args['fields'] = $fields;
	endif;

    $loop = new WP_Query($query_args);
    $posts = array();
	
    if ( ! empty( $loop->posts ) ) :
        $posts = $loop->posts;
	endif;
    return $posts;
}