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
    die('No Permission on'. __FILE__);
}

// Do setup 
if( empty($_POST['sysname']) )
{
    $B->setup_error[] = 'Sysadmin name field is empty!';
}
if( empty($_POST['syslastname']) )
{
    $B->setup_error[] = 'Sysadmin lastname field is empty!';
}
if( empty($_POST['syslogin']) )
{
    $B->setup_error[] = 'Sysadmin login field is empty!';
}
if( empty($_POST['syspassword1']) || ($_POST['syspassword1'] != $_POST['syspassword2']) )
{
    $B->setup_error[] = 'Sysadmin password fields are empty or not equal!';
} 

if( count($B->setup_error) == 0 )
{
    //create sqlite dir if it dosent exist
    if(!is_dir(SF_BASE_DIR . '/admin/tmp/captcha_pics'))
    {
        if(!@mkdir(SF_BASE_DIR . '/admin/tmp/captcha_pics', SF_DIR_MODE))
        {
            $B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . '/admin/tmp/captcha_pics';
        }
        elseif(!@is_writeable( SF_BASE_DIR . '/admin/tmp/captcha_pics' ))
        {
            $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/tmp/captcha_pics';
        }  
    }
    
    // include PEAR DB
    include_once( 'DB.php' ); 
    
    // include sqlite setup
    include_once( SF_BASE_DIR . '/admin/modules/user/_setup_'.$_POST['dbtype'].'.php' );    
    
    $B->conf_val['module']['user']['name']    = 'user';
    $B->conf_val['module']['user']['version'] = '0.1';
    $B->conf_val['module']['user']['info'] = '';  
    
    $B->conf_val['db']['dbtype'] = $_POST['dbtype'];
    
    $B->conf_val['option']['user']['allow_register']  = TRUE;
    $B->conf_val['option']['user']['register_type']   = 'auto';
}

?>