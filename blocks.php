<?php

defined( 'ABSPATH' ) || exit();

use \EduBlink\Filter;

if ( ! isset( $block_data ) ) :
    $block_data = array();
endif;

$default_data = Filter::LP_Data();
$features = $default_data['features'];
$block_data = wp_parse_args( $block_data, $default_data );
learn_press_get_template( 'custom/course-block/block-' . $block_data['style'] . '.php', compact( 'block_data', 'features' ) );