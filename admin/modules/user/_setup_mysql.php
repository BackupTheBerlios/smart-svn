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


if( count($B->setup_error) == 0 )
{
    // include sqlite class
    include_once( SF_BASE_DIR . '/admin/modules/user/class.mysql.php' );
        
    // Set db connection data
    $B->conf_val['db']['host']         = $_POST['dbhost'];
    $B->conf_val['db']['user']         = $_POST['dbuser'];
    $B->conf_val['db']['passwd']       = $_POST['dbpasswd'];
    $B->conf_val['db']['name']         = $_POST['dbname'];
    $B->conf_val['db']['table_prefix'] = $_POST['dbtablesprefix'];

    // Connect to the main database
    $B->db = & new DB($B->conf_val['db']['host'],$B->conf_val['db']['user'],$B->conf_val['db']['passwd'],$B->conf_val['db']['name']);

    if($_POST['create_db'])
    {
        $B->tmp_sql = 'CREATE DATABASE IF NOT EXISTS '.$B->conf_val['db']['name'];
        $B->db->query( $B->tmp_sql );    
    }
    
    $B->db->select_db ( $B->conf_val['db']['name'] );
    
    $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}user_users (
            uid      INT(11) NOT NULL auto_increment,
            status   TINYINT NOT NULL default 1,
            rights   TINYINT NOT NULL default 1,
            login    VARCHAR(30) NOT NULL,
            passwd   CHAR(32) NOT NULL,
            forename VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email    VARCHAR(300) NOT NULL,
            PRIMARY KEY     (uid),
            KEY status      (status),
            KEY rights      (rights))";

    if( FALSE == $B->db->query($sql) )
    {
        $B->setup_error[] = $B->db->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }

    $forename  = $B->db->escapeString($_POST['sysname']);
    $lastename = $B->db->escapeString($_POST['syslastname']);
    $login     = $B->db->escapeString($_POST['syslogin']);
    $passwd    = md5($_POST['syspassword1']);

    $sql = "INSERT INTO {$B->conf_val['db']['table_prefix']}user_users 
                (forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('{$forename}','{$lastename}','{$login}','{$passwd}',2,5)";
    
    if( FALSE == $B->db->query($sql) )
    {
        $B->setup_error[] = $B->db->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }   
}

?>