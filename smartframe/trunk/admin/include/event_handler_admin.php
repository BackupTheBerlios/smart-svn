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
 * System event handler
 *
 *
 */

// Check if this file is included in the SMART environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

// Name of the event handler
define( 'MOD_SYSTEM' , 'SYSTEM' );

// register this handler                       
if (FALSE == $B->register_handler(MOD_SYSTEM,
                           array ( 'module'        => MOD_SYSTEM,
                                   'event_handler' => 'system_event_handler') ))
{
    trigger_error( 'The handler '.MOD_SYSTEM.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function system_event_handler( $evt )
{
    global $B;
    
    switch( $evt["code"] )
    {            
        case EVT_INIT:
            // Assign registered module handlers
            $B->mod_list = array();
            foreach ($B->handler_list as $key => $value)
            {
                if(($value['module'] != 'SYSTEM') && ($value['module'] != 'SETUP'))
                {
                    $B->mod_list[$key] =  $value;
                }
            }                 
            break; 
        case EVT_LOGOUT:  
            break;    
        case EVT_SETUP:         
            include_once(SF_BASE_DIR.'/admin/include/_setup.php');          
            break;             
        case EVT_SETUP_FINISH: 
            break;                
    }
}

?>
