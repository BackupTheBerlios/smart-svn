<?php


/**
 * Test module event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_MAILARCHIVER' , 'MAILARCHIVER');

// Version of this modul
define ( 'MOD_MAILARCHIVER_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler(MOD_MAILARCHIVER,
                                     array ( 'module'        => MOD_MAILARCHIVER,
                                             'event_handler' => 'mailarchiver_event_handler') ))
{
    trigger_error( 'The handler '.MOD_MAILARCHIVER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function mailarchiver_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_LOAD_MODULE:
            // set the base template for this module
            include(SF_BASE_DIR . '/admin/modules/mailarchiver/module_loader.php');           
            break;             
        case EVT_INIT: 
            // check for install or upgrade
            if (MOD_MAILARCHIVER_VERSION != $B->sys['module']['mailarchiver']['version'])
            {
                $B->setup_error = array();
                $B->conf_val = &$B->sys;
                include(SF_BASE_DIR . '/admin/modules/mailarchiver/_setup.php'); 
                $B->conf->setConfigValues( $B->conf_val );
                $B->conf->writeConfigFile( "config_system.xml.php", array('filetype' => 'xml', 'mode' => 'pretty') );               
                if( count($B->setup_error) > 0 )
                    trigger_error( "Setup/upgrade error: ".var_export($B->setup_error, TRUE)."\n".__FILE__."\n".__LINE__, E_USER_ERROR  );
            }       
            break; 
        case EVT_LOGOUT:  
            break;  
        case EVT_SETUP:       
            if( count($base->tmp_error) == 0 )
            {
                include(SF_BASE_DIR.'/admin/modules/mailarchiver/_setup.php'); 
            }
            break;            
    }
}

?>
