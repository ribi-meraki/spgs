<?php
defined( 'ABSPATH' ) || exit;

/**
 * Helper functions.
 *
 * All functions don't need to name prefix because they checked with function_exists.
 */


/**
 * Returns '0'.
 *
 * Useful for returning 0 to filters easily.
 *
 * @return string '0'.
 */
if ( ! function_exists( '__return_zero_string' ) ) {
	function __return_zero_string() {
		return '0';
	}
}

if ( ! function_exists( 'html_class' ) ) {
	function html_class( $class = '' ) {
		$classes = array();

		if ( is_admin_bar_showing() ) {
			$classes[] = 'has-admin-bar';
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}
			$classes = array_merge( $classes, $class );
		} else {
			// Ensure that we always coerce class to being an array.
			$class = array();
		}

		$classes = apply_filters( 'html_class', $classes, $class );

		if ( ! empty( $classes ) ) {
			echo 'class="' . esc_attr( join( ' ', $classes ) ) . '"';
		}
	}
}

/**
 * Hook in wp 5.2
 * Backwards Compatibility with old versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

/**
 * Loads a template part into a template with prefix folder given.
 *
 * @param string $slug The slug name for the generic template.
 * @param string $name The name of the specialised template.
 * @param array $args Optional. Additional arguments passed to the template.
 *                     Default empty array.
 *
 * @return void|false Void on success, false if the template does not exist.
 * @see get_template_part()
 */
function unicamp_load_template( $slug, $name = null, $args = array() ) {
	get_template_part( "template-parts/{$slug}", $name, $args );
}

function unicamp_valid_file_path( $file_path ) {
	return str_replace( '/', UNICAMP_DS, $file_path );
}

function unicamp_require_once( $file_path ) {
	$file_path = unicamp_valid_file_path( $file_path );

	require_once $file_path;
}


/**
 * Admin notice waning minimum plugin version required.
 *
 * @param $plugin_name
 * @param $plugin_version
 */
function unicamp_notice_required_plugin_version( $plugin_name, $plugin_version ) {
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}

	$message = sprintf(
		esc_html__( '%1$s requires %2$s plugin version %3$s or greater!', 'unicamp' ),
		'<strong>' . UNICAMP_THEME_NAME . '</strong>',
		'<strong>' . $plugin_name . '</strong>',
		$plugin_version
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

/**
 * Allow to remove method for an hook when, it's a class method used and class don't have variable, but you know the class name :)
 *
 * @see https://github.com/herewithme/wp-filters-extras/blob/master/wp-filters-extras.php
 *
 * @param string $hook_name The action hook to which the function to be removed is hooked.
 * @param string $class_name The class name of contain function which should be removed.
 * @param string $method_name The name of the function which should be removed.
 * @param int $priority Optional. The priority of the function. Default 10.
 *
 * @return bool
 */
function unicamp_remove_filters_for_anonymous_class( $hook_name = '', $class_name = '', $method_name = '', $priority = 10 ) {
	global $wp_filter;

	// Take only filters on right hook name and priority
	if ( ! isset( $wp_filter[ $hook_name ][ $priority ] ) || ! is_array( $wp_filter[ $hook_name ][ $priority ] ) ) {
		return false;
	}

	// Loop on filters registered
	foreach ( (array) $wp_filter[ $hook_name ][ $priority ] as $unique_id => $filter_array ) {
		// Test if filter is an array ! (always for class/method)
		if ( isset( $filter_array['function'] ) && is_array( $filter_array['function'] ) ) {
			// Test if object is a class, class and method is equal to param !
			if ( is_object( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) && get_class( $filter_array['function'][0] ) == $class_name && $filter_array['function'][1] == $method_name ) {
				// Test for WordPress >= 4.7 WP_Hook class (https://make.wordpress.org/core/2016/09/08/wp_hook-next-generation-actions-and-filters/)
				if ( is_a( $wp_filter[ $hook_name ], 'WP_Hook' ) ) {
					unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
				} else {
					unset( $wp_filter[ $hook_name ][ $priority ][ $unique_id ] );
				}
			}
		}

	}

	return false;
}


/**
 * @param null $user_id
 * @param int $width
 * @param int $height
 *
 * Generate text to avatar
 *
 * Rewrite plugin function.
 *
 * @return string
 * @see   \TUTOR\Utils::get_tutor_avatar()
 *
 */
function unicamp_get_avatar( $user_id = null, $width = 150, $height = false ) {
	if ( ! $user_id ) {
		return '';
	}

	if ( ! $height ) {
		$height = $width;
	}

	// Priority use Tutor profile photo.
	if ( function_exists( 'tutor_utils' ) ) {
		$user = tutor_utils()->get_tutor_user( $user_id );

		if ( $user && $user->tutor_profile_photo ) {
			return Unicamp_Image::get_attachment_by_id( [
				'id'        => $user->tutor_profile_photo,
				'size'      => 'custom',
				'width'     => $width,
				'height'    => $height,
				'img_attrs' => [
					'class' => 'tutor-image-avatar',
				],
			] );
		}
	}

	return get_avatar( $user_id, $width );
}

/**
 * Check if has elementor template
 *
 * @param $location
 *
 * @return bool
 *
 * @since 1.3.0
 */
function unicamp_has_elementor_template( $location ) {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
		return true;
	}

	return false;
}

/**
 * @return string
 *
 * @since 2.7.0
 */
function unicamp_login_url() {
	return apply_filters( 'unicamp_login_url', wp_login_url() );
}

/**
 * @return string
 *
 * @since 2.7.0
 */
function unicamp_registration_url() {
	return apply_filters( 'unicamp_register_url', wp_registration_url() );
}

/**
 * @return string
 *
 * @since 2.7.0
 */
function unicamp_user_profile_url() {
	return apply_filters( 'unicamp_user_profile_url', '' );
}
