<?php

namespace TPOTY\Voting\Forms;

class Builder
{
    protected $form;
    protected $fields;
    protected $actions;

    public function __construct()
    {
        //
    }

    public function createForm($settings)
    {
        global $wpdb;
        // $form = Ninja_Forms()->form()->get();
        $form = new \NF_Database_Models_Form( $wpdb, null );
        $form->update_settings( $settings )->save();
        $this->form = $form;
    }

    public function getFormID()
    {
        return $this->form->get_id();
    }

    public function createField($settings)
    {
        $field = Ninja_Forms()->form( $this->form->get_id() )->field()->get();
        $field->update_settings( $settings )->save();
        $this->fields[] = $field;
    }

    public function createAction($settings) {
        $action = Ninja_Forms()->form( $this->form->get_id() )->action()->get();
        $action->update_settings( $settings )->save();
        $this->actions[] = $action;
    }
}