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

            <h2>Generate Voting Forms</h2>

            <p>Generate a voting form based on a portfolio forms submissions.</p>

            <form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
                <input type="hidden" name="action" value="tpoty_voting_generate_form">
                <?php wp_nonce_field( 'tpoty_voting_generate_form' ); ?>

                <div style="margin-bottom:20px;">
                    <h2><label for="source">Select a Form</label></h2>
                    <select name="source" id="source">
                        <option value="">--</option>
                        <?php foreach(Ninja_Forms()->form()->get_forms() as $form): ?>
                        <option value="<?php echo $form->get_id(); ?>">
                            <?php echo $form->get_setting('title'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="button button-primary">Generate Voting Form</button>
            </form>

            <h2>Generate Shortlist Voting Forms</h2>

            <p>Generate a shortlist voting form based on voting form submissions.</p>

            <form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
                <input type="hidden" name="action" value="tpoty_voting_generate_shortlist_voting_form">
                <?php wp_nonce_field( 'tpoty_voting_generate_shortlist_voting_form' ); ?>

                <div style="margin-bottom:20px;">
                    <h2><label for="source">Select a Form</label></h2>
                    <select name="source" id="source">
                        <option value="">--</option>
                        <?php foreach(Ninja_Forms()->form()->get_forms() as $form): ?>
                        <option value="<?php echo $form->get_id(); ?>">
                            <?php echo $form->get_setting('title'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="button button-primary">Generate Voting Form</button>
            </form>
        </div>
        <?php
    }
}
