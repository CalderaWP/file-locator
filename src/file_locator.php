<?php
/**
 * Return a file path or file contents, checking in child theme, then theme, then as an absolute file path.
 *
 * @package   calderawp\file_locator
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link
 * @copyright 2015 Josh Pollock
 */

namespace calderawp\file_locator;

/**
 * Class file_locator
 *
 * @package calderawp\file_locator
 */
class file_locator {

	/**
	 * Return a file path or file contents, checking in child theme, then theme, then as an absolute file path.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The file to attempt to load, either the full path or relative to currently active theme
	 * @param string $use_for Optional. A context flag that enables filtering allowed extensions via "calderawp_file_locator_allow_extensions"
	 * @param bool $return_path_only Optional. If false, the default, the contents of the file are returned. If true, the path is returned.
	 *
	 * @return void|string File contents, or path if it was loaded.
	 */
	public static function locate( $file, $use_for = false, $return_path_only = false ) {
		$file = self::locate_template( $file  );
		if ( self::verify_files( $file, $use_for ) ) {
			if ( $return_path_only ) {
				return $file;
			}

			return file_get_contents( $file );

		}

	}

	/**
	 * Verify that the file exists and is of an acceptable type
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 *
	 * @param string $file File path. Can be relative to current theme or absolute.
	 * @param string $use_for Context check is for.
	 *
	 * @return bool True if file is valid.
	 */
	protected static function verify_files( $file_path, $use_for = false ) {
		if ( file_exists( $file_path ) && is_file( $file_path ) ) {
			$extension = pathinfo( $file_path, PATHINFO_EXTENSION );

			$allowed = self::allow_extensions( $use_for );
			if ( true === $allowed || ( is_array( $allowed ) && in_array( $extension, $allowed ) ) ) {
				return true;

			}

		}

	}

	/**
	 * Filter which extensions are allowed, by usage.
	 *
	 * @param string $use_for Context for using this.
	 *
	 * @return array
	 */
	protected static function allow_extensions( $use_for ) {
		$allowed  = array( 'php', 'html', 'htm' );
		if ( false === $use_for ) {

			/**
			 * Filter allowed extensions.
			 *
			 * @since 1.0.0
			 *
			 * @param bool|array Array of extensions to allow. Return true to bypass this check. Only works if $use_for was set when calling self::locate
			 * @param string $use_for The value of $use_for when self::locate was called.
			 */
			$_allowed_extensions = apply_filters( 'calderawp_file_locator_allow_extensions', $allowed, $use_for );
			if ( is_array( $_allowed_extensions ) ) {
				$allowed = $_allowed_extensions;
			}


		}

		return $allowed;

	}


	/**
	 * Locate the template's file path.
	 *
	 * Much copypasta from https://github.com/pods-framework/pods/blob/master/classes/PodsView (c) Pods Foundation. Much GPL, very thanks.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 *
	 * @param string $file File path. Can be relative to current theme or absolute. Must be .html or .htm
	 *
	 * @return bool|mixed|string|void
	 */
	protected static function locate_template( $file ) {

		// Keep it safe
		$file = trim( str_replace( array( '../', '\\' ), array( '', '/' ), (string) $file ) );
		$file = preg_replace( '/\/+/', '/', $file );

		if ( empty( $file ) ) {
			return false;
		}

		$_real_view = realpath( $file );

		if ( empty( $_real_view ) ) {
			$_real_view = $file;
		}

		$located = false;

		if ( false === strpos( $_real_view, realpath( WP_PLUGIN_DIR ) ) && false === strpos( $_real_view, realpath( WPMU_PLUGIN_DIR ) ) ) {
			$_real_view = trim( $_real_view, '/' );

			if ( empty( $_real_view ) ) {
				return false;
			}

			if ( file_exists( realpath( get_stylesheet_directory() . '/' . $_real_view ) ) ) {
				$located = realpath( get_stylesheet_directory() . '/' . $_real_view );
			}
			elseif ( file_exists( realpath( get_template_directory() . '/' . $_real_view ) ) ) {
				$located = realpath( get_template_directory() . '/' . $_real_view );
			}

		} elseif ( file_exists( $file ) ) {
			$located = $file;
		}
		else {
			$located = '';
		}

		/**
		 * Filter which template is returned
		 *
		 * @since 1.0.0
		 *
		 * @param string $located Located file path to return.
		 * @param string $file Requested file
		 */
		return apply_filters( 'calderawp_file_locator_located_template', $located, $file );

	}


}
