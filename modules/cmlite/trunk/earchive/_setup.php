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

if( count($B->setup_error) == 0)
{  
    //create sqlite dir if it dosent exist
    if(!is_dir(SF_BASE_DIR . '/data/earchive'))
    {
        if(!@mkdir(SF_BASE_DIR . '/data/earchive', SF_DIR_MODE))
        {
            $B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . '/data/earchive';
        }
        elseif(!@is_writeable( SF_BASE_DIR . '/data/earchive' ))
        {
            $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/data/earchive';
        }  
    }

    if(!isset($_POST['dbtype']))
        $db_type = $B->sys['db']['dbtype'];
    else
        $db_type = $_POST['dbtype'];
    

    // include db setup
    include_once( SF_BASE_DIR . '/admin/modules/earchive/_setup_'.$db_type.'.php' );    
    
    $B->conf_val['module']['earchive']['name']    = 'Earchive';
    $B->conf_val['module']['earchive']['version'] = '0.1';
    $B->conf_val['module']['earchive']['info'] = 'Email messages archive';     
}

?>