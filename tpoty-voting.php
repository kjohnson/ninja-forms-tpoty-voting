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

(new \TPOTY\Voting\Admin\Submenu())->hook();

if(isset($_REQUEST['dynamic_form'])){
    add_action('init', function() {
        $builder = new \TPOTY\Voting\Forms\Builder();
        $builder->createForm([
            'title' => 'Dynamic Form ' . time(),
        ]);

        $time = time();
        $builder->createField([
            'type' => 'checkbox',
            'label' => 'Shortlist ' . $time,
            'label_pos' => 'above',
            'key' => 'shortlist-' . $time,
        ]);

        $time = time();
        $builder->createField([
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
        ]);
    });
}
