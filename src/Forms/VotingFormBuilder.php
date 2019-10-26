<?php

namespace TPOTY\Voting\Forms;

class VotingFormBuilder extends Builder
{
    protected $sourceForm;
    protected $sourceFields;
    protected $sourceSubmissions;

    protected $formContentData = [];

    public function __construct($formID, $formPartKey)
    {
        $this->sourceForm = Ninja_Forms()->form($formID)->get();
        $this->sourceFields = Ninja_Forms()->form($formID)->get_fields();
        $this->sourceSubmissions = array_reverse(Ninja_Forms()->form($formID)->get_subs());

        if(!count($this->sourceSubmissions)) wp_die('No submissions found. Cannot generate voting form.');

        $parts = $this->sourceForm->get_setting('formContentData');
        $parts = array_filter($parts, function($part) use ($formPartKey) {
            return $part['key'] == $formPartKey;
        });
        $part = reset($parts);


            $title = $part['title'];
            $this->createForm([
                'title' => '[Voting] ' . $this->sourceForm->get_setting('title') . ' [' . $title . '] - ' . time(),
            ]);
            $this->createFields($part['formContentData']);

            foreach(array_chunk($this->fields, 10 * 2) as $fields) { // 10 entries per part, 2 fields per entry.
                $order = count($this->formContentData) + 1;
                $part = [
                    'formContentData' => [],
                    'order' => $order,
                    'type' => 'part',
                    'clean' => true,
                    'title' => '#' . $order,
                    'key' => 'part-' . $order,
                ];
                foreach($fields as $field) {
                    $part['formContentData'][] = $field->get_setting('key');
                }
                $this->formContentData[] = $part;
            }

            // Add submit button.
            $this->createField([
                'key' => "submit",
                'label' => "Submit",
                'order' => count($this->fields),
                'processing_label' => "Processing",
                'type' => "submit",
            ]);

            $this->formContentData[] = [
                'formContentData' => [
                    'submit',
                ],
                'order' => count($this->formContentData),
                'type' => 'part',
                'clean' => true,
                'title' => 'Submit',
                'key' => 'part-submit',
            ];

            // Add Success Message
            $this->createAction([
                'active' => "1",
                'label' => "This Success Message",
                'success_msg' => "Your form has been successfully submitted.",
                'type' => "successmessage",
            ]);

            // Add Store Submission
            $this->createAction([
                'active' => "1",
                '​​​exception_fields' => [],
                "fields-save-toggle" => "save_all",
                'label' => "Store Submission",
                'message' => "This action adds users to WordPress' personal data export tool, allowing admins to comply with the GDPR and other privacy regulations from the site's front end.",
                'set_subs_to_expire' => "0",
                'submitter_email' => "",
                'subs_expire_time' => "90",
                'type' => "save",
            ]);

            // Multi-Part Forms
            $this->form->update_setting('formContentData', $this->formContentData)->save();
    }

    protected function createFields($fieldKeys)
    {

		// Adjust for L&S
		$fieldKeys = array_map(function($fieldKey) {
			if(is_array($fieldKey) && isset($fieldKey['cells'])) {
				$fieldKeys = [];
				foreach($fieldKey['cells'] as $cell) {
					if(!isset($cell['fields'])) continue;
					foreach($cell['fields'] as $field) {
						$fieldKeys[] = $field;
					}
				}
				return $fieldKeys;
			} else {
				return $fieldKey;
			}
		}, $fieldKeys);
		
		
		$flatFieldKeys = [];
		foreach($fieldKeys as $fieldKey) {
			if(is_array($fieldKey)) {
				$flatFieldKeys = array_merge($flatFieldKeys, $fieldKey);
			} else {
				$flatFieldKeys[] = $fieldKey;
			}
		}
		// Get back to normal...
		$fieldKeys = $flatFieldKeys;

        $sourceFields = array_filter($this->sourceFields, function($field) use ($fieldKeys) {
            return 'file_upload' === $field->get_setting('type') && in_array($field->get_setting('key'), $fieldKeys);
        });

        $sourceFieldIDs = array_map(function($field) {
            return $field->get_id();
        }, $sourceFields);

        foreach($this->sourceSubmissions as $submission) {
            $options = array_map(function($fieldID, $submission) {
                $src = $submission->get_field_value($fieldID);
                if(is_array($src)) $src = reset($submission->get_field_value($fieldID));
                if(!$src) return false;
                return [
                    'label' => sprintf('<img src="%s" />', $src),
                    'value' => $fieldID,
                ];
            }, $sourceFieldIDs, array_fill(0, count($sourceFieldIDs), $submission));
			$options = array_filter($options, function($option) {
				return $option;
			});
			if(!$options || empty($options)) continue;
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