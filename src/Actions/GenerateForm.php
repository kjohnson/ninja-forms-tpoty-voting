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

        $formID = filter_var($_POST['source'], FILTER_SANITIZE_NUMBER_INT);

        if(!$formID) {
           return new Errors\MissingSourceForm();
        }

        $builder = new \TPOTY\Voting\Forms\VotingFormBuilder($formID);

        return Redirect::ninjaForms($builder->getFormID());
    }
}