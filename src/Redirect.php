<?php

namespace TPOTY\Voting;

class Redirect
{
    public static function redirect($url, $status = 302)
    {
        wp_redirect( $url, $status = 302 );
        exit;
    }

    public static function ninjaForms($formID = false)
    {
        $args = [
            'page' => 'ninja-forms'
        ];

        if($formID) $args['form_id'] = $formID;

        $url = add_query_arg($args, admin_url());

        self::redirect($url);
    }
}