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

// include session class
include_once( SF_BASE_DIR . '/admin/include/class.sfSession.php' );

// Load the util class
include_once( SF_BASE_DIR . '/admin/include/class.sfUtil.php' );

// The patErrorManager class 
include_once( SF_BASE_DIR . '/admin/lib/patTools/patErrorManager.php' );

// The patConfiguration class (read/write xml config files)
include_once( SF_BASE_DIR . '/admin/lib/patTools/patConfiguration.php' );

// The base object
include_once( SF_BASE_DIR . '/admin/include/class.sfBase.php' );
$B = & new sfBase;

// set error handler
$B->errorHandler   =  new sfErrorHandler();

//  create config
$B->conf = & new patConfiguration(array(
                                         'configDir'     => SF_BASE_DIR . '/admin/config',
                                         'cacheDir'      => SF_BASE_DIR . '/admin/config/cache',
                                         'errorHandling' => 'trigger_error',
                                         'encoding'      => 'ISO-8859-1'
                                        ));

if(@file_exists(SF_BASE_DIR . '/admin/config/config_system.xml.php'))
{
    //  read config file from cache
    //  if cache is not valid, original file will be read and cache created
    $B->conf->loadCachedConfig( 'config_system.xml.php', array('filetype'=>'xml') );
    $B->sys = $B->conf->getConfigValue();
}

//  instance of the util class
$B->util = new sfUtil;

/* Create new object of session class */
$B->session = & new session();

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

// Check if setup was done
if ( $B->sys['info']['status'] !== TRUE )
{
    if( SF_SECTION != 'admin' )
    {
        @header('Location: admin/index.php');
        exit;
    }
    // send a setup message to the handler which takes
    // the setup part
    $B->M( MOD_SETUP, EVT_SETUP );
}

?>