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
 * Sqlite setup of the user module
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

if( count($B->setup_error) == 0 )
{
    // the sqlite database file
    $db_file = SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php';
    
    if(file_exists($db_file))
        $is_db_file = TRUE;

    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='mailarchiver_lists'";
    $result = $B->conn->Execute($sql);

    if($result->RecordCount() == 1)
    {
        $sql = "DROP TABLE mailarchiver_lists";
        if ( FALSE === $B->conn->Execute($sql))
        {
            $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
    }
    
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='mailarchiver_messages'";
    $result = $B->conn->Execute($sql);

    if($result->RecordCount() == 1)
    {
        $sql = "DROP TABLE mailarchiver_messages";
        if ( FALSE === $B->conn->Execute($sql))
        {
            $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
    }
    
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='mailarchiver_attach'";
    $result = $B->conn->Execute($sql);

    if($result->RecordCount() == 1)
    {
        $sql = "DROP TABLE mailarchiver_attach";
        if ( FALSE === $B->conn->Execute($sql))
        {
            $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
    }    
    
    // create the user_users table
    $sql = "CREATE TABLE mailarchiver_lists (
            lid         INTEGER NOT NULL PRIMARY KEY,
            status      TINYINT NOT NULL default 1,
            name        VARCHAR(50) NOT NULL default '',
            description TEXT NOT NULL default '',
            email       VARCHAR(255) NOT NULL default '',
            emailserver TEXT NOT NULL default '',            
            folder      CHAR(32) NOT NULL)";

    if ( FALSE === $B->conn->Execute($sql))
    {
        $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }
    
    // create table if it dosent exist
    $sql = "CREATE TABLE mailarchiver_messages (
            mid      INTEGER NOT NULL PRIMARY KEY,
            lid      INT(11) NOT NULL,
            mes_id   CHAR(32) NOT NULL,
            subject  TEXT NOT NULL default '',
            sender   TEXT NOT NULL default '',
            mdate    DATETIME NOT NULL default '0000-00-00 00:00:00',
            body     TEXT NOT NULL default '',
            folder   CHAR(32) NOT NULL default '')";

    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create table if it dosent exist
    $sql = "CREATE TABLE mailarchiver_attach (
            aid      INTEGER NOT NULL PRIMARY KEY,
            mid      INT(11) NOT NULL,
            lid      INT(11) NOT NULL,
            file     TEXT NOT NULL default '',
            size     INT(11) NOT NULL,
            type     VARCHAR(200) NOT NULL default '')";

    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }
    unset($sql);
}

?>