<?php

namespace TPOTY\Voting\Forms;

class ShortlistVotingFormBuilder extends Builder
{
    protected $sourceForm;
    protected $sourceFields;
    protected $sourceSubmissions;

    public function __construct($formID)
    {
        $this->createForm([
            'title' => 'TPOTY Shortlist Voting ' . time(),
        ]);

        $this->sourceForm = Ninja_Forms()->form($formID)->get_form();
        $this->sourceFields = Ninja_Forms()->form($formID)->get_fields();

        $this->sourceSubmissions = array_reverse(Ninja_Forms()->form($formID)->get_subs());

        $this->createFields();
    }

    protected function createFields()
    {
        $sourceFields = array_filter($this->sourceFields, function($field) {
            return 0 === strpos($field->get_setting('key'), 'shortlist');
        });

        $shortlistFields = [];
        foreach($sourceFields as $sourceField) {
            foreach($this->sourceSubmissions as $submission) {
                if($submission->get_field_value($sourceField->get_id())) {
                    $shortlistFields[] = $sourceField;
                }
            }
        }

        foreach($shortlistFields as $shortlistField) {
            $this->createField([
                'type' => 'checkbox',
                'label' => 'Portfolio #' . $shortlistField->get_setting('key'),
                'label_pos' => 'left',
                'key' => 'shortlist-' . $shortlistField->get_setting('key'),
            ]);
        }
    }
}
