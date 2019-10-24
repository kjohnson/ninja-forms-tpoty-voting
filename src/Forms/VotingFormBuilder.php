<?php

namespace TPOTY\Voting\Forms;

class VotingFormBuilder extends Builder
{
    protected $sourceForm;
    protected $sourceFields;
    protected $sourceSubmissions;

    protected $formContentData;

    public function __construct($formID)
    {
        $this->sourceForm = Ninja_Forms()->form($formID)->get();
        $this->sourceFields = Ninja_Forms()->form($formID)->get_fields();
        $this->sourceSubmissions = array_reverse(Ninja_Forms()->form($formID)->get_subs());

        $this->createForm([
            'title' => '[Voting] ' . $this->sourceForm->get_setting('title') . ' ' . time(),
        ]);

        $this->createFields();

        // Multi-Part Forms
        $this->form->update_setting('formContentData', $this->formContentData)->save();
    }

    protected function createFields()
    {
        $sourceFields = array_filter($this->sourceFields, function($field) {
            return 'file_upload' === $field->get_setting('type');
        });

        $sourceFieldIDs = array_map(function($field) {
            return $field->get_id();
        }, $sourceFields);

        foreach($this->sourceSubmissions as $submission) {
            $options = array_map(function($fieldID, $submission) {
                $src = $submission->get_field_value($fieldID);
                if(is_array($src)) $src = reset($submission->get_field_value($fieldID));
                if(!$src) $src = 'https://placehold.it/500x500&text=Not+Submitted';
                return [
                    'label' => sprintf('<img src="%s" />', $src),
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

        $this->formContentData[] = [
            'formContentData' => [
                'shortlist-' . $id,
                'portfolio-' . $id,
            ],
            'order' => count($this->formContentData),
            'type' => 'part',
            'clean' => true,
            'title' => '#' . $id,
            'key' => 'part-' . $id,
        ];
    }
}