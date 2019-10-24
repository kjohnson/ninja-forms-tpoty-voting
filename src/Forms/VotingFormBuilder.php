<?php

namespace TPOTY\Voting\Forms;

class VotingFormBuilder extends Builder
{
    protected $sourceForm;
    protected $sourceFields;
    protected $sourceSubmissions;

    public function __construct($formID)
    {
        $this->createForm([
            'title' => 'TPOTY Voting ' . time(),
        ]);

        $this->sourceForm = Ninja_Forms()->form($formID)->get_form();
        $this->sourceFields = Ninja_Forms()->form($formID)->get_fields();
        $this->sourceSubmissions = array_reverse(Ninja_Forms()->form($formID)->get_subs());

        $this->createFields();
    }

    protected function createFields()
    {
        $sourceFields = array_filter($this->sourceFields, function($field) {
            return 'firstname' === $field->get_setting('type') || 'lastname' === $field->get_setting('type');
        });

        $sourceFieldIDs = array_map(function($field) {
            return $field->get_id();
        }, $sourceFields);

        foreach($this->sourceSubmissions as $submission) {
            $options = array_map(function($fieldID, $submission) {
                return [
                    'label' => $submission->get_field_value($fieldID), //sprintf('<img src="https://placehold.it/200x200&text=%s" />', $submission->get_field_value($fieldID)),
                    'value' => $fieldID,
                ];
            }, $sourceFieldIDs, array_fill(0, count($sourceFieldIDs), $submission));
            $this->createPortfolioFields($submission->get_id(), $options);
        }
    }

    protected function createPortfolioFields($id, array $options)
    {
        $this->createField([
            'type' => 'checkbox',
            'label' => 'Shortlist Protfolio #' . $id,
            'label_pos' => 'left',
            'key' => 'shortlist-' . $id,
        ]);

        $this->createField([
            'type' => 'listcheckbox',
            'label' => 'Portfolio #' . $id,
            'label_pos' => 'left',
            'key' => 'portfolio-' . $id,
            'options' => $options
        ]);
    }
}