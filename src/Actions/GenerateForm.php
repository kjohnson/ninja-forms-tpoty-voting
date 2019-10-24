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
            'title' => 'Dynamic Form Generated ' . time(),
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

        return Redirect::ninjaForms($builder->getFormID());
    }
}