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
 * Module loader of the option module
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Init this variable
$B->modul_options = FALSE;

// init var - used if a config value has been modified
$B->_modified = FALSE;
    
if (isset($_POST['update_main_options_url']))
{
    $B->sys['option']['url'] = stripslashes($_POST['site_url']);
    $B->_modified = TRUE;
}
elseif (isset($_POST['update_main_options_email']))
{
    $B->sys['option']['email']        = stripslashes($_POST['site_email']);
    $B->_modified = TRUE;
} 
elseif (isset($_POST['update_main_options_title']))
{
    $B->sys['option']['site_title'] = stripslashes($_POST['site_title']);
    $B->sys['option']['site_desc']  = stripslashes($_POST['site_desc']);
    $B->_modified = TRUE;
} 
elseif (isset($_POST['update_main_options_charset']))
{
    $B->sys['option']['charset']    = stripslashes($_POST['charset']);
    $B->_modified = TRUE;
}  
elseif (isset($_POST['update_main_options_tpl']))
{
    $B->sys['option']['tpl']        = stripslashes($_POST['tpl']);
    $B->_modified = TRUE;
}     
    
// set options of other modules
$B->B( EVT_SET_OPTIONS );
 
// if some config are modified, write the config file and reload the page
if($B->_modified == TRUE)
{
    // include PEAR Config class
    include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/Config.php');

    $c = new Config();
    $root =& $c->parseConfig($B->sys, 'PHPArray');
                    
    // write config array
    $c->writeConfig(SF_BASE_DIR . '/admin/modules/common/config/config.php', 'PHPArray', array('name' => 'B->sys'));
    
    unset($c);
    unset($root);
    
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=OPTION');
    exit;
}      
    
// Load the available public templates sets from the main folder 
$B->templ = array();
$directory =& dir(SF_BASE_DIR);

while (false != ($dirname = $directory->read()))
{
    if (FALSE == is_dir(SF_BASE_DIR . '/' . $dirname))
    {
        if(preg_match("/(^[^_]+).*\.tpl\.php$/", $dirname, $tmp))
        {
            if(!in_array($tmp[1], $B->templ))
                $B->templ[] = $tmp[1];
        }
    }
}

$directory->close();

// Load options templates from other modules    
$B->mod_option = array();
$B->B( EVT_GET_OPTIONS );

// set the base template for this module
$B->module = SF_BASE_DIR . '/admin/modules/option/templates/index.tpl.php';    


?>
