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
 * The base setup include file
 *
 */

/*
 * Check if this script is included in the smart environment
 */
if (!defined( 'SF_SECURE_INCLUDE' ))
{
    die("no permission on " . __FILE__);
}

// include default definitions
include_once( SF_BASE_DIR . "/admin/include/defaults.php" );

// Start output buffering
//
if( SF_OB == TRUE )
{
    ob_start( SF_OB_GZHANDLER ); 
}

// The base object
include_once( SF_BASE_DIR . "/admin/include/class.sfObject.php" );
$base = & new sfObject;
if( SF_DEBUG == TRUE )
    $base->register( 'base', __FILE__, __LINE__);


// include pear db
include_once( "DB.php" );
// include event class
include_once( SF_BASE_DIR . "/admin/include/class.sfEvent.php" );
// include patErrorManager
include_once( SF_BASE_DIR . "/admin/lib/patError/patErrorManager.php" );
// include patTemplate
include_once( SF_BASE_DIR . "/admin/lib/patTemplate/patTemplate.php" );
// include patConfiguration
include_once( SF_BASE_DIR . "/admin/lib/patConfiguration/include/patConfiguration.php" );

// set error handling
patErrorManager::setErrorHandling( SF_ERROR_REPORTING, "echo" );

// pat configurator instance    
$base->conf = new patConfiguration;
if( SF_DEBUG == TRUE )
    $base->register( 'conf', __FILE__, __LINE__);

//  set global config dir
$base->conf->setConfigDir( SF_BASE_DIR . "/admin/setup" );
    
//  instance of patTemplate
$base->tpl = new patTemplate();
if( SF_DEBUG == TRUE )
    $base->register( 'tpl', __FILE__, __LINE__);

// set templates root dir
$base->tpl->setRoot( SF_BASE_DIR  );

// Event class instance
$base->event = & new sfEvent;
if( SF_DEBUG == TRUE )
    $base->register( 'event', __FILE__, __LINE__);
    
// register system event handler
include_once (SF_BASE_DIR . '/admin/include/event_handler_' . SF_SECTION . '.php');

// Register module handlers
//
$base->tmp_directory =& dir( SF_BASE_DIR . '/admin/modules');
while (false != ($base->tmp_dirname = $base->tmp_directory->read()))
{
    if ( ( $base->tmp_dirname == "." ) || ( $base->tmp_dirname == ".." ) )
    {
        continue;
    }            
    if ( @is_dir( SF_BASE_DIR . '/admin/modules/'.$base->tmp_dirname) )
    {
        $base->tmp_evt_handler = SF_BASE_DIR . '/admin/modules/' . $base->tmp_dirname . '/event_handler_' . SF_SECTION . '.php';
        if( @is_file( $base->tmp_evt_handler ) )
        {
            include_once $base->tmp_evt_handler;
        }  
    }
}
$base->tmp_directory->close();
unset($base->tmp_evt_handler);
unset($base->tmp_directory);
unset($base->tmp_evt_handler);

?>