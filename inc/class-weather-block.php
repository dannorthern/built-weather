<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * ------------------------------------------------------------------
 * Helpers
 * ------------------------------------------------------------------
 * 
 * Contains utility and helper functions.
 * 
 * @package BuiltWeather
 * 
 * */
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

/**
* WeatherBlock Class
*
* Handles the creation and management of a weather block using OpenWeatherMap API.
*/
class WeatherBlock {
  /**
   * API key for OpenWeatherMap
   *
   * @var string
   */
  protected $api_key;

  /**
   * Option name for storing the API key in WordPress options
   *
   * @var string
   */
  protected $option_name = 'weather_block_api_key';

  /**
   * Constructor
   */
  public function __construct() {
      add_action( 'rest_api_init', array( $this, 'register_weather_api_routes' ) );
      add_action( 'admin_init', array( $this, 'register_settings' ) );
      $this->api_key = get_option( $this->option_name );
  }

  /**
   * Register custom REST API routes
   */
  public function register_weather_api_routes() {
      register_rest_route( 'built-weather/v1', '/weather', array(
          'methods'             => 'GET',
          'callback'            => array( $this, 'get_weather_api' ),
          'permission_callback' => function () {
              return current_user_can( 'edit_posts' );
          },
      ) );
  }

  /**
   * Handle GET requests for weather data
   *
   * @param WP_REST_Request $request The request object.
   * @return WP_REST_Response|WP_Error The response object on success, or WP_Error on failure.
   */
  public function get_weather_api( $request ) {
      $city = sanitize_text_field( $request->get_param( 'city' ) );
      $units = sanitize_text_field( $request->get_param( 'units' ) );
      $weather = $this->get_weather_data( $city, $units );

      if ( is_wp_error( $weather ) ) {
          return new WP_Error( 'weather_error', __( 'Error fetching weather data', 'built-weather' ), array( 'status' => 500 ) );
      }

      return rest_ensure_response( $weather );
  }

  /**
   * Fetch weather data from OpenWeatherMap API
   *
   * @param string $city The city name.
   * @return array|WP_Error The weather data on success, or WP_Error on failure.
   */
  protected function get_weather_data( $city = 'Cleveland', $units = 'metric' ) {
    if ( empty( $this->api_key ) ) {
        return new WP_Error( 'api_key_missing', __( 'OpenWeatherMap API key is not set', 'built-weather' ) );
    }

    $url = add_query_arg(
        array(
            'q'     => urlencode( $city ),
            'appid' => $this->api_key,
            'units' => $units,
        ),
        'http://api.openweathermap.org/data/2.5/weather'
    );

      $response = wp_remote_get( $url );
      if ( is_wp_error( $response ) ) {
          return new WP_Error( 'api_error', __( 'Error fetching weather data', 'built-weather' ) );
      }

      $data = json_decode( wp_remote_retrieve_body( $response ), true );
      if ( ! $data ) {
          return new WP_Error( 'api_error', __( 'Error parsing weather data', 'built-weather' ) );
      }

      return array(
          'temp'        => round( $data['main']['temp'] ),
          'description' => sanitize_text_field( $data['weather'][0]['description'] ),
          'icon'        => sanitize_text_field( $data['weather'][0]['icon'] ),
      );
  }

  /**
   * Register settings for the API key
   */
  public function register_settings() {
      register_setting(
          'general',
          $this->option_name,
          array(
              'type'              => 'string',
              'sanitize_callback' => 'sanitize_text_field',
              'default'           => '',
          )
      );
      
      add_settings_field(
          $this->option_name,
          __( 'OpenWeatherMap API Key', 'built-weather' ),
          array( $this, 'api_key_callback' ),
          'general'
      );
  }

  /**
   * Callback for rendering the API key input field
   */
  public function api_key_callback() {
      echo '<input type="text" id="' . esc_attr( $this->option_name ) . '" name="' . esc_attr( $this->option_name ) . '" value="' . esc_attr( $this->api_key ) . '" class="regular-text">';
      echo '<p class="description">' . __( 'Enter your OpenWeatherMap API key. You can get one from <a href="https://openweathermap.org/api" target="_blank">OpenWeatherMap</a>.', 'built-weather' ) . '</p>';
  }
}

// Initialize the plugin
new WeatherBlock();
