<?php

/**
 * Plugin Name: Unicode to Bijoy Converter
 * Description: A plugin to convert Unicode to Bijoy and Bijoy to Unicode for Bangla text with voice typing support, including fixing broken text.
 * Version: 1.3
 * Author: Subrata Debnath
 * Plugin URI: https://wordpress.org/plugins/unicode-to-bijoy-converter
 * Author URI: https://profiles.wordpress.org/subrata-deb-nath
 * License: GPL2
 * Text Domain: unicode-to-bijoy-converter
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}



// Hook to add custom styles and scripts
function unicode_to_bijoy_converter_enqueue_assets()
{
    $plugin_url = plugin_dir_url(__FILE__);

    // Enqueue Tailwind CSS and custom styles
    wp_enqueue_style('tailwind-css', $plugin_url . 'config/css/tailwind.min.css', array(), '1.0.0');
    wp_enqueue_style('main-css', $plugin_url . 'config/css/main.css', array(), '1.0.0');

    // Enqueue JavaScript files
    $scripts = [
        'bijoy-js' => 'config/js/bijoy.js',
        'unicode-js' => 'config/js/unicode.js',
        'base-js' => 'config/js/base.js',
        'keymap-js' => 'config/js/keymap.js',
        'check-js' => 'config/js/check.js',
        'converter-js' => 'config/js/converter.js',
        'clear-js' => 'config/js/clear.js',
        'voice-js' => 'config/js/voice.js',
        'fixbijoy-js' => 'config/js/fixbijoy.js',
        'fixunicode-js' => 'config/js/fixunicode.js'
    ];

    foreach ($scripts as $handle => $path) {
        wp_enqueue_script($handle, $plugin_url . $path, array('jquery'), '1.0.0', true);
    }
}

add_action('wp_enqueue_scripts', 'unicode_to_bijoy_converter_enqueue_assets');

// Enqueue Sutonny MJ font
function enqueue_sutonny_mj_font()
{
    $font_css = plugin_dir_url(__FILE__) . 'config/fonts/sutonny-mj.css';
    wp_enqueue_style('sutonny-mj', $font_css, array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'enqueue_sutonny_mj_font');

// Shortcode to display the converter form
function unicode_to_bijoy_converter_shortcode()
{
    ob_start();
?>
    <div class="container mx-auto bg-blue-100 p-4 rounded-md">
        <h1 class="text-center text-3xl font-semibold text-blue-600">Unicode to Bijoy Converter</h1>
        <p class="text-center text-lg text-gray-700 mb-6">
            Looking for a Unicode to Bijoy font converter? Bangla Converter offers you Unicode to Bijoy, Bijoy to Unicode, and fixing broken text with voice typing support.
        </p>

        <div class="text-center my-4">
            <div class="text-center my-4">
                <div class="flex justify-center mt-4">
                    <button id="voice-typing-btn" onclick="startDictation()" type="button" class="relative flex items-center gap-2 px-4 py-2 text-white bg-blue-500 rounded-full shadow-lg hover:bg-blue-700 transition duration-300">
                        <span class="relative inline-flex w-8 h-8">
                            <span class="absolute inline-flex w-full h-full rounded-full bg-blue-400 opacity-75 animate-ping"></span>
                            <span class="relative flex items-center justify-center w-8 h-8 text-white bg-blue-600 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 006-6v-1.5m-6 7.5a6 6 0 01-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 01-3-3V4.5a3 3 0 116 0v8.25a3 3 0 01-3 3z"></path>
                                </svg>
                            </span>
                        </span>
                        <span class="text-sm font-medium">Click Here To Voice Typing</span>
                    </button>
                </div>
            </div>

        </div>

        <div class="my-4">
            <textarea class="unicode_textarea w-full h-32 p-2 border rounded-md border-gray-300" id="EDT" name="textarea" placeholder="ইউনিকোডে লেখা পেস্ট করুন"></textarea>
        </div>

        <div class="text-center mb-4 space-y-3 sm:space-y-0 sm:flex sm:justify-center sm:space-x-4">
            <button class="btn btn-success bg-green-500 text-white py-2 px-6 rounded-md hover:bg-green-700" onclick="ConvertToTextArea('CONVERTEDT')">Unicode to Bijoy</button>
            <button class="btn btn-warning bg-yellow-500 text-white py-2 px-6 rounded-md hover:bg-yellow-700" onclick="ConvertFromTextArea('CONVERTEDT')">Bijoy to Unicode</button>
            <button class="btn btn-blue bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-700" onclick="FixUnicode()">Fix Unicode Broken</button>
            <button class="btn btn-blue bg-blue-500 text-white py-2 px-6 rounded-md hover:bg-blue-700" onclick="FixBijoy()">Fix Bijoy Broken</button>
            <button class="btn btn-danger bg-red-500 text-white py-2 px-6 rounded-md hover:bg-red-700" onclick="ClearInput();">Clear Text</button>
        </div>

        <div class="my-4">
            <textarea class="bijoy_textarea w-full h-32 p-2 border rounded-md border-gray-300" id="CONVERTEDT" placeholder="বিজয় কি-বোর্ডর লেখা এখানে পেস্ট করুন"></textarea>
        </div>
    </div>

<?php
    return ob_get_clean();
}

add_shortcode('unicode_to_bijoy_converter', 'unicode_to_bijoy_converter_shortcode');
