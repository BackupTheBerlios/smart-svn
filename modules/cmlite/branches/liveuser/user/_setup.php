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
 * Setup of the user module
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// Do setup 
if( empty($_POST['sysname']) )
{
    $B->setup_error[] = 'Sysadmin name field is empty!';
    $success = FALSE;
}
if( empty($_POST['syslastname']) )
{
    $B->setup_error[] = 'Sysadmin lastname field is empty!';
    $success = FALSE;
}
if( empty($_POST['syslogin']) )
{
    $B->setup_error[] = 'Sysadmin login field is empty!';
    $success = FALSE;
}
if( empty($_POST['syspassword1']) || ($_POST['syspassword1'] != $_POST['syspassword2']) )
{
    $B->setup_error[] = 'Sysadmin password fields are empty or not equal!';
    $success = FALSE;
} 

if( $success == TRUE )
{
    //create captcha_pics dir if it dosent exist
    if(!is_dir(SF_BASE_DIR . '/admin/tmp/captcha_pics'))
    {
        if(!mkdir(SF_BASE_DIR . '/admin/tmp/captcha_pics', SF_DIR_MODE))
        {
            $B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . '/admin/tmp/captcha_pics';
            $success = FALSE;
        }
        elseif(!is_writeable( SF_BASE_DIR . '/admin/tmp/captcha_pics' ))
        {
            $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/tmp/captcha_pics';
            $success = FALSE;
        }  
    }
    
    if($success == TRUE)
    {
        // create db tables
        if(file_exists(SF_BASE_DIR . '/admin/modules/user/_setup_'.$_POST['dbtype'].'.php'))
        {
            // include mysql setup
            include_once( SF_BASE_DIR . '/admin/modules/user/_setup_'.$_POST['dbtype'].'.php' );    
        }
        else
        {
            $B->setup_error[] = 'USER module: This db type isnt supported: ' . $_POST['dbtype'];
            $success = FALSE;
        }
    }
    
    $B->conf_val['module']['user']['name']     = 'user';
    $B->conf_val['module']['user']['version']  = MOD_USER_VERSION;
    $B->conf_val['module']['user']['mod_type'] = 'lite';
    $B->conf_val['module']['user']['info']     = 'This is leader module of this module group. Author: Armand Turpel <smart AT open-publisher.net>';  
    
    $B->conf_val['db']['dbtype'] = $_POST['dbtype'];
    
    $B->conf_val['option']['user']['allow_register']  = TRUE;
    $B->conf_val['option']['user']['register_type']   = 'auto';
}

?>