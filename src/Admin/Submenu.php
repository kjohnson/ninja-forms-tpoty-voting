<?php

namespace TPOTY\Voting\Admin;

class Submenu
{
    public function hook()
    {
        add_action('admin_menu', [ $this, 'register']);
    }

    public function register()
    {
        add_submenu_page(
            'tools.php',
            __( 'TPOTY Voting Forms' ),
            __( 'TPOTY Voting Forms' ),
            'manage_options',
            'tpoty-voting-forms',
            [$this, 'callback']
        );
    }

    public function callback()
    {
        ?>
        <div class="wrap">

            <h1>Generate Voting Forms</h1>

            <p>Generate a voting form based on a portfolio forms submissions.</p>

            <form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
                <input type="hidden" name="action" value="tpoty_voting_generate_form">
                <?php wp_nonce_field( 'tpoty_voting_generate_form' ); ?>

                <div style="margin-bottom:20px;">
                    <h2><label for="source">Select a Form</label></h2>

                    <?php foreach(Ninja_Forms()->form()->get_forms() as $form): ?>
                        <?php
                        $formContentData = $form->get_setting('formContentData');
                        $isMultiPart = (is_array($formContentData) && isset($formContentData[0]['formContentData']));
                        if(!$isMultiPart) continue;
                        ?>
                        <h3><?php echo $form->get_setting('title'); ?></h3>
                        <select name="form[<?php echo $form->get_id(); ?>]">
                            <option value=""></option>
                            <?php foreach($formContentData as $part): ?>
                            <option value="<?php echo $part['key']; ?>">
                                <?php echo $part['title']; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                        <?php endforeach; ?>
                </div>

                <button type="submit" class="button button-primary">Generate Voting Form</button>
            </form>
        </div>
        <?php
    }
}
