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


// get db connection data
$this->B->conf_val['db']['host']         = $_POST['dbhost'];
$this->B->conf_val['db']['user']         = $_POST['dbuser'];
$this->B->conf_val['db']['passwd']       = $_POST['dbpasswd'];
$this->B->conf_val['db']['name']         = $_POST['dbname'];
$this->B->conf_val['db']['table_prefix'] = $_POST['dbtablesprefix'];

// create db on demande
if(isset($_POST['create_db']))
{
    if(FALSE === ($_conn = @mysql_connect($this->B->conf_val['db']['host'], $this->B->conf_val['db']['user'], $this->B->conf_val['db']['passwd'])))
    {
        trigger_error('Cannot connect to the database host: '.__FILE__.' '.__LINE__, E_USER_ERROR);
        $this->B->setup_error[] = 'Cannot connect to the database host: '.__FILE__.' '.__LINE__;             
        return FALSE;
    }
        
    $sql = 'CREATE DATABASE IF NOT EXISTS ' . $_POST['dbname'];
    if(FALSE === @mysql_query($sql, $_conn))
    {
        trigger_error('Cannot create database: '.__FILE__.' '.__LINE__, E_USER_ERROR);
        $this->B->setup_error[] = 'Cannot create database: '.__FILE__.' '.__LINE__;                     
        return FALSE;
    }
    @mysql_close( $_conn );
}

$this->B->dsn = array('phptype'  => 'mysql',
                'username' => $this->B->conf_val['db']['user'],
                'password' => $this->B->conf_val['db']['passwd'],
                'hostspec' => $this->B->conf_val['db']['host'],
                'database' => $this->B->conf_val['db']['name']);

$this->B->dboptions = array('debug'       => 2,
                      'portability' => DB_PORTABILITY_ALL);
    
$this->B->db =& DB::connect($this->B->dsn, $this->B->dboptions);
if (DB::isError($this->B->db))
{
    trigger_error($this->B->db->getMessage()."\n".$this->B->db->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = 'Cannot connect to the database: '.__FILE__.' '.__LINE__ ; 
    return FALSE;
}
    
// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}user_users (
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

$result = $this->B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

$result = $this->B->db->createSequence($this->B->conf_val['db']['table_prefix'].'user_seq_add_user');

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}user_registered (
        uid      INT(11) NOT NULL,
        md5_str  CHAR(32) NOT NULL default '',
        reg_date DATETIME NOT NULL default '0000-00-00 00:00:00')";

$result = $this->B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

if($success != FALSE)
{
    // insert an administrator
    $forename  = $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['sysname']));
    $lastename = $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['syslastname']));
    $login     = $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['syslogin']));
    $passwd    = $this->B->db->quoteSmart(md5($_POST['syspassword1']));
    
    $uid = $this->B->db->nextId($this->B->conf_val['db']['table_prefix'].'user_seq_add_user');
    
    if (DB::isError($uid)) 
    {
        trigger_error($uid->getMessage()."\n".$uid->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        trigger_error($uid->getMessage(), E_USER_ERROR);
    }
    
    $sql = 'INSERT INTO '.$this->B->conf_val['db']['table_prefix'].'user_users 
                (uid,forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('.$uid.','.$forename.','.$lastename.','.$login.','.$passwd.',2,5)';
        
    $result = $this->B->db->query($sql);

    if (DB::isError($result))
    {
        trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
    unset($sql);
}

?>