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

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// Init error array
$base->tmp_error = array();

// Check directories accesses
if(!is_writeable( SF_BASE_DIR . '/admin/config/' ))
{
    $base->tmp_error[]['error'] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/config/';
}
if(!is_writeable( SF_BASE_DIR . '/admin/tmp/cache_admin/' ))
{
    $base->tmp_error[]['error'] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/tmp/cache_admin/';
}
if(!is_writeable( SF_BASE_DIR . '/admin/tmp/cache_public/' ))
{
    $base->tmp_error[]['error'] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/tmp/cache_public/';
}

// Do setup 
if( $_POST['do_setup'] && (count($base->tmp_error, COUNT_RECURSIVE) == 0) )
{

    if( empty($_POST['host']) )
    {
        $base->tmp_error[]['error'] = 'Host field is empty!';
    }
    if( empty($_POST['login']) )
    {
        $base->tmp_error[]['error'] = 'Login field is empty!';
    }
    if( ($_POST['password1'] != $_POST['password2']) )
    {
        $base->tmp_error[]['error'] = 'Password fields are empty or not equal!';
    } 
    if( empty($_POST['db_name']) )
    {
        $base->tmp_error[]['error'] = 'DB name field is empty!';
    }
  
    if( count($base->tmp_error, COUNT_RECURSIVE) == 0 )
    {
        // set db resource
        $base->dsn = $_POST['db_type'].'://'.$_POST['login'].':'.$_POST['password'].'@'.$_POST['host'].'/'.$_POST['db_name'];

        // db connect
        $base->db = & DB::connect($base->dsn);
        if (DB::isError($base->db)) 
        {
            $base->tmp_error[]['error'] = $base->db->getMessage();
        }        

        if( count($base->tmp_error, COUNT_RECURSIVE) == 0 )
        {
            $base->tmp_config['db.smart'] = array(
                                       'db_host'         => $_POST['host'],
                                       'db_user'         => $_POST['login'],
                                       'db_passwd'       => $_POST['password'],
                                       'db_type'         => $_POST['db_type'],
                                       'db_name'         => $_POST['db_name'],
                                       'db_table_prefix' => $_POST['table_prefix'] );
     
            $base->conf->setConfigValues( $base->tmp_config );
            $base->conf->writeConfigFile('config_db_connect.xml.php', 'xml', array('mode' => 'pretty'));
            
            $base->tmp_table_prefix = $_POST['table_prefix'];
        }
    }
}

?>