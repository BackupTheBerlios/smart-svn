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

/**
 * Update script to version 0.1.5a
 *
 */

/*
 * Secure include of files from this script
 */
define ('SF_SECURE_INCLUDE', 1);

// Define the absolute path to the Framework base
//
define ('SF_BASE_DIR', dirname(dirname(dirname(dirname(__FILE__)))));

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages which is included in smartframe
ini_set( 'include_path', SF_BASE_DIR . '/admin/modules/common/PEAR' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator);

// upgrade only to earchive 0.1.5a
include_once(SF_BASE_DIR . '/admin/include/system_version.php');
if($B->system_version == '0.1.5a' && @file_exists(SF_BASE_DIR . '/admin/config/config_system.xml.php'))
{
    // The patErrorManager class
    include_once( SF_BASE_DIR . '/admin/lib/patTools/patErrorManager.php' );

    // The patConfiguration class (read/write xml config files)
    include_once( SF_BASE_DIR . '/admin/lib/patTools/patConfiguration.php' );
    //  create config
    $conf = & new patConfiguration(array(
                                             'configDir'     => SF_BASE_DIR . '/admin/config',
                                             'cacheDir'      => SF_BASE_DIR . '/admin/config/cache',
                                             'errorHandling' => 'trigger_error',
                                             'encoding'      => 'ISO-8859-1'
                                            ));

    //  read config file from cache
    //  if cache is not valid, original file will be read and cache created
    $conf->loadCachedConfig( 'config_system.xml.php', array('filetype'=>'xml') );
    $sys = $conf->getConfigValue();

    // include PEAR Config class
    include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/Config.php');

    $c = new Config();
    $root =& $c->parseConfig($sys, 'PHPArray');

    if( !is_writeable(SF_BASE_DIR . '/admin/modules/common/config') )
    {
        die('This directory must be writeable: '.SF_BASE_DIR . '/admin/modules/common/config');
    }

    $c->writeConfig(SF_BASE_DIR . '/admin/modules/common/config/config.php', 'PHPArray', array('name' => 'B->sys'));

    // The patErrorManager class
    include_once( SF_BASE_DIR . '/admin/modules/common/class.commonUtil.php' );

    $util = & new commonUtil;
    $util->delete_dir_tree( SF_BASE_DIR . '/admin/config' );
    $util->delete_dir_tree( SF_BASE_DIR . '/admin/lib' );
    $util->delete_dir_tree( SF_BASE_DIR . '/admin/media' );
    $util->delete_dir_tree( SF_BASE_DIR . '/admin/tmp' );
    @unlink(SF_BASE_DIR . '/admin/index.tpl.php');
    
    @header('Location: ../../index.php');
    exit; 
}


?>
