<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * ------------------------------------------------------------------
 * Block: Weather
 * ------------------------------------------------------------------
 * 
 * This file renders the Weather Block
 * 
 * @package BuiltWeather
 * 
 * */




 // Block city attributes
if ( !empty( $attributes['city'] ) ) :
  $city = wp_kses_post( $attributes['city'] );
else: 
  $city = 'London';
endif;

 // Block units attributes
 if ( !empty( $attributes['units'] ) ) :
  $units = wp_kses_post( $attributes['units'] );
else: 
  $units = 'Imperial';
endif;

// Set unit symbol
if ( 'metric' == $units ) :
  $unit_symbol = '°C';
else :
  $unit_symbol = '°F';
endif;

// Weather
$weather_block_class = new WeatherBlock();
$weather = $weather_block_class->get_weather_data($city, $units);



// Add Classes & Styles to Block Wrapper Attributes
$styles = get_block_wrapper_attributes( ['class' =>  'built-weather'] );

?>

<div <?php echo wp_kses_data( $styles ); ?>>
  <h2><?php echo $city; ?></h2>
  <img src='http://openweathermap.org/img/w/<?php echo $weather['icon']; ?>.png' alt='<?php echo $weather['description']; ?>'>
  <p><?php echo $weather['temp']; ?><?php echo $unit_symbol ; ?></p>
  <p><?php echo $weather['description']; ?></p>
</div>