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
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='earchive_lists'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE earchive_lists";
        if ( FALSE === $B->conn->Execute($sql))
        {
            $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
    }
    
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='earchive_messages'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE earchive_messages";
        $result = $B->db->query($sql);

        if (DB::isError($result))
        {
            $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        }
    }
    
    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='earchive_attach'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE earchive_attach";
        $result = $B->db->query($sql);

        if (DB::isError($result))
        {
            $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        }
    }    

    // delete the user_users table if it exist
    $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='earchive_words_crc32'";
    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if($result->numRows() == 1)
    {
        $sql = "DROP TABLE earchive_words_crc32";
        $result = $B->db->query($sql);

        if (DB::isError($result))
        {
            $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
        }
    } 
    
    // create the user_users table
    $sql = "CREATE TABLE earchive_lists (
            lid         INTEGER NOT NULL default 0,
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
    $sql = "CREATE INDEX earchive_lists_lid ON earchive_lists (lid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create index
    $sql = "CREATE INDEX earchive_lists_status ON earchive_lists (status)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
    
    // create table if it dosent exist
    $sql = "CREATE TABLE earchive_messages (
            mid      INTEGER NOT NULL default 0,
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
    $sql = "CREATE INDEX earchive_messages_mid ON earchive_messages (mid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create index
    $sql = "CREATE INDEX earchive_messages_lid ON earchive_messages (lid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 

    // create table if it dosent exist
    $sql = "CREATE TABLE earchive_attach (
            aid      INTEGER NOT NULL default 0,
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
    $sql = "CREATE INDEX earchive_attach_aid ON earchive_attach (aid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create index
    $sql = "CREATE INDEX earchive_attach_mid ON earchive_attach (mid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 

    // create index
    $sql = "CREATE INDEX earchive_attach_lid ON earchive_attach (lid)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
    
    $sql = "CREATE TABLE earchive_words_crc32 (
              word int(11) NOT NULL default 0,
              mid  int(11) NOT NULL default 0,
              lid  int(11) NOT NULL default 0)";

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }    
    unset($sql);
}

?>