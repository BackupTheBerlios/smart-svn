<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * TEST_SYS_LOAD_MODULE class 
 *
 */
 
class TEST_SYS_LOAD_MODULE
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function TEST_SYS_LOAD_MODULE()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Load the test module (assign template vars and define the template file)
     *
     * @param array $data
     */
    function perform( $data )
    {
            // Load a specific module feature
            // 'mf' stay for 'module feature'
            // this var is defined in the test template index.tpl.php
            //
            if($_REQUEST['mf'] == 'evalform')
            {
                // just assign the form data to the template var
                $this->B->tpl_test_form_text = $_POST['testfield'];
            }
            
            // assign some template vars
            // these vars were included in template index.tpl.php of this module
            //
            $this->B->tpl_test_title      = "Test module";
            $this->B->tpl_test_intro_text = "This module does currently nothing else than print out this text, some array variables and evaluate form data.";
            
            // assign an template array with numbers
            $this->B->tpl_test_counter = array();
            for($i=0;$i<11;$i++)
                $this->B->tpl_test_counter[] = $i;
            
            // set the base template for this module
            $this->B->module = SF_BASE_DIR . '/admin/modules/test/templates/index.tpl.php'; 
            
            return TRUE;
    }    
}

?>
