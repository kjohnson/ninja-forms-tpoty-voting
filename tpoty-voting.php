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
(new \TPOTY\Voting\Actions\GenerateForm())->hook();
