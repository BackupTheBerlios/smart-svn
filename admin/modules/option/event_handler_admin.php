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
 * Option module admin event handler
 *
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'SF_EVT_HANDLER_OPTION' , 'option');

// register this handler                       
if (FALSE == $base->event->register_handler( 
                           SF_EVT_HANDLER_OPTION,
                           array ( 'module'        => SF_EVT_HANDLER_OPTION,
                                   'event_handler' => 'option_event_handler') ))
{
    trigger_error( 'The handler '.SF_EVT_HANDLER_OPTION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function option_event_handler( $evt )
{
    global $base;

    switch( $evt['code'] )
    {
        case SF_EVT_LOAD_INIT_OPTION:
            // get all system options
            $base->sql = "SELECT * FROM options";
            if(!$base->result = sqlite_unbuffered_query($base->dbconn_system, $base->sql))
            {
                trigger_error("ERROR: " . @sqlite_error_string(@sqlite_last_error ( $base->dbconn_system )) . "\nFILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n\n");
            }
            
            $row = sqlite_fetch_array($base->result, SQLITE_ASSOC);
            foreach($row as $key => $value)
            {
                $base->tpl->assign($key, $value);
            }
            
            break;      
        case SF_EVT_LOAD_MODULE:
            include(SF_BASE_DIR.'/admin/modules/option/module_loader.php');           
            break;             
        case SF_EVT_INIT:    
            break; 
        case SF_EVT_LOGOUT:  
            break;             
    }
}

?>
