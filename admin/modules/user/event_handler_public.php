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

// Include all necessairy stuff to get work the module set

// ### These 3 defines MUST be declared ###
/**
 * The module (name) which takes the authentication part.
 */
define('SF_AUTH_MODULE',                 'USER');

/**
 * The module (name) which takes the global options part.
 */
define('SF_OPTION_MODULE',               'OPTION');

/**
 * The module (name) which should be loaded by default.
 */
define('SF_DEFAULT_MODULE',              'ENTRY');

// class instance of DB if setup is done
if($B->sys['info']['status'] == TRUE)
{
    // include PEAR DB class
    include_once( 'DB.php');
    
    if($B->sys['db']['dbtype'] == 'sqlite')
    {
        $B->sys['db']['name'] = SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php';
    }
    
    $B->dsn = array('phptype'  => $B->sys['db']['dbtype'],
                    'username' => $B->sys['db']['user'],
                    'password' => $B->sys['db']['passwd'],
                    'hostspec' => $B->sys['db']['host'],
                    'database' => $B->sys['db']['name']);

    $B->dboptions = array('debug'       => 2,
                          'portability' => DB_PORTABILITY_ALL);
    
    $B->db =& DB::connect($B->dsn, $B->dboptions);
    if (DB::isError($B->db)) 
        trigger_error( $B->db->getMessage(),E_USER_ERROR );     
}

?>
