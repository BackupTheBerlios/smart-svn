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

// The base object
include_once( SF_BASE_DIR . '/admin/include/class.sfObject.php' );
$base = & new sfObject;
if ( SF_DEBUG == TRUE ) $base->register( 'base', __FILE__, __LINE__);//remove

// include pear db
include_once( 'DB.php' );

// include Log class
include_once( SF_BASE_DIR . '/admin/lib/PEAR/Log/Log.php' );

// include sfSecureGPC
include_once( SF_BASE_DIR . '/admin/include/class.sfSecureGPC.php' );

// include sfErrorHandler
include_once( SF_BASE_DIR . '/admin/include/class.sfErrorHandler.php' );

// include patConfiguration
include_once( SF_BASE_DIR . '/admin/lib/patConfiguration/include/patConfiguration.php' );

// include patErrorManager
include_once( SF_BASE_DIR . '/admin/lib/patError/patErrorManager.php' );

// include event class
include_once( SF_BASE_DIR . '/admin/include/class.sfEvent.php' );

// include patTemplate
include_once( SF_BASE_DIR . '/admin/lib/patTemplate/patTemplate.php' );

// Load the util class
include_once( SF_BASE_DIR . '/admin/include/class.sfUtil.php' );

// set error handler
$base->errorHandler   =  new sfErrorHandler();
if ( SF_DEBUG == TRUE ) $base->register( 'errorHandler', __FILE__, __LINE__);//remove    
patErrorManager::setErrorHandling( E_ALL , 'callback', array( $base->errorHandler, 'sfDebug' ) );

/*
 * The base location to Smart
 */    
define('SF_BASE_LOCATION', sfUtil::base_location());

// Check if setup was done
if (!@is_file(SF_BASE_DIR . '/admin/config/config_db_connect.xml.php'))
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

//  instance of patTemplate
$base->tpl = new patTemplate();
if ( SF_DEBUG == TRUE ) $base->register( 'tpl', __FILE__, __LINE__);//remove

// set templates root dir
$base->tpl->setRoot( SF_BASE_DIR  );

// set cache directory
$base->tpl->useTemplateCache( 'File', array('cacheFolder' => SF_BASE_DIR . '/admin/tmp/cache_' . SF_SECTION,
                                            'lifetime'    => 'auto' ));

// Event class instance
$base->event = & new sfEvent;
if ( SF_DEBUG == TRUE ) $base->register( 'event', __FILE__, __LINE__);//remove

// Register all handlers
//
// register system event handler
include_once (SF_BASE_DIR . '/admin/include/event_handler_' . SF_SECTION . '.php');

// Register module handlers
//
$base->tmp_directory =& dir( SF_BASE_DIR . '/admin/modules');
while (false != ($base->tmp_dirname = $base->tmp_directory->read()))
{
    if ( ( $base->tmp_dirname == '.' ) || ( $base->tmp_dirname == '..' ) )
    {
        continue;
    }            
    if ( @is_dir( SF_BASE_DIR . '/admin/modules/'.$base->tmp_dirname) )
    {
        $base->tmp_evt_handler = SF_BASE_DIR . '/admin/modules/' . $base->tmp_dirname . '/event_handler_' . SF_SECTION . '.php';
        if ( @is_file( $base->tmp_evt_handler ) )
        {
            include_once $base->tmp_evt_handler;
        }  
    }
}

$base->tmp_directory->close();
unset($base->tmp_evt_handler);
unset($base->tmp_directory);
unset($base->tmp_evt_handler);

// Check if option handler is installed (required)
if ( FALSE == $base->event->is_handler(SF_OPTION_MODULE) )
{
    patErrorManager::raiseError( "sf:handler", 'Missing handler', SF_OPTION_MODULE . "handler is missing on \nFile: ".__FILE__."\nLine: ".__LINE__ );
}

// Check if user authentication handler is installed (required)
if ( FALSE == $base->event->is_handler(SF_AUTH_MODULE) )
{
    patErrorManager::raiseError( "sf:handler", 'Missing handler', SF_AUTH_MODULE . "handler is missing on \nFile: ".__FILE__."\nLine: ".__LINE__ );
}

//
// Load base options
//
$base->event->directed_run( SF_OPTION_MODULE, SF_EVT_LOAD_INIT_OPTION );

// set db resource
$base->dsn = $base->db_data['db_type'].'://'.$base->db_data['db_user'].':'.$base->db_data['db_passwd'].'@'.$base->db_data['db_host'].'/'.$base->db_data['db_name'].'';

// db instance and connect
$base->db = & DB::connect($base->dsn);
if (DB::isError($base->db)) 
{
    patErrorManager::raiseError( "db:connect", $base->db->getMessage(), "File: ".__FILE__."\nLine: ".__LINE__ );
}
unset($base->dsn);

?>