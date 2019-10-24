<?php

namespace TPOTY\Voting\Actions\Errors;

use WP_Error as Error;

class MissingSourceForm
{
    public function __construct()
    {
        $code = 500;
        $title = __('Voting Form Error');
        $message = __('A source form is requried.');
        return wp_die(new Error($code, $message), $title, [ 'back_link' => true ]);
    }
}