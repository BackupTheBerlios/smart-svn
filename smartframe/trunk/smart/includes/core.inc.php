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
@ob_start(); 

// include sfErrorHandler
include_once( SF_BASE_DIR . 'smart/includes/class.errorHandler.php' );

// Load the util class
include_once( SF_BASE_DIR . 'smart/includes/class.util.php' );

// include session class
include_once( SF_BASE_DIR . 'smart/includes/class.session.php' );  

// The base container object
include_once( SF_BASE_DIR . 'smart/includes/class.base.php' );

// create base container instance
$B = & new Base;

// set error handler
$B->errorHandler   =  new ErrorHandler();

//  instance of the util class
$B->util = & new Util;         
     
/* Create new object of session class */
$B->session = & new session(); 

// Define the base location
define('SF_BASE_LOCATION', $B->util->base_location());

// Register all common handlers and filter handlers
//

// check if the modules directory exists
if(!is_dir(SF_BASE_DIR . 'modules'))
{
    trigger_error("Missing ".SF_BASE_DIR . "modules directory: \nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    die("<b>You have to install at least one module in the /modules directory!</b>");
}

// A "common" event handler and filter must be loaded and registered first
//
$evt_common = SF_BASE_DIR . 'modules/' . SF_COMMON_MODULE . '/event_handler.php';
$filter_common = SF_BASE_DIR . 'modules/' . SF_COMMON_MODULE . '/filter_handler.php';

if(file_exists( $evt_common ))
{
    // include event handler of a "common" module
    include_once ( $evt_common );
}
else
{
    die ("The module event handler {$evt_common} must be installed!");
}

if(file_exists( $filter_common ))
{
    // include filter handler of a "common" module
    include_once ( $filter_common );
}

// include system event handler
include_once (SF_BASE_DIR . 'smart/event_handler.php');

// include system filter handler
include_once (SF_BASE_DIR . 'smart/filter_handler.php');

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