<?php
/**
 * Plugin Name:       Built Weather Block
 * Description:       Block that pulls in the weather from a location of your choice using OpenWeather Map.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.2.0
 * Author:            Dan Northern
 * Author URI:        https://dannorthern.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       built-weather
 *
 * @package BuiltWeather
 */

 //namespace BuiltNorth\BuiltWeather\Core;

 
/**
 * If called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) { die; }


/**
 * Define plugin version.
 * @link https://semver.org
 */
define( 'BUILT_WEATHER_VERSION', '0.2.0' );


/**
 * Define global constants.
 */
define( 'BUILT_WEATHER_URL', plugin_dir_url( __FILE__ ) );
define( 'BUILT_WEATHER_PATH', plugin_dir_path( __FILE__ ) );


// Include the WeatherBlock class
require_once BUILT_WEATHER_PATH . 'inc/class-weather-block.php';

/**
 * Register the block.
 */
add_action( 'init', __NAMESPACE__ . '\weather_block_init' );
function weather_block_init() {
	register_block_type( BUILT_WEATHER_PATH . '/build' );
}


// Initialize the plugin
function init_weather_block_plugin() {
    new WeatherBlock();
}
add_action('plugins_loaded', 'init_weather_block_plugin');