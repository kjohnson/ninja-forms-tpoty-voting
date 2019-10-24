<?php

namespace TPOTY\Voting;

class Redirect
{
    public static function redirect($url, $status = 302)
    {
        wp_redirect( $url, $status = 302 );
        exit;
    }

    public static function ninjaForms($formID)
    {
        $url = add_query_arg([
            'page' => 'ninja-forms', 'form_id' => $formID
        ], admin_url());

        self::redirect($url);
    }
}