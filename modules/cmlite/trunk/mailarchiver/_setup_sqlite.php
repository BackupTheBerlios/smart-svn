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

/** 
 * Check if this file is included in the environement
 */
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

if( count($B->setup_error) == 0 )
{
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='mailarchiver_lists'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE mailarchiver_lists";
        if ( FALSE === $B->conn->Execute($sql))
        {
            $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
    }
    
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='mailarchiver_messages'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE mailarchiver_messages";
        $result = $B->db->query($sql);

        if (DB::isError($result))
        {
            $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        }
    }
    
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='mailarchiver_attach'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE mailarchiver_attach";
        $result = $B->db->query($sql);

        if (DB::isError($result))
        {
            $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
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

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create index
    $sql = "CREATE INDEX mailarchiver_lists_status ON mailarchiver_lists (status)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
    
    // create table if it dosent exist
    $sql = "CREATE TABLE mailarchiver_messages (
            mid      INTEGER NOT NULL PRIMARY KEY,
            lid      INT(11) NOT NULL,
            subject  TEXT NOT NULL default '',
            sender   TEXT NOT NULL default '',
            mdate    DATETIME NOT NULL default '0000-00-00 00:00:00',
            body     TEXT NOT NULL default '',
            folder   CHAR(32) NOT NULL default '')";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create index
    $sql = "CREATE INDEX mailarchiver_messages_lid ON mailarchiver_messages (lid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 

    // create table if it dosent exist
    $sql = "CREATE TABLE mailarchiver_attach (
            aid      INTEGER NOT NULL PRIMARY KEY,
            mid      INT(11) NOT NULL,
            lid      INT(11) NOT NULL,
            file     TEXT NOT NULL default '',
            size     INT(11) NOT NULL,
            type     VARCHAR(200) NOT NULL default '')";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 

    // create index
    $sql = "CREATE INDEX mailarchiver_attach_mid ON mailarchiver_attach (mid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 

    // create index
    $sql = "CREATE INDEX mailarchiver_attach_lid ON mailarchiver_attach (lid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }     
    unset($sql);
}

?>