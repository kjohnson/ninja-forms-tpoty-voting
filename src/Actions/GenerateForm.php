<?php

namespace TPOTY\Voting\Actions;

use TPOTY\Voting\Redirect;

class GenerateForm
{
    public function hook()
    {
        add_action('admin_post_tpoty_voting_generate_form', [$this, 'callback']);
    }

    public function callback()
    {
        check_admin_referer('tpoty_voting_generate_form');

        $source = array_filter($_POST['form'], function($form) {
            return $form;
        });

        $keys = array_keys($source);
        $values = array_values($source);

        $formID = reset($keys);
        $formPartKey = reset($values);

        if(!$formID) {
           return new Errors\MissingSourceForm();
        }

        $builder = new \TPOTY\Voting\Forms\VotingFormBuilder($formID, $formPartKey);

        return Redirect::ninjaForms();
    }
}