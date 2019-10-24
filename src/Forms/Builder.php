<?php

namespace TPOTY\Voting\Forms;

class Builder
{
    protected $form;
    protected $fields;

    public function __construct()
    {
        //
    }

    public function createForm($settings)
    {
        $form = Ninja_Forms()->form()->get();
        $form->update_settings( $settings )->save();
        $this->form = $form;
    }

    public function createField($settings)
    {
        $field = Ninja_Forms()->form( $this->form->get_id() )->field()->get();
        $field->update_settings( $settings )->save();
        $this->fields[] = $field;
    }
}