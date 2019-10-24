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

        $builder = new \TPOTY\Voting\Forms\Builder();
        $builder->createForm([
            'title' => 'TPOTY Voting ' . time(),
        ]);

        $formID = filter_var($_POST['source'], FILTER_SANITIZE_NUMBER_INT);

        foreach(Ninja_Forms()->form($formID)->get_subs() as $sub) {

            $time = time();
            $builder->createField([
                'type' => 'checkbox',
                'label' => 'Shortlist Protfolio #' . $sub->get_id(),
                'label_pos' => 'above',
                'key' => 'shortlist-' . $sub->get_id(),
            ]);

            $time = time();
            $builder->createField([
                'type' => 'listcheckbox',
                'label' => 'Portfolio #' . $sub->get_id(),
                'label_pos' => 'above',
                'key' => 'portfolio-' . $sub->get_id(),
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
        }

        return Redirect::ninjaForms($builder->getFormID());
    }
}