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

/*
 * The base include file
 *
 */

/*
 * Check if this script is included in the  environment
 */
if (!defined( 'SF_SECURE_INCLUDE' ))
{
    die('no permission on ' . __FILE__);
}

// include default definitions
include_once( SF_BASE_DIR . '/admin/include/defaults.php' );

// Start output buffering
//
if ( SF_OB == TRUE )
{
    ob_start( SF_OB_GZHANDLER ); 
}


// include sfSecureGPC
include_once( SF_BASE_DIR . '/admin/include/class.sfSecureGPC.php' );

// include sfErrorHandler
include_once( SF_BASE_DIR . '/admin/include/class.sfErrorHandler.php' );

// include sqlite class
include_once( SF_BASE_DIR . '/admin/include/class.sfSqLite.php' );

// include session class
include_once( SF_BASE_DIR . '/admin/include/class.sfSession.php' );

// Load the util class
include_once( SF_BASE_DIR . '/admin/include/class.sfUtil.php' );

// The base object
include_once( SF_BASE_DIR . '/admin/include/class.sfBase.php' );
$B = & new sfBase;

// set error handler
$B->errorHandler   =  new sfErrorHandler();

//  instance of the util class
$B->util = new sfUtil;

/*
 * The base location to Smart
 */    
define('SF_BASE_LOCATION', $B->util->base_location());


// Check if setup was done
if ( !@file_exists(SF_BASE_DIR . '/data/db_sqlite/system.db.php' ) )
{
    // redirect to setup
    if (SF_SECTION != 'admin')
    {
        @header('Location: ' . SF_BASE_LOCATION . '/admin/setup/');
    }
    else
    {
        @header('Location: ' . SF_BASE_LOCATION . '/setup/');    
    }
    exit;          
}

// Connect to the session database
$B->dbsession = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/session.db.php');
$B->dbsession->turboMode();

/* Create new object of session class */
$B->session = & new session();

// Connect to the system database
$B->dbsys = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/system.db.php');
$B->dbsys->turboMode();

// Load system info
$result = $B->dbsys->query('SELECT * FROM info'); 
$B->sys_info = $B->dbsys->getRow($result);
unset($result);

// Load system options
$result = $B->dbsys->query('SELECT * FROM options'); 
$B->sys_option = $B->dbsys->getRow($result);
unset($result);

// Connect to the database
$B->dbdata = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/' . $B->sys_option['db_prefix'] . '_data.db.php');
$B->dbdata->turboMode();

// Register all handlers
//
// register system event handler
include_once (SF_BASE_DIR . '/admin/include/event_handler_' . SF_SECTION . '.php');

// Register module handlers
//
$B->tmp_directory =& dir( SF_BASE_DIR . '/admin/modules');
while (false != ($B->tmp_dirname = $B->tmp_directory->read()))
{
    if ( ( $B->tmp_dirname == '.' ) || ( $B->tmp_dirname == '..' ) )
    {
        continue;
    }            
    if ( @is_dir( SF_BASE_DIR . '/admin/modules/'.$B->tmp_dirname) )
    {
        $B->tmp_evt_handler = SF_BASE_DIR . '/admin/modules/' . $B->tmp_dirname . '/event_handler_' . SF_SECTION . '.php';
        
        if ( @is_file( $B->tmp_evt_handler ) )
        {
            include_once $B->tmp_evt_handler;
        }  
    }
}

$B->tmp_directory->close();
unset($B->tmp_evt_handler);
unset($B->tmp_directory);
unset($B->tmp_evt_handler);

// Check if option handler is installed (required)
if ( FALSE == $B->is_handler(SF_OPTION_MODULE) )
{
    trigger_error("Missing handler ".SF_OPTION_MODULE . "handler is missing on \nFile: ".__FILE__."\nLine: ".__LINE__,  E_USER_ERROR);
}

// Check if user authentication handler is installed (required)
if ( FALSE == $B->is_handler(SF_AUTH_MODULE) )
{
    trigger_error("Missing handler" . SF_AUTH_MODULE . "handler is missing on \nFile: ".__FILE__."\nLine: ".__LINE__,  E_USER_ERROR);
}

//
// Load base options -> option module
//
$B->M( MOD_OPTION, EVT_LOAD_INIT_OPTION );

?>