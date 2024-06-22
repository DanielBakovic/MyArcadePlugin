<?php
/**
 * Heler functions.
 *
 * @author Daniel Bakovic <contact@myarcadeplugin.com>
 * @package MyArcadePlugin/Helpers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}

/**
 * Helper functions.
 */
class MyArcade_Helper {

	/**
	 * Function to check if the array has correct syntax. Used for some IBPArcade games,
	 * that uses outdated array declaration
	 *
	 * @param string $contents Content of a game config file. Usually a PHP file.
	 * @return boolean
	 */
	public static function has_correct_array_synthax( $contents ) {

		$tokens = token_get_all( $contents );

		$brace_count = 0;
		$last_token  = null;

		foreach ( $tokens as $token ) {
			if ( is_array( $token ) ) {
				$type  = $token[0];
				$value = $token[1];

				if ( T_ARRAY === $type || '[' === $value ) {
					$brace_count++;
				} elseif ( T_WHITESPACE === $type || T_COMMENT === $type ) {
					continue;
				} elseif ( ']' === $value ) {
					$brace_count--;
				}

				// Check for missing quotes around array keys.
				if ( T_CONSTANT_ENCAPSED_STRING === $type && T_DOUBLE_ARROW === $last_token ) {
					// Ensure the string is properly quoted.
					$first_char = $value[0];
					$last_char  = $value[ strlen( $value ) - 1 ];

					if ( ! ( ( '"' === $first_char && '"' === $last_char ) || ( '\'' === $first_char && '\'' === $last_char ) ) ) {
						// Missing or mismatched quotes around array key.
						return false;
					}
				}

				$last_token = $type;
			}
		}

		return 0 === $brace_count;
	}

	/**
	 * Ensures keys and values in the configuration string are enclosed in double quotes.
	 * Keys without quotes will get double quotes, and keys/values with single quotes will have them replaced with double quotes.
	 *
	 * @param string $content The configuration string with array syntax.
	 * @return string The configuration string with keys and values enclosed in double quotes.
	 */
	public static function ensure_double_quotes( $content ) {

		// Replace single quotes around values with double quotes.
		$content_fixed = preg_replace_callback( '/=>\s*\'([^\']*)\'/', array( __CLASS__, 'convert_single_to_double_quotes' ), $content );

		// Replace single quotes around keys with double quotes or add double quotes if missing.
		$content_fixed = preg_replace_callback( '/(\'[a-zA-Z_]\w*\'|[a-zA-Z_]\w*)\s*=>/', array( __CLASS__, 'quote_keys' ), $content_fixed );


		return $content_fixed;
	}

	/**
	 * Callback function to convert single quotes to double quotes around values.
	 *
	 * @param array $matches The array of matches from the preg_replace_callback function.
	 * @return string The modified string with double quotes around the value.
	 */
	public static function convert_single_to_double_quotes( $matches ) {
		$value = $matches[1];
		// Replace single quotes with double quotes.
		return '=> "' . $value . '"';
	}

	/**
	 * Callback function to add double quotes around keys if missing or replace single quotes with double quotes.
	 *
	 * @param array $matches The array of matches from the preg_replace_callback function.
	 * @return string The modified string with double quotes around the key.
	 */
	public static function quote_keys( $matches ) {
		$key = $matches[1];
		// Remove single quotes if present.
		if ( $key[0] === "'" ) {
				$key = substr( $key, 1, -1 );
		}

		return '"' . $key . '":';
	}


	/**
	 * Function to extract and parse the IBPArcade $config array.
	 *
	 * @param string $contents Content of a game config file. Usually a PHP file.
	 * @return array
	 */
	public static function extract_and_parse_config( $contents ) {

		// Extract the $config array using regular expressions.
		if ( preg_match( '/\$config\s*=\s*array\((.*?)\);/s', $contents, $matches ) ) {
			$config_str = $matches[1];

			// Remove comments to avoid interference with parsing.
			$config_str_fixed = preg_replace( '/\/\*.+?\*\/|\/\/[^\n]+/', '', $config_str );

			// Remove empty lines.
			$config_str_fixed = preg_replace( "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $config_str_fixed );

			// Trim leading and trailing spaces.
			$config_str_fixed = trim( $config_str_fixed );

			// Ensure keys and values are quoted with double quotes.
			$config_str_fixed = self::ensure_double_quotes( $config_str );

			// Remove white spaces.
			$config_str_fixed = preg_replace( '/\s+/', ' ', $config_str_fixed );

			// Remove the last comma if it exists.
			$config_str_fixed = preg_replace( '/,\s*+$/', '', $config_str_fixed );

			// Add curly brace to make a json object.
			$config_str_fixed = '{' . $config_str_fixed . '}';

			// Convert the fixed array definition to a PHP array.
			$config_array = json_decode( $config_str_fixed, true );

			// Check if decoding was successful.
			if ( JSON_ERROR_NONE === json_last_error() ) {
				return $config_array;
			} else {
				return array();
			}
		}

		return array();
	}
}
