<?php
/**
 * tagDiv plugin compatibility guard.
 *
 * The tagDiv plugins that ship with the Newspaper theme (td-cloud-library,
 * td-composer and friends) attach callbacks to front-end hooks that call
 * classes the *theme* defines — td_util, td_global, td_api and so on. Under
 * any theme other than a tagDiv one those classes are absent, so the first
 * such callback throws
 *
 *     Uncaught Error: Class "td_util" not found
 *         in td-cloud-library/td-cloud-library.php:217
 *         #0 tdb_add_js_variables()  <- hooked to wp_head
 *
 * and takes the entire front end down. Twenty Twenty-Five fails the same way;
 * it is not specific to this theme.
 *
 * Deactivating those plugins is the real fix. This guard only makes sure that
 * forgetting to do so degrades gracefully instead of white-screening the site,
 * and it does nothing at all while a tagDiv theme is active.
 *
 * @package Indfir
 */

defined( 'ABSPATH' ) || exit;

/**
 * Front-end hooks the tagDiv plugins attach theme-dependent callbacks to.
 */
function indfir_tagdiv_guarded_hooks() {
	return array(
		'wp_head',
		'wp_footer',
		'wp_enqueue_scripts',
		'get_header',
		'get_footer',
		'loop_start',
		'the_content',
		'template_redirect',
	);
}

/**
 * Directory name prefixes used by the tagDiv plugin family.
 */
function indfir_tagdiv_path_marker() {
	return '/plugins/td-';
}

/**
 * Is this callback owned by a tagDiv plugin?
 *
 * Two signals are used. The cheap one is the callback name: plain functions
 * (tdb_add_js_variables) and methods on tagDiv classes (td_subscription::...)
 * all carry a td-family prefix. The reliable one is the file the callback is
 * declared in — that is what catches closures such as
 *
 *     td_subscription->{closure:td_subscription::init():913}
 *
 * which carry no usable name at all but still live inside wp-content/plugins/td-*.
 *
 * @param mixed $callback Registered callback.
 * @return bool
 */
function indfir_is_tagdiv_callback( $callback ) {
	$name = '';

	if ( is_string( $callback ) ) {
		$name = $callback;
	} elseif ( is_array( $callback ) && isset( $callback[0] ) ) {
		$name = is_object( $callback[0] ) ? get_class( $callback[0] ) : (string) $callback[0];
	} elseif ( $callback instanceof Closure ) {
		// A closure bound to a tagDiv object still identifies its owner.
		try {
			$bound = ( new ReflectionFunction( $callback ) )->getClosureThis();
			if ( $bound ) {
				$name = get_class( $bound );
			}
		} catch ( ReflectionException $e ) {
			$name = '';
		}
	}

	if ( '' !== $name && preg_match( '/^(td|tdb|tdc|tds|tdw)_/i', $name ) ) {
		return true;
	}

	// Fall back to where the callback is declared.
	$file = indfir_callback_file( $callback );

	return $file && false !== strpos( str_replace( '\\', '/', $file ), indfir_tagdiv_path_marker() );
}

/**
 * Absolute path of the file a callback is declared in, or '' if undeterminable.
 *
 * @param mixed $callback Registered callback.
 * @return string
 */
function indfir_callback_file( $callback ) {
	try {
		if ( $callback instanceof Closure || is_string( $callback ) && function_exists( $callback ) ) {
			$ref = new ReflectionFunction( $callback );
		} elseif ( is_array( $callback ) && isset( $callback[0], $callback[1] ) ) {
			$ref = new ReflectionMethod( $callback[0], $callback[1] );
		} elseif ( is_object( $callback ) && method_exists( $callback, '__invoke' ) ) {
			$ref = new ReflectionMethod( $callback, '__invoke' );
		} else {
			return '';
		}
	} catch ( ReflectionException $e ) {
		return '';
	}

	$file = $ref->getFileName();

	return $file ? $file : '';
}

/**
 * Detach tagDiv front-end callbacks when their theme-side classes are missing.
 */
function indfir_detach_tagdiv_hooks() {
	// A tagDiv theme is active (or a child of one) — leave everything alone.
	if ( class_exists( 'td_util' ) || class_exists( 'td_global' ) ) {
		return;
	}

	// Nothing to do if no tagDiv plugin is loaded.
	if ( ! function_exists( 'tdb_add_js_variables' ) && ! defined( 'TD_COMPOSER' ) && ! defined( 'TDB_URL' ) ) {
		return;
	}

	global $wp_filter;
	$removed = 0;

	foreach ( indfir_tagdiv_guarded_hooks() as $tag ) {
		if ( empty( $wp_filter[ $tag ] ) || ! isset( $wp_filter[ $tag ]->callbacks ) ) {
			continue;
		}

		foreach ( $wp_filter[ $tag ]->callbacks as $priority => $callbacks ) {
			foreach ( $callbacks as $entry ) {
				if ( ! isset( $entry['function'] ) || ! indfir_is_tagdiv_callback( $entry['function'] ) ) {
					continue;
				}

				remove_filter( $tag, $entry['function'], $priority );
				$removed++;
			}
		}
	}

	if ( $removed && ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) ) {
		return;
	}

	if ( $removed ) {
		/* translators: %d: number of detached callbacks. */
		error_log( sprintf( 'Indfir: detached %d tagDiv callback(s); deactivate the tagDiv plugins.', $removed ) );
	}
}
// Late enough that every plugin has registered, early enough to precede output.
add_action( 'template_redirect', 'indfir_detach_tagdiv_hooks', 0 );

/**
 * Warn in the admin while the incompatible plugins are still active.
 */
function indfir_tagdiv_admin_notice() {
	if ( ! current_user_can( 'activate_plugins' ) ) {
		return;
	}
	if ( class_exists( 'td_util' ) || class_exists( 'td_global' ) ) {
		return;
	}
	if ( ! function_exists( 'tdb_add_js_variables' ) && ! defined( 'TD_COMPOSER' ) && ! defined( 'TDB_URL' ) ) {
		return;
	}
	?>
	<div class="notice notice-warning">
		<p>
			<strong><?php esc_html_e( 'Indfir:', 'indfir' ); ?></strong>
			<?php
			esc_html_e(
				'Plugin tagDiv (tagDiv Composer / Cloud Library dan sejenisnya) masih aktif. Plugin itu membutuhkan tema Newspaper dan akan mematikan halaman depan tanpa tema tersebut. Tema ini menonaktifkan hook-nya secara otomatis sebagai pengaman, tetapi sebaiknya plugin-plugin itu dinonaktifkan.',
				'indfir'
			);
			?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'indfir_tagdiv_admin_notice' );
