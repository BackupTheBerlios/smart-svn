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
                            array ( 'module'        => MOD_SETUP,
                                    'event_handler' => 'setup_event_handler') ))
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
            include SF_BASE_DIR . '/admin/modules/setup/index.php';
            break;        
    } 
}


?>
