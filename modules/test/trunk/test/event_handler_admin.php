<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Admin TEST module event handler
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_TEST' , 'TEST');

// Version of this modul
define ( 'MOD_TEST_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler( 
                            MOD_TEST,
                            array ( 'module'        => MOD_TEST,
                                    'event_handler' => 'test_event_handler') ))
{
    trigger_error( 'The handler '.MOD_TEST.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                                                          
// The handler function
function test_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_LOAD_MODULE:
            // Load a specific module feature
            // 'mf' stay for module feature
            // this var is defined in the test template index.tpl.php
            //
            if($_REQUEST['mf'] == 'evalform')
            {
                // just assign the form data to the template var
                $B->tpl_test_form_text = $_POST['testfield'];
            }
            
            // assign some template vars
            // these vars were included in template index.tpl.php of this module
            //
            $B->tpl_test_title      = "Test module";
            $B->tpl_test_intro_text = "This module does currently nothing else than print out this text, some array variables and evaluate form data.";
            
            // assign an array
            $B->tpl_test_counter = array();
            for($i=0;$i<11;$i++)
                $B->tpl_test_counter[] = $i;
            
            // set the base template for this module
            $B->module = SF_BASE_DIR . '/admin/modules/test/templates/index.tpl.php';          
            break;             
        case EVT_INIT:
            // Check for upgrade  
            if(MOD_TEST_VERSION != (string)$B->sys['module']['test']['version'])
            {
                // set the new version num of this module
                $B->sys['module']['test']['version']  = MOD_TEST_VERSION;
                $B->system_update_flag = TRUE;  
                
                // include here additional upgrade code
            }
            break;               
        case EVT_LOGOUT:  
            // just destroying session
            $B->session->destroy();
            
            // include here additional clean up code
            
            // exit to the main public page
            @header('Location: '.SF_BASE_LOCATION.'/index.php');
            exit;
            break;               
        case EVT_SETUP:  
            // init the success var
            $success = TRUE;
            
            // include here all stuff to get work this module:
            // creating db tables

            // The module name and version
            // these array vars were saved later by the setup handler
            // in the file /admin/config/config_system.xml.php
            //
            $B->conf_val['module']['test']['name']     = 'test';
            $B->conf_val['module']['test']['version']  = MOD_TEST_VERSION;
            $B->conf_val['module']['test']['mod_type'] = 'test';
            $B->conf_val['module']['test']['info']     = 'This is a test modul';
            
            // if noting is going wrong $success is still TRUE else FALSE
            // ex.: if creating db tables fails you must set this var to false
            return $success;
            break;            
    } 
}

?>
