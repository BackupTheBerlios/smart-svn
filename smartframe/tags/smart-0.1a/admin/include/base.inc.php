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

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages which is included in smartframe
ini_set( 'include_path', SF_BASE_DIR . '/admin/lib/PEAR' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator);

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

// Define the base location
define('SF_BASE_LOCATION', $B->util->base_location());

/* Create new object of session class */
$B->session = & new session();

// Register all handlers
//
// register system event handler
include_once (SF_BASE_DIR . '/admin/include/event_handler_' . SF_SECTION . '.php');

// check the modules directory exist
if(!is_dir(SF_BASE_DIR . '/admin/modules'))
{
    trigger_error("Missing ".SF_BASE_DIR . "/admin/modules directory: \nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    die("<b>You have to install at least one module in the /admin/modules directory!</b>");
}

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
    // If calling from the pubilc page, switch to the admin page
    if( SF_SECTION != 'admin' )
    {
        if(isset($_GET['dbtype']))
            $_dbtype = '?dbtype='.$_GET['dbtype'];
        else
            $_dbtype = '?dbtype=mysql';
        @header('Location: '.SF_BASE_LOCATION.'/admin/index.php'.$_dbtype);
        exit;
    }
    // send a setup message to the handler which takes
    // the setup part
    if(FALSE === $B->M( MOD_SETUP, EVT_SETUP ))
    {
        die("<b>It seems that there are no modules available.<br />
             Min. required modules:<br />
             - admin/modules/setup<br />
             - admin/modules/user</b>");
    }
}

?>