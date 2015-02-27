<?php
/**
 * Functions file for this library.
 *
 * @package   calderawp\file_locator
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2015 Josh Pollock
 */


if ( ! function_exists( 'calderawp_file_locator' ) ) :
	/**
	 * Return a file path or file contents, checking in child theme, then theme, then as an absolute file path.
	 *
	 * @since 1.0.0
	 *
	 * @param string $file The file to attempt to load, either the full path or relative to currently active theme.
	 * @param string $use_for Optional. A context flag that enables filtering allowed extensions via "calderawp_file_locator_allow_extensions"
	 * @param bool $return_path_only Optional. If false, the default, the contents of the file are returned. If true, the path is returned.
	 *
	 * @return void|string File contents, or path if it was loaded.
	 */
	function calderawp_file_locator( $file, $use_for = false, $return_path_only = false ) {
		return \calderawp\file_locator\file_locator::locate( $file, $return_path_only );

	}
endif;
