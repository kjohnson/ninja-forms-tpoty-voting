<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Field_TPOTYVotingExample
 */
class NF_TPOTYVoting_Fields_TPOTYVoting extends NF_Fields_Textbox
{
    protected $_name = 'tpoty-voting';

    protected $_section = 'common';

    protected $_type = 'textbox';

    protected $_templates = 'textbox';

    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'TPOTY Voting', 'ninja-forms' );
    }
}