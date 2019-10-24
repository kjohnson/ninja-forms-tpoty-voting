<?php

namespace TPOTY\Voting\Admin;

class Submenu
{
    public function hook()
    {
        add_action('admin_menu', [ $this, 'register']);
    }

    public function register()
    {
        add_submenu_page(
            'tools.php',
            __( 'TPOTY Voting Forms' ),
            __( 'TPOTY Voting Forms' ),
            'manage_options',
            'tpoty-voting-forms',
            [$this, 'callback']
        );
    }

    public function callback()
    {
        echo '<div class="wrap">Here</div>';
    }
}
