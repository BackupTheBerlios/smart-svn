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
 * The core include file
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
include_once( SF_BASE_DIR . 'smart/includes/defaults.php' );

// Start output buffering
//
ob_start( SF_OB_GZHANDLER ); 

// include sfErrorHandler
include_once( SF_BASE_DIR . 'smart/includes/class.errorHandler.php' );

// Load the util class
include_once( SF_BASE_DIR . 'smart/includes/class.util.php' );

// The base object
include_once( SF_BASE_DIR . 'smart/includes/class.base.php' );
$B = & new Base;

// set error handler
$B->errorHandler   =  new ErrorHandler();

//  instance of the util class
$B->util = & new Util;

// Define the base location
define('SF_BASE_LOCATION', $B->util->base_location());

// Register all common handlers and filter handlers
//
// include system event handler
include_once (SF_BASE_DIR . 'smart/event_handler.php');

// include system filter handler
include_once (SF_BASE_DIR . 'smart/filter_handler.php');

// check if the modules directory exists
if(!is_dir(SF_BASE_DIR . 'modules'))
{
    trigger_error("Missing ".SF_BASE_DIR . "modules directory: \nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    die("<b>You have to install at least one module in the /smart/modules directory!</b>");
}

// A "common" must be loaded first after the system handlers
//
// include event handler of a "common" module
include_once (SF_BASE_DIR . 'modules/' . SF_COMMON_MODULE . '/event_handler.php');

// include filter handler of a "common" module
include_once (SF_BASE_DIR . 'modules/' . SF_COMMON_MODULE . '/filter_handler.php');

// include other module handlers and filters
//
$B->tmp_directory =& dir( SF_BASE_DIR . 'modules');
while (false != ($B->tmp_dirname = $B->tmp_directory->read()))
{
    if ( ( $B->tmp_dirname == '.' ) || ( $B->tmp_dirname == '..' ) )
    {
        continue;
    }            
    if ( ($B->tmp_dirname != SF_COMMON_MODULE) && @is_dir( SF_BASE_DIR . 'modules/'.$B->tmp_dirname) )
    {
        // include common handler
        $B->tmp_evt_handler = SF_BASE_DIR . 'modules/' . $B->tmp_dirname . '/event_handler.php';

        if ( @is_file( $B->tmp_evt_handler ) )
        {
            include_once $B->tmp_evt_handler;
        }  
        
        // include filter handler
        $B->tmp_filter_handler = SF_BASE_DIR . 'modules/' . $B->tmp_dirname . '/filter_handler.php';
        
        if ( @is_file( $B->tmp_filter_handler ) )
        {
            include_once $B->tmp_filter_handler;
        }        
    }
}

$B->tmp_directory->close();
unset($B->tmp_evt_handler);
unset($B->tmp_directory);
unset($B->tmp_filter_handler);

?>