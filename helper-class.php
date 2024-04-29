<?php
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class EduBlink_LD_Helper {

    public static function instructor( $tag = 'h6', $image_size = 40 ) {
        $redirect = apply_filters( 'edublink_ld_author_redirect_to_course', false );
        if ( $redirect ) :
            echo '<a class="author-name" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) ) . '?ldauthor=true' ) . '">';
        endif;

        echo get_avatar( get_the_author_meta( 'ID' ), $image_size );

        echo '<' . esc_attr( $tag) . ' class="instructor-name">';
            the_author();
        echo '</' . esc_attr( $tag) . '>';
        if ( $redirect ) :
            echo '</a>';
        endif;
    }
    
    public static function course_price() {
        global $post; 
        $ribbon_text = get_post_meta( $post->ID, '_learndash_course_grid_custom_ribbon_text', true );
        $ribbon_text = ! empty( $ribbon_text ) ? $ribbon_text : '';
        $currency = '';
        $user_id = get_current_user_id();

        if ( function_exists( 'learndash_get_currency_symbol' ) ) {
            $currency = learndash_get_currency_symbol();
        } else {
            $paypal_enabled = class_exists( 'LearnDash_Settings_Section' ) ? LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_PayPal', 'enabled' ) : null;
            $paypal_currency = class_exists( 'LearnDash_Settings_Section' ) ? LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_PayPal', 'paypal_currency' ) : null;

            $stripe_settings = get_option( 'learndash_stripe_settings', [] );

            if ( class_exists( 'NumberFormatter' ) ) {
                if ( $paypal_enabled == 'on' && ! empty( $paypal_currency ) ) {
                    $locale = get_locale();
                    $number_format = new NumberFormatter( $locale . '@currency=' . $paypal_currency, NumberFormatter::CURRENCY );  
                    $currency = $number_format->getSymbol( NumberFormatter::CURRENCY_SYMBOL );
                } elseif ( isset( $stripe_settings['enabled'] ) && $stripe_settings['enabled'] == 'yes' && ! empty( $stripe_settings['currency'] ) ) {
                    $locale = get_locale();
                    $number_format = new NumberFormatter( $locale . '@currency=' . $stripe_settings['currency'], NumberFormatter::CURRENCY );  
                    $currency = $number_format->getSymbol( NumberFormatter::CURRENCY_SYMBOL );
                }
            }
        }

        /**
         * learndash_course_grid_currency
         * filter for currency icon
         */
        $currency = apply_filters( 'learndash_course_grid_currency', $currency, $post->ID );
        $edublink_currency_symbol = apply_filters( 'edublink_currency_symbol_status', false );
        if ( $edublink_currency_symbol ) :
            $currency = edublink_ld_course_currency_symbols( $currency );
        endif;

        $price = '';
        $price_type = '';
        $price_text = '';
        if ( function_exists( 'learndash_get_course_price' ) && function_exists( 'learndash_get_group_price' ) ) {
            if ( $post->post_type == 'sfwd-courses' ) {
                $price_args = learndash_get_course_price( $post->ID );
            } elseif ( $post->post_type == 'groups' ) {
                $price_args = learndash_get_group_price( $post->ID );
            }

            if ( ! empty( $price_args ) ) {
                $price = $price_args['price'];
                $price_type = $price_args['type'];

                $price_format = apply_filters( 'learndash_course_grid_price_text_format', '{currency}{price}' );

                if ( is_numeric( $price ) && ! empty( $price ) ) {
                    $price = self::format_price( $price, 'output' );
                    $price_text = str_replace( [ '{currency}', '{price}' ], [ $currency, $price ], $price_format );
                } elseif ( is_string( $price ) && ! empty( $price ) ) {
                    if ( preg_match( '/(((\d+),?)*(\d+)(\.?\d+)?)/', $price ) ) {
                        $price = self::format_price( $price, 'output' );
                        $price_text = str_replace( [ '{currency}', '{price}' ], [ $currency, $price ], $price_format );
                    } else {
                        $price_text = $price;
                    }
                } elseif ( empty( $price ) ) {
                    if ( 'closed' === $price_type || 'open' === $price_type ) {
                        $price_text = '';
                    } else {
                        $price_text = __( 'Free', 'edublink' );
                    }
                }

                if ( $price_type == 'subscribe' ) {
                    $trial_price = $price_args['trial_price'] ?? false;
                    
                    $trial_duration = isset( $price_args['trial_interval'] ) && isset( $price_args['trial_frequency'] ) ? $price_args['trial_interval'] . ' ' . $price_args['trial_frequency'] : false;

                    if ( isset( $price_args['interval'] ) && isset( $price_args['frequency'] ) ) {
                        $subscription_duration =  $price_args['interval'] > 1 ? $price_args['interval'] . ' ' . $price_args['frequency'] : $price_args['frequency'];

                        $price_text = sprintf( '%s%s', $price_text, $subscription_duration ? '/' . $subscription_duration : '' );
                    }
                }
            }
        }

        if ( empty( $price ) ) {
            $price = __( 'Free', 'edublink' );
        }

        $price = apply_filters( 'learndash_course_grid_price', $price, $post->ID );

        $user_object = get_user_by( 'ID', $post->post_author );
        $author = apply_filters( 'learndash_course_grid_author', [
            'name' => $user_object->display_name,
            'avatar' => get_avatar_url( $post->post_author ),
        ], $post->ID, $post->post_author );

        $is_completed = false;

        $has_access = false;
        if ( defined( 'LEARNDASH_VERSION' ) ) {
            if ( in_array( $post->post_type, [ 'sfwd-courses', 'groups' ] ) ) {
                if ( $price_type != 'open' && empty( $ribbon_text ) ) {
                    $ribbon_text = $price_text;
                } elseif ( $price_type == 'open' && empty( $ribbon_text ) ) {
                    $ribbon_text = __( 'Free', 'edublink' );
                } 
            }
        }

        $ribbon_text = apply_filters( 'learndash_course_grid_ribbon_text', $ribbon_text, $post->ID, $price_type );

        return $ribbon_text;
    }

    public static function course_price_alter() {
        global $post; 
        $ribbon_text = get_post_meta( $post->ID, '_learndash_course_grid_custom_ribbon_text', true );
        $ribbon_text = ! empty( $ribbon_text ) ? $ribbon_text : '';
        $currency = '';
        $user_id = get_current_user_id();

        if ( function_exists( 'learndash_get_currency_symbol' ) ) {
            $currency = learndash_get_currency_symbol();
        } else {
            $paypal_enabled = class_exists( 'LearnDash_Settings_Section' ) ? LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_PayPal', 'enabled' ) : null;
            $paypal_currency = class_exists( 'LearnDash_Settings_Section' ) ? LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_PayPal', 'paypal_currency' ) : null;

            $stripe_settings = get_option( 'learndash_stripe_settings', [] );

            if ( class_exists( 'NumberFormatter' ) ) {
                if ( $paypal_enabled == 'on' && ! empty( $paypal_currency ) ) {
                    $locale = get_locale();
                    $number_format = new NumberFormatter( $locale . '@currency=' . $paypal_currency, NumberFormatter::CURRENCY );  
                    $currency = $number_format->getSymbol( NumberFormatter::CURRENCY_SYMBOL );
                } elseif ( isset( $stripe_settings['enabled'] ) && $stripe_settings['enabled'] == 'yes' && ! empty( $stripe_settings['currency'] ) ) {
                    $locale = get_locale();
                    $number_format = new NumberFormatter( $locale . '@currency=' . $stripe_settings['currency'], NumberFormatter::CURRENCY );  
                    $currency = $number_format->getSymbol( NumberFormatter::CURRENCY_SYMBOL );
                }
            }
        }

        /**
         * learndash_course_grid_currency
         * filter for currency icon
         */
        $currency = apply_filters( 'learndash_course_grid_currency', $currency, $post->ID );
        $edublink_currency_symbol = apply_filters( 'edublink_currency_symbol_status', false );
        if ( $edublink_currency_symbol ) :
            $currency = edublink_ld_course_currency_symbols( $currency );
        endif;
        
        $price = '';
        $price_type = '';
        $price_text = '';
        if ( function_exists( 'learndash_get_course_price' ) && function_exists( 'learndash_get_group_price' ) ) {
            if ( $post->post_type == 'sfwd-courses' ) {
                $price_args = learndash_get_course_price( $post->ID );
            } elseif ( $post->post_type == 'groups' ) {
                $price_args = learndash_get_group_price( $post->ID );
            }

            if ( ! empty( $price_args ) ) {
                $price = $price_args['price'];
                $price_type = $price_args['type'];

                $price_format = apply_filters( 'learndash_course_grid_price_text_format', '{currency}{price}' );

                if ( is_numeric( $price ) && ! empty( $price ) ) {
                    $price = self::format_price( $price, 'output' );
                    $price_text = str_replace( [ '{currency}', '{price}' ], [ $currency, $price ], $price_format );
                } elseif ( is_string( $price ) && ! empty( $price ) ) {
                    if ( preg_match( '/(((\d+),?)*(\d+)(\.?\d+)?)/', $price ) ) {
                        $price = self::format_price( $price, 'output' );
                        $price_text = str_replace( [ '{currency}', '{price}' ], [ $currency, $price ], $price_format );
                    } else {
                        $price_text = $price;
                    }
                } elseif ( empty( $price ) ) {
                    if ( 'closed' === $price_type || 'open' === $price_type ) {
                        $price_text = '';
                    } else {
                        $price_text = __( 'Free', 'edublink' );
                    }
                }

                if ( $price_type == 'subscribe' ) {
                    $trial_price = $price_args['trial_price'] ?? false;
                    
                    $trial_duration = isset( $price_args['trial_interval'] ) && isset( $price_args['trial_frequency'] ) ? $price_args['trial_interval'] . ' ' . $price_args['trial_frequency'] : false;

                    if ( isset( $price_args['interval'] ) && isset( $price_args['frequency'] ) ) {
                        $subscription_duration =  $price_args['interval'] > 1 ? $price_args['interval'] . ' ' . $price_args['frequency'] : $price_args['frequency'];

                        $price_text = sprintf( '%s%s', $price_text, $subscription_duration ? '/' . $subscription_duration : '' );
                    }
                }
            }
        }

        if ( empty( $price ) ) {
            $price = __( 'Free', 'edublink' );
        }

        $price = apply_filters( 'learndash_course_grid_price', $price, $post->ID );

        $user_object = get_user_by( 'ID', $post->post_author );
        $author = apply_filters( 'learndash_course_grid_author', [
            'name' => $user_object->display_name,
            'avatar' => get_avatar_url( $post->post_author ),
        ], $post->ID, $post->post_author );

        $is_completed = false;

        $has_access = false;
        if ( defined( 'LEARNDASH_VERSION' ) ) {
            if ( $post->post_type == 'sfwd-courses' ) {
                $has_access   = sfwd_lms_has_access( $post->ID, $user_id );
                $is_completed = learndash_course_completed( $user_id, $post->ID );
            } elseif ( $post->post_type == 'groups' ) {
                $has_access = learndash_is_user_in_group( $user_id, $post->ID );
                $is_completed = learndash_get_user_group_completed_timestamp( $post->ID, $user_id );
            } elseif ( $post->post_type == 'sfwd-lessons' ) {
                $parent_course_id = learndash_get_course_id( $post->ID );
                $has_access   = is_user_logged_in() && ! empty( $parent_course_id ) ? sfwd_lms_has_access( $post->ID, $user_id ) : false;
                $is_completed = learndash_is_lesson_complete( $user_id, $post->ID, $parent_course_id );
            } elseif ( $post->post_type == 'sfwd-topic' ) {
                $parent_course_id = learndash_get_course_id( $post->ID );
                $has_access   = is_user_logged_in() && ! empty( $parent_course_id ) ? sfwd_lms_has_access( $post->ID, $user_id ) : false;
                $is_completed = learndash_is_topic_complete( $user_id, $post->ID, $parent_course_id );
            }

            if ( in_array( $post->post_type, [ 'sfwd-courses', 'groups' ] ) ) {
                if ( $price_type != 'open' && empty( $ribbon_text ) ) {
                    if ( $has_access && ! $is_completed ) {
                        $ribbon_text = __( 'Enrolled', 'edublink' );
                    } elseif ( $has_access && $is_completed ) {
                        $ribbon_text = __( 'Completed', 'edublink' );
                    } elseif ( ! empty( $price ) ) {
                        $ribbon_text = $price_text;
                    } elseif ( $price_type == 'free' ) {
                        $ribbon_text = __( 'Free', 'edublink' );
                    } else {
                        $ribbon_text = __( 'Available', 'edublink' );
                    }
                } elseif ( $price_type == 'open' && empty( $ribbon_text ) ) {
                    if ( is_user_logged_in() && ! $is_completed ) {
                        $ribbon_text = __( 'Enrolled', 'edublink' );
                    } elseif ( is_user_logged_in() && $is_completed ) {
                        $ribbon_text = __( 'Completed', 'edublink' );
                    } else {
                        $ribbon_text = __( 'Free', 'edublink' );
                    }
                } 
            } elseif ( in_array( $post->post_type, ['sfwd-lessons', 'sfwd-topic'] ) ) {
                $has_started = false;

                if ( $post->post_type == 'sfwd-lessons' ) {
                    $activity_type = 'lesson';
                } elseif ( $post->post_type == 'sfwd-topic' ) {
                    $activity_type = 'topic';
                }

                $activity = learndash_get_user_activity( [
                    'course_id'        => $course_id,
                    'user_id'          => $user_id,
                    'post_id'          => $post->ID,
                    'activity_type'    => $activity_type,
                ] );

                if ( ! empty( $activity ) ) {
                    if ( ! empty( $activity->activity_started ) && ! $activity->activity_completed ) {
                        $has_started = true;
                    }
                }

                if ( $has_access && $is_completed ) {
                    $ribbon_text = __( 'Completed', 'edublink' );
                } elseif ( $has_access && ! $has_started ) {
                    $ribbon_text = __( 'Not started', 'edublink' );
                } elseif ( $has_access && $has_started ) {
                    $ribbon_text = __( 'In progress', 'edublink' );
                } elseif ( learndash_is_sample( $post->ID ) ) {
                    $ribbon_text = __( 'Free', 'edublink' );
                } else {
                    $ribbon_text = '';
                }
            }
        }

        $ribbon_text = apply_filters( 'learndash_course_grid_ribbon_text', $ribbon_text, $post->ID, $price_type );

        return $ribbon_text;
    }

    public static function format_price( $price, $format = 'plain' )
    {
        if ( $format == 'output' ) {
            preg_match( '/(((\d+)[,\.]?)*(\d+)([\.,]?\d+)?)/', $price, $matches );

            $price = $matches[1];

            if ( ! empty( $price ) ) {
                $match_comma_decimal = preg_match( '/(?:\d+\.?)*\d+(,\d{1,2})$/', $price, $comma_matches );

                $match_dot_decimal = preg_match( '/(?:\d+,?)*\d+(\.\d{1,2})$/', $price, $dot_matches );

                if ( $match_comma_decimal ) {
                    $has_decimal = ! empty( $comma_matches[1] ) ? true : false;
                    $thousands_separator = '.';
                    $decimal_separator = ',';
                    $price = str_replace( '.', '', $price );
                    $price = str_replace( ',', '.', $price );
                } else {
                    $has_decimal = ! empty( $dot_matches[1] ) ? true : false;
                    $thousands_separator = ',';
                    $decimal_separator = '.';
                    $price = str_replace( ',', '', $price );
                }
                
                $price = floatval( $price );
        
                if ( $has_decimal ) {
                    $price = number_format( $price, 2, $decimal_separator, $thousands_separator );
                } else {
                    $price = number_format( $price, 0, $decimal_separator, $thousands_separator );
                }
            }

            return $price;
        }

        return $price;
    }

    public static function course_price_alter_two() {
        global $post; 
        $post_id = $post->ID;
        $course_id = $post_id;
        $user_id   = get_current_user_id();
        $current_id = $post->ID;
        $options = get_option('sfwd_cpt_options');
        $currency = null;
        $currency_code = ! empty( $currency_code ) ? $currency_code : learndash_get_currency_code();
        $actual_price = '';

        if ( ! is_null( $options ) ) :
            if ( isset($options['modules'] ) && isset( $options['modules']['sfwd-courses_options'] ) && isset( $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'] ) )
                $currency = $options['modules']['sfwd-courses_options']['sfwd-courses_paypal_currency'];
        endif;

        if ( $currency_code ) :
                $currency_symbol = learndash_get_currency_symbol( $currency_code );
        endif;

        $course_options = get_post_meta( $post_id, "_sfwd-courses", true );

        $price = $course_options && isset($course_options['sfwd-courses_course_price']) ? $course_options['sfwd-courses_course_price'] : __( 'Free', 'edublink' );

        $has_access   = sfwd_lms_has_access( $course_id, $user_id );
        $is_completed = learndash_course_completed( $user_id, $course_id );

        if( $price == '' ) :
            $price .= __( 'Free', 'edublink' );
        endif;

        if ( is_numeric( $price ) ) :
            if ( $currency_symbol ) :
                $currency_symbol = apply_filters( 'edublink_ld_course_currency_symbol', $currency_symbol );
                $price = $currency_symbol . $price;
            endif;
        endif;

        if ( $has_access && ! $is_completed ) :
            $actual_price = __( 'Enrolled', 'edublink' );
        elseif ( $has_access && $is_completed ) :
            $actual_price = __( 'Completed', 'edublink' );
        else :
            $actual_price = $price;
        endif;
        return $actual_price;
    }
}