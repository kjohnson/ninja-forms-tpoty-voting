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

// TODO: PHP Version Check.
// TODO: Ninja Forms Version Check.

include_once 'vendor/autoload.php';

(new \TPOTY\Admin\Submenu())->hook();

if(isset($_REQUEST['dynamic_form'])){
    add_action('init', function() {

        $settings = array(
            'title' => 'Dynamic Form ' . time(),
        );

        $form = Ninja_Forms()->form()->get();
        $form->update_settings( $settings )->save();

        $time = time();
        $settings = array(
            'type' => 'checkbox',
            'label' => 'Shortlist ' . $time,
            'label_pos' => 'above',
            'key' => 'shortlist-' . $time,
        ); 

        $field = Ninja_Forms()->form( $form->get_id() )->field()->get();
        $field->update_settings( $settings )->save();

        $time = time();
        $settings = array(
            'type' => 'listcheckbox',
            'label' => 'Portfolio ' . $time,
            'label_pos' => 'above',
            'key' => 'portfolio-' . $time,
            'options' => [
                [
                    'label' => '<img src="https://placehold.it/200x200&text=1" />',
                    'value' => 1,
                ],
                [
                    'label' => '<img src="https://placehold.it/200x200&text=2" />',
                    'value' => 2,
                ],
            ]
        ); 

        $field = Ninja_Forms()->form( $form->get_id() )->field()->get();
        $field->update_settings( $settings )->save();
    });
}
