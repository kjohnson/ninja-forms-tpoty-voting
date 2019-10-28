<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * Plugin Name: TPOTY Voting
 * Plugin URI: 
 * Description: 
 * Version: 1.0.0
 * Author: Kyle B. Johnson
 * Author URI: https://kylebjohnson.me
 *
 * Copyright 2019 Kyle B. Johnson.
 */

include_once 'vendor/autoload.php';

(new \TPOTY\Voting\Admin\Submenu())->hook();
(new \TPOTY\Voting\Actions\GenerateForm())->hook();

add_action('wp_enqueue_scripts', function ($hook) {

    wp_enqueue_script( 'tpoty_voting_fancybox_script', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', ['jquery'] );
    wp_enqueue_script( 'tpoty_voting_fancybox_custom', plugins_url( 'src/Resources/fancybox.js', __FILE__ ), ['tpoty_voting_fancybox_script'], $ver = false, $in_footer = true );
    wp_enqueue_style( 'tpoty_voting_fancybox_styles', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css' );
 
});