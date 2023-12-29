<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'astra-theme-css' ) );

        // Encolar tus archivos CSS desde la carpeta "dist/assets"
        wp_enqueue_style('admin-style', get_stylesheet_directory_uri() . '/dist/assets/css/admin.css', array(), '1.0.0', 'all');
        wp_enqueue_style('common-style', get_stylesheet_directory_uri() . '/dist/assets/css/common.css', array(), '1.0.0', 'all');
        wp_enqueue_style('public-style', get_stylesheet_directory_uri() . '/dist/assets/css/public.css', array(), '1.0.0', 'all');
    }
endif;

function enqueue_custom_scripts() {
    wp_register_script('custom-scripts', get_stylesheet_directory_uri() . '/dist/assets/js/common.min.js', array('jquery'), '1.0', true);
    wp_enqueue_script('custom-scripts');
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

include_once("inc/wp-actions.php");
include_once("inc/wp-filters.php");
include_once('inc/wp-shortcodes.php');
include_once('inc/wp-hubspot.php');
include_once('inc/custom-shortcodes.php');
include_once('inc/wp-hubspot.php');