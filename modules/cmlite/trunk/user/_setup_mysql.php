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

    $B->dsn = array('phptype'  => 'mysql',
                    'username' => $B->conf_val['db']['user'],
                    'password' => $B->conf_val['db']['passwd'],
                    'hostspec' => $B->conf_val['db']['host'],
                    'database' => $B->conf_val['db']['name']);

    $B->dboptions = array('debug'       => 2,
                          'portability' => DB_PORTABILITY_ALL);
    
    $B->db =& DB::connect($B->dsn, $B->dboptions);
    if (DB::isError($B->db))
    {
        $B->setup_error[] = 'Cannot connect to the database: '.__FILE__.' '.__LINE__ ; 
    }
    
    // create table if it dosent exist
    $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}user_users (
            uid      INT(11) NOT NULL default 0,
            status   TINYINT NOT NULL default 1,
            rights   TINYINT NOT NULL default 1,
            login    VARCHAR(30) NOT NULL,
            passwd   CHAR(32) NOT NULL,
            forename VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email    TEXT NOT NULL,
            KEY uid         (uid),
            KEY status      (status),
            KEY rights      (rights))";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    $result = $B->db->createSequence($B->conf_val['db']['table_prefix'].'user_seq_add_user');

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create table if it dosent exist
    $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}user_registered (
            uid      INT(11) NOT NULL,
            md5_str  CHAR(32) NOT NULL default '',
            reg_date DATETIME NOT NULL default '0000-00-00 00:00:00')";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // insert an administrator
    $forename  = $B->db->quoteSmart($B->util->stripSlashes($_POST['sysname']));
    $lastename = $B->db->quoteSmart($B->util->stripSlashes($_POST['syslastname']));
    $login     = $B->db->quoteSmart($B->util->stripSlashes($_POST['syslogin']));
    $passwd    = $B->db->quoteSmart(md5($_POST['syspassword1']));

    $uid = $B->db->nextId($B->conf_val['db']['table_prefix'].'user_seq_add_user');

    if (DB::isError($uid)) 
    {
        trigger_error($uid->getMessage(), E_USER_ERROR);
    }

    $sql = 'INSERT INTO '.$B->conf_val['db']['table_prefix'].'user_users 
                (uid,forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('.$uid.','.$forename.','.$lastename.','.$login.','.$passwd.',2,5)';
    
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
    unset($sql);
}

?>