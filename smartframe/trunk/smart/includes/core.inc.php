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

// Start output buffering
//
@ob_start(); 

// include default definitions
include_once( SF_BASE_DIR . 'smart/includes/defaults.php' );

// include sfErrorHandler
include_once( SF_BASE_DIR . 'smart/includes/class.errorHandler.php' );

// Load the util class
include_once( SF_BASE_DIR . 'smart/includes/class.util.php' );

// The base container object
include_once( SF_BASE_DIR . 'smart/includes/class.base.php' );

// The distributor functions
include_once( SF_BASE_DIR . 'smart/includes/event_distributors.php' );

// Default view class
include_once( SF_BASE_DIR . 'smart/includes/class.view.php' );

// Default action class
include_once( SF_BASE_DIR . 'smart/includes/class.action.php' );

// create base container instance
$B = & new Base;

// set error handler
$B->errorHandler   =  new ErrorHandler();

//  instance of the util class
$B->util = & new Util;         
     
// Define the base location
define('SF_BASE_LOCATION', $B->util->base_location());

// Register all common handlers and filter handlers
//

// check if the modules directory exists
if(!is_dir(SF_BASE_DIR . 'modules'))
{
    trigger_error("Missing ".SF_BASE_DIR . "modules directory: \nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    die("<b>You have to install at least one 'SF_COMMON_MODULE' module in the /modules directory!</b>");
}

// A "common" module must be loaded and registered first
//
$mod_common = SF_BASE_DIR . 'modules/' . SF_COMMON_MODULE . '/init.php';

if(file_exists( $mod_common ))
{
    // include event handler of a "common" module
    include_once ( $mod_common );
}
else
{
    die ("The module /modules/{$mod_common}/init.php  must be installed!");
}

// include system module
include_once (SF_BASE_DIR . 'smart/init.php');

// include other modules init file
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
        $B->tmp_mod_init = SF_BASE_DIR . 'modules/' . $B->tmp_dirname . '/init.php';

        if ( @is_file( $B->tmp_mod_init ) )
        {
            include_once $B->tmp_mod_init;
        }       
    }
}

$B->tmp_directory->close();
unset($B->tmp_evt_handler);
unset($B->tmp_directory);

?>