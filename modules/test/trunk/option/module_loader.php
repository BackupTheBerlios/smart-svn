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

// Show main options if no module feature is requested.
if(!isset($_GET['mf']))
{
    if (isset($_POST['update_main_options_email']))
    {
        $B->sys['option']['email']        = $B->util->stripSlashes($_POST['site_email']);
    } 
    elseif (isset($_POST['update_main_options_title']))
    {
        $B->sys['option']['site_title'] = $B->util->stripSlashes($_POST['site_title']);
        $B->sys['option']['site_desc']  = $B->util->stripSlashes($_POST['site_desc']);
    } 
    elseif (isset($_POST['update_main_options_charset']))
    {
        $B->sys['option']['charset']    = $B->util->stripSlashes($_POST['charset']);
    }  
    elseif (isset($_POST['update_main_options_tpl']))
    {
        $B->sys['option']['tpl']        = $B->util->stripSlashes($_POST['tpl']);
    }     
 
    // set the config array 
    $B->conf->setConfigValues( $B->sys );
    //  write the config array
    $B->conf->writeConfigFile( 'config_system.xml.php', array('comment' => 'Main config file', 'filetype' => 'xml', 'mode' => 'pretty') );        
    
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
}
else
{
    //Load options of the requested modul
    if(FALSE === ($B->modul_options = $B->M( $_GET['mf'], EVT_LOAD_OPTIONS)))
    {
        trigger_error( "This modul handler dosent exist: " . $_GET['mf'] . "\n" . __FILE__ . "\n" . __LINE__, E_USER_ERROR  );            
    }
}

// set the base template for this module
$B->module = SF_BASE_DIR . '/admin/modules/option/templates/index.tpl.php';    

?>