<?php
/**
 * Plugin Name: Perseo Multilang GPT
 * Description: A custom multilanguage plugin using GPT-3.5 Turbo API.
 * Version: 1.0
 * Author: Giovanni Manetti
 */

// Include API Key
include_once('api_key.php');

// Include other files
include_once('includes/api.php');
include_once('includes/widget.php');

// Enqueue JS
function enqueue_custom_scripts() {
    global $api_key;
    wp_enqueue_script('perseo-functions', plugin_dir_url(__FILE__) . 'includes/functions.js', array(), '1.0', true);
    wp_localize_script('perseo-functions', 'perseo_params', array(
        'api_key' => $api_key
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Enqueue CSS
function enqueue_custom_styles() {
    wp_enqueue_style('perseo-styles', plugin_dir_url(__FILE__) . 'style.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

// Register REST API
add_action('rest_api_init', function () {
    register_rest_route('perseo/v1', '/translate_batch/', array(  
      'methods' => 'POST',
      'callback' => 'handle_translation_request',
    ));
});

// REST API function to handle batch translation
function handle_translation_request(WP_REST_Request $request) {
    $texts = $request->get_param('texts');
    $source_lang = $request->get_param('source_lang');
    $target_lang = $request->get_param('target_lang');
    $translated_texts = translate_text($texts, $source_lang, $target_lang);
    return new WP_REST_Response(array('translated_texts' => $translated_texts), 200);
}
