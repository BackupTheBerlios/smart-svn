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

// Load the util class
include_once( SF_BASE_DIR . '/admin/include/class.sfUtil.php' );

// The base object
include_once( SF_BASE_DIR . '/admin/include/class.sfBase.php' );
$B = & new sfBase;

// The system name and version variables
include_once( SF_BASE_DIR . '/admin/include/system_version.php' );

// set error handler
$B->errorHandler   =  new sfErrorHandler();

// Init error array
$B->setup_error = array();

//  instance of the util class
$B->util = new sfUtil;

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


?>