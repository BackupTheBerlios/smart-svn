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
 * Public user module event handler
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_USER' , 'USER');

// register this handler                       
if (FALSE == $B->register_handler(MOD_USER,
                           array ( 'module'        => MOD_USER,
                                   'event_handler' => 'user_event_handler') ))
{
    trigger_error( 'The handler '.MOD_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function user_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_AUTHENTICATE:
            break;
        case EVT_INIT:        
            break;           
    }
}

// include sqlite class
include_once( SF_BASE_DIR . '/admin/include/class.sfSqLite.php' );

// Connect to the database
$B->dbdata = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/' . $B->db_prefix . '_data.db.php');
$B->dbdata->turboMode(); 

?>
