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
    // get db connection data
    $B->conf_val['db']['host']         = $_POST['dbhost'];
    $B->conf_val['db']['user']         = $_POST['dbuser'];
    $B->conf_val['db']['passwd']       = $_POST['dbpasswd'];
    $B->conf_val['db']['name']         = $_POST['dbname'];
    $B->conf_val['db']['table_prefix'] = $_POST['dbtablesprefix'];

    // create db on demande
    if(isset($_POST['create_db']))
    {
        if(FALSE === ($_conn = @mysql_connect($B->conf_val['db']['host'], $B->conf_val['db']['user'], $B->conf_val['db']['passwd'])))
        {
            $B->setup_error[] = 'Cannot connect to the database host: '.__FILE__.' '.__LINE__ ;             
        }
        
        $sql = 'CREATE DATABASE IF NOT EXISTS ' . $_POST['dbname'];
        if(FALSE === @mysql_query($sql, $_conn))
        {
            $B->setup_error[] = 'Cannot create database: '.__FILE__.' '.__LINE__ ;                     
        }
        @mysql_close( $_conn );
    }

    // instance of adodb
    $B->conn = ADONewConnection( 'mysql' );
    
    if (!$B->conn->Connect( $B->conf_val['db']['host'], $B->conf_val['db']['user'], $B->conf_val['db']['passwd'], $B->conf_val['db']['name'] ))
    {
        $B->setup_error[] = 'Cannot connect to the database: '.__FILE__.' '.__LINE__ ;            
    }
    
    // create table if it dosent exist
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

    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // insert an administrator
    $forename  = $B->conn->qstr($B->util->stripSlashes($_POST['sysname']),     magic_quotes_runtime());
    $lastename = $B->conn->qstr($B->util->stripSlashes($_POST['syslastname']), magic_quotes_runtime());
    $login     = $B->conn->qstr($B->util->stripSlashes($_POST['syslogin']),    magic_quotes_runtime());
    $passwd    = $B->conn->qstr(md5($_POST['syspassword1']),    magic_quotes_runtime());

    $sql = 'INSERT INTO '.$B->conf_val['db']['table_prefix'].'user_users 
                (forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('.$forename.','.$lastename.','.$login.','.$passwd.',2,5)';
    
    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }  
    unset($sql);
}

?>