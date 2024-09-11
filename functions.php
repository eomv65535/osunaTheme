<?php
/**
 * OsunaTheme functions and definitions
 *
 * @package OsunaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! defined( 'OSUNATHEME_VERSION' ) ) {
    // Define the theme version.
    define( 'OSUNATHEME_VERSION', '1.0.0' );
}

// Include the main OsunaTheme class.
require get_template_directory() . '/inc/class-osunatheme.php';

// Instantiate the OsunaTheme class to initialize the theme.
new OsunaTheme();