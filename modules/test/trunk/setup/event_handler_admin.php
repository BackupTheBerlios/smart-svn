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
 * Admin setup module event handler
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_SETUP' , 'SETUP');

// Version of this modul
define ( 'MOD_SETUP_VERSION' , '0.1');

// register this handler                       
if (FALSE == $B->register_handler( 
                            MOD_SETUP,
                            array ( 'module'          => MOD_SETUP,
                                    'event_handler'   => 'setup_event_handler',
                                    'menu_visibility' => FALSE) ))
{
    trigger_error( 'The handler '.MOD_SETUP.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}    
                                        
                                        
                                        
// The handler function
function setup_event_handler( $evt )
{
    global $B;

    switch( $evt["code"] )
    {
        case EVT_SETUP:
            $B->conf_val = array();
            // Init error array
            $B->setup_error = array();

            // launch setup
            if( $_POST['do_setup'] )
            {
                // Send a setup message to the system handler
                $success = $B->M( MOD_SYSTEM,           EVT_SETUP );
  
                // Send a setup message to the common handler
                if($success == TRUE)    
                    $success = $B->M( MOD_COMMON,        EVT_SETUP );
    
                // Send a setup message to the entry handler
                if($success == TRUE)    
                    $success = $B->M( MOD_DEFAULT,       EVT_SETUP );
    
                // Send a setup message to the test handler
                if($success == TRUE)
                    $success = $B->M( MOD_TEST,         EVT_SETUP );
    
                // Send a setup message to the option handler
                if($success == TRUE)
                    $success = $B->M( MOD_OPTION,       EVT_SETUP );
        
                // check on errors before proceed
                if( $success == TRUE )
                {   
                    // write the system config file
                    $B->conf_val['info']['status'] = TRUE;
        
                    // include PEAR Config class
                    include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/Config.php');

                    $c = new Config();
                    $root =& $c->parseConfig($B->conf_val, 'PHPArray');
                    
                    // write config array
                    $c->writeConfig(SF_BASE_DIR . '/admin/modules/common/config/config.php', 'PHPArray', array('name' => 'B->sys'));
        
                    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
                    exit;  
                }
            }

            // Include the setup template
            include(  SF_BASE_DIR . '/admin/modules/setup/index.tpl.php' ); 

            // Send the output buffer to the client
            if( SF_OB == TRUE)
            {
                ob_end_flush();
            }

            // Basta
            exit;
            break;        
    } 
}


?>
