<?php

namespace TPOTY\Voting\Forms;

class VotingFormBuilder extends Builder
{
    public function __construct($formID)
    {
        $this->createForm([
            'title' => 'TPOTY Voting ' . time(),
        ]);

        $submissions = Ninja_Forms()->form($formID)->get_subs();
        $submissions = array_reverse($submissions);
        array_walk($submissions, [$this, 'createPortfolioFields']);
    }

    protected function createPortfolioFields($submission)
    {
        $this->createField([
            'type' => 'checkbox',
            'label' => 'Shortlist Protfolio #' . $submission->get_id(),
            'label_pos' => 'above',
            'key' => 'shortlist-' . $submission->get_id(),
        ]);

        $this->createField([
            'type' => 'listcheckbox',
            'label' => 'Portfolio #' . $submission->get_id(),
            'label_pos' => 'above',
            'key' => 'portfolio-' . $submission->get_id(),
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
}