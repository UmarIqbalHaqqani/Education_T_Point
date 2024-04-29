<?php
/**
 * The BLANK header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package EduBlink
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php 
	$preloader = edublink_set_value( 'preloader', true );
	if ( $preloader ) : 
		get_template_part( 'template-parts/preloaders/preloader' );
	endif;
	echo '<div id="page" class="site">';
		echo '<a class="skip-link screen-reader-text" href="#content">' . __( 'Skip to content', 'edublink' ) . '</a>';