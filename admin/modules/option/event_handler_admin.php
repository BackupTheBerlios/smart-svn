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
define ( 'MOD_OPTION' , 'OPTION');

// register this handler                       
if (FALSE == $B->register_handler( 
                           MOD_OPTION,
                           array ( 'module'        => MOD_OPTION,
                                   'event_handler' => 'option_event_handler') ))
{
    trigger_error( 'The handler '.MOD_OPTION.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
}

// The handler function
function option_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_LOAD_INIT_OPTION:
            // get all system options
            $sql = "SELECT * FROM options";
            if(!$result = sqlite_unbuffered_query($B->dbconn_system, $sql))
            {
                trigger_error("ERROR: " . @sqlite_error_string(@sqlite_last_error ( $B->dbconn_system )) . "\nFILE: " . __FILE__ . "\nLINE: " . __LINE__ . "\n\n");
            }
            
            $row = sqlite_fetch_array($result, SQLITE_ASSOC);
            foreach($row as $key => $value)
            {
                $B->$key = $value;
            }
            unset($row);
            unset($result);
            unset($sql);
            break;      
        case EVT_LOAD_MODULE:
            include(SF_BASE_DIR.'/admin/modules/option/module_loader.php');           
            break;             
        case EVT_INIT:    
            break; 
        case EVT_LOGOUT:  
            break;             
    }
}

?>
