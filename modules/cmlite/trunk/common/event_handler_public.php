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
 * Public common module event handler
 * This module does some init proccess and include 
 * external libraries needed by other modules
 * 
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_COMMON' , 'COMMON');


// register this handler                       
if (FALSE == $B->register_handler(MOD_COMMON,
                           array ( 'module'        => MOD_COMMON,
                                   'event_handler' => 'common_event_handler') ))
{
    trigger_error( 'The handler '.MOD_COMMON.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function common_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {           
    }
}

/***************************
**** Module SET  CONFIG ****
****************************/

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

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages which is included in smartframe
ini_set( 'include_path', SF_BASE_DIR . '/admin/modules/common/PEAR' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator);

// class instance of DB if setup is done
if($B->sys['info']['status'] == TRUE)
{
    // include PEAR DB class
    include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/DB.php');
    
    $B->dsn = array('phptype'  => $B->sys['db']['dbtype'],
                    'username' => $B->sys['db']['user'],
                    'password' => $B->sys['db']['passwd'],
                    'hostspec' => $B->sys['db']['host'],
                    'database' => $B->sys['db']['name']);

    $B->dboptions = array('debug'       => 0,
                          'portability' => DB_PORTABILITY_NONE);
    
    $B->db =& DB::connect($B->dsn, $B->dboptions, TRUE);
    if (DB::isError($B->db)) 
    {
        trigger_error( 'Cannot connect to the database: '.__FILE__.' '.__LINE__, E_USER_ERROR  );
    }
}

?>
