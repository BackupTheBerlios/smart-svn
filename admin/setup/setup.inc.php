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
    die('no permission on ' . __FILE__);
}

// include default definitions
include_once( SF_BASE_DIR . '/admin/include/defaults.php' );

// Start output buffering
//
if( SF_OB == TRUE )
{
    ob_start( SF_OB_GZHANDLER ); 
}

// The base object
include_once( SF_BASE_DIR . '/admin/include/class.sfObject.php' );
$base = & new sfObject;
if( SF_DEBUG == TRUE ) $base->register( 'base', __FILE__, __LINE__);

// include pear db
include_once( 'DB.php' );
// Load the util class
include_once( SF_BASE_DIR . '/admin/include/class.sfUtil.php' );
// include event class
include_once( SF_BASE_DIR . '/admin/include/class.sfEvent.php' );
// include sfErrorHandler
include_once( SF_BASE_DIR . '/admin/include/class.sfErrorHandler.php' );
// include patErrorManager
include_once( SF_BASE_DIR . '/admin/lib/patError/patErrorManager.php' );
// include patTemplate
include_once( SF_BASE_DIR . '/admin/lib/patTemplate/patTemplate.php' );
// include patConfiguration
include_once( SF_BASE_DIR . '/admin/lib/patConfiguration/include/patConfiguration.php' );

// set error handler
$base->errorHandler   =  new sfErrorHandler();
if ( SF_DEBUG == TRUE ) $base->register( 'errorHandler', __FILE__, __LINE__);//remove    
patErrorManager::setErrorHandling( E_ALL , 'callback', array( $base->errorHandler, 'sfDebug' ) );

//  instance of the util class
$base->util = new sfUtil;
if( SF_DEBUG == TRUE ) $base->register( 'util', __FILE__, __LINE__);

// pat configurator instance    
$base->conf = new patConfiguration (array(
                                    'configDir'     => SF_BASE_DIR . '/admin/config',
                                    'cacheDir'      => SF_BASE_DIR . '/admin/config/cache',
                                    'errorHandling' => 'trigger_error',
                                    'includeDir'    => SF_BASE_DIR . '/admin/include',
                                    'encoding'      => 'iso-8859-1'));  
if( SF_DEBUG == TRUE ) $base->register( 'conf', __FILE__, __LINE__);

// pat configurator instance for config_release_info.xml  
$base->conf_rel_info = new patConfiguration (array(
                                    'configDir'     => SF_BASE_DIR . '/admin/config',
                                    'cacheDir'      => SF_BASE_DIR . '/admin/config/cache',
                                    'errorHandling' => 'trigger_error',
                                    'includeDir'    => SF_BASE_DIR . '/admin/include',
                                    'encoding'      => 'iso-8859-1'));  
if( SF_DEBUG == TRUE ) $base->register( 'conf_rel_info', __FILE__, __LINE__);        


//  parse global config options file
$base->conf_rel_info->parseConfigFile( 'config_release_info.xml' );
// get system status
$base->system_status = $base->conf_rel_info->getConfigValue('info.status');

// Check if setup was done
if ( @is_file(SF_BASE_DIR . '/admin/config/config_db_connect.xml.php') )
{
    if ( $base->system_status == 'ready' )
    {
        @header('Location: ../index.php');         
    }
}
    
//  instance of patTemplate
$base->tpl = new patTemplate();
if( SF_DEBUG == TRUE ) $base->register( 'tpl', __FILE__, __LINE__);

// set templates root dir
$base->tpl->setRoot( SF_BASE_DIR  );

// Event class instance
$base->event = & new sfEvent;
if( SF_DEBUG == TRUE ) $base->register( 'event', __FILE__, __LINE__);

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