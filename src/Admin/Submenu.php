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
            <form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">
                <input type="hidden" name="action" value="tpoty_voting_generate_form">
                <?php wp_nonce_field( 'tpoty_voting_generate_form' ); ?>

                <label for="source">
                    <select name="source" id="source">
                    <?php foreach(Ninja_Forms()->form()->get_forms() as $form): ?>
                        <option value="<?php echo $form->get_id(); ?>">
                            <?php echo $form->get_setting('title'); ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </label>

                <button type="submit">Generate Voting Form</button>
            </form>
        </div>
        <?php
        echo '<div class="wrap">Here</div>';
    }
}
