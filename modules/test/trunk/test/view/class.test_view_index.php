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
 * test_view_index class of the template "index.tpl.php"
 *
 */
 
class test_view_index
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
    function test_view_index()
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
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // Execute a specific module action
        // Here we listen to the "evalform" action
        //
        if($_REQUEST['action'] == 'evalform')
        {
            // just assign the form data to the template var
            $this->B->tpl_test_form_text = $_POST['testfield'];
        }
            
        // assign some template vars
        // these vars were included in template index.tpl.php of this module
        //
        $this->B->tpl_test_title      = "Test module";
        $this->B->tpl_test_intro_text = "This module does currently nothing else than
                                         print out this text, some array variables, 
                                         evaluate form data and it provide a couple of
                                         public template action classes (see: public templates).";
            
        // assign an template array with numbers
        $this->B->tpl_test_counter = array();
        for($i=0;$i<11;$i++)
            $this->B->tpl_test_counter[] = $i;
            
        return TRUE;
    }    
}

?>
