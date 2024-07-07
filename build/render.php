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

// Weather
//$weather = get_weather_data($city);


// Add Classes & Styles to Block Wrapper Attributes
$styles = get_block_wrapper_attributes( ['class' =>  'built-weather'] );

?>

<div <?php echo wp_kses_data( $styles ); ?>>
  <h2><?php echo $city; ?></h2>
</div>