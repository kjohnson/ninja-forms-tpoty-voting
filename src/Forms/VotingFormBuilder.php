<?php

namespace TPOTY\Voting\Forms;

class VotingFormBuilder extends Builder
{
    protected $sourceForm;
    protected $sourceFields;
    protected $sourceSubmissions;

    protected $formContentData = [];

    protected $countCalculation = ''; // {field:...-8} + {field::...-16} = 2

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

            $mpFormContentData = [];
            foreach(array_chunk($this->formContentData, 10 * 2) as $formContentData) { // 10 entries per part, 2 rows per entry.
                $order = count($mpFormContentData);
                $part = [
                    'formContentData' => $formContentData,
                    'order' => $order,
                    'type' => 'part',
                    'clean' => true,
                    'title' => '#' . $order,
                    'key' => 'part-' . $order,
                ];
                $mpFormContentData[] = $part;
            }

            // Add submit button.
            $this->createField([
                'key' => "submit",
                'label' => "Submit",
                'order' => count($this->fields),
                'processing_label' => "Processing",
                'type' => "submit",
            ]);

            $mpFormContentData[] = [
                'formContentData' => [
                    'submit',
                ],
                'order' => count($mpFormContentData),
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
            $this->form->update_setting('formContentData', $mpFormContentData)->save();

            $this->form->update_setting('calculations',[
                [
                    'dec' => 1,
                    'eq' => $this->countCalculation,
                    'name' => 'count'
                ]
            ])->save();
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
                    'label' => 'Favourite?<br />' . sprintf('<img src="%s" />', $src),
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
            'label_pos' => 'right',
            'key' => 'shortlist-' . $id,
            'checked_calc_value' => 1,
            'unchecked_calc_value' => 0,
        ]);
        $this->countCalculation .= '{field:' . 'shortlist-' . $id . '} + ';
        $this->formContentData[] = [
            'cells' => [
                [
                    'fields' => [ 'shortlist-' . $id ],
                    'order' => 1,
                    'width' => 100,
                ]
            ],
            'order' => count($formContentData)
        ];

        $columns = [];
        foreach($options as $option) {
            $columns[] = [
                'fields' => [
                    "portfolio-{$id}-image-{$option['value']}"
                ],
                'order' => count($columns),
                'width' => 25
            ];
            $this->createField([
                'type' => 'checkbox',
                'label_pos' => 'below',
                'label' => $option['label'],
                'key' => "portfolio-{$id}-image-{$option['value']}",
            ]);
        }
        $this->formContentData[] = [
            'cells' => $columns,
            'order' => count($formContentData)
        ];
    }
}