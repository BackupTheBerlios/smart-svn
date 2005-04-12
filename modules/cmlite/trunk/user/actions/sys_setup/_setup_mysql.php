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

// create db
function db_create( & $B )
{
    if(FALSE == ($_conn = @mysql_connect($B->conf_val['db']['host'], $B->conf_val['db']['user'], $B->conf_val['db']['passwd'])))
    {
        trigger_error('Cannot connect to the database host: '.__FILE__.' '.__LINE__, E_USER_ERROR);
        $B->setup_error[] = 'Cannot connect to the database host: '.__FILE__.' '.__LINE__;             
        return FALSE;
    }
    
    $sql = 'CREATE DATABASE IF NOT EXISTS ' . $_POST['dbname'];
    if(FALSE == @mysql_query($sql, $_conn))
    {
        trigger_error('Cannot create database: '.__FILE__.' '.__LINE__, E_USER_ERROR);
        $B->setup_error[] = 'Cannot create database: '.__FILE__.' '.__LINE__;                     
        return FALSE;
    }
    @mysql_close( $_conn );
    return TRUE;
}

// create db on demande
if(isset($_POST['create_db']))
{
    if(FALSE == ($success = db_create( $this->B )))
    {
        return FALSE;
    }
}

$this->B->dsn = array('phptype'  => 'mysql',
                'username' => $this->B->conf_val['db']['user'],
                'password' => $this->B->conf_val['db']['passwd'],
                'hostspec' => $this->B->conf_val['db']['host'],
                'database' => $this->B->conf_val['db']['name']);

// include PEAR DB class
include_once( SF_BASE_DIR . 'modules/common/PEAR/MDB2.php');  
    
$this->B->db =& MDB2::connect( $this->B->dsn );
if (MDB2::isError($this->B->db))
{
    trigger_error($this->B->db->getMessage()."\n".$this->B->db->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = 'Cannot connect to the database: '.__FILE__.' '.__LINE__ ; 
    $success = FALSE;
    return FALSE;
}
    
// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}user_users (
        uid      INT(11) NOT NULL auto_increment,
        status   TINYINT NOT NULL default 1,
        rights   TINYINT NOT NULL default 1,
        login    VARCHAR(30) NOT NULL,
        passwd   CHAR(32) NOT NULL,
        forename VARCHAR(50) NOT NULL,
        lastname VARCHAR(50) NOT NULL,
        email    TEXT NOT NULL,
        PRIMARY KEY     (uid),
        KEY login       (login),
        KEY passwd      (passwd),
        KEY status      (status),
        KEY rights      (rights))";

$result = $this->B->db->query($sql);

if (MDB2::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
    return FALSE;
}

// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}user_registered (
        uid      INT(11) NOT NULL,
        md5_str  CHAR(32) NOT NULL default '',
        reg_date DATETIME NOT NULL default '0000-00-00 00:00:00')";

$result = $this->B->db->query($sql);

if (MDB2::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
    return FALSE;
}

if($success != FALSE)
{
    // insert an administrator
    $forename  = $this->B->db->escape(commonUtil::stripSlashes($_POST['sysname']));
    $lastename = $this->B->db->escape(commonUtil::stripSlashes($_POST['syslastname']));
    $login     = $this->B->db->escape(commonUtil::stripSlashes($_POST['syslogin']));
    $passwd    = $this->B->db->escape(md5($_POST['syspassword1']));
    
    $sql = "INSERT INTO ".$this->B->conf_val["db"]["table_prefix"]."user_users 
                (forename,lastname,login,passwd,status,rights,email) 
              VALUES 
                (".$forename.",".$lastename.",".$login.",".$passwd.",2,5,"admin@foo.com")";
        
    $result = $this->B->db->query($sql);

    if (MDB2::isError($result))
    {
        trigger_error($result->getMessage()."\n".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->getCode()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        $success = FALSE;
        return FALSE;
    } 
    unset($sql);
}

?>