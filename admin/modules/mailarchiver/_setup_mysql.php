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
    // create table if it dosent exist
    $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}mailarchiver_lists (
            lid         INT(11) NOT NULL auto_increment,
            status      TINYINT NOT NULL default 1,
            name        VARCHAR(255) NOT NULL default '',
            description TEXT NOT NULL  default '',
            email       TEXT NOT NULL default '',
            emailserver TEXT NOT NULL default '',
            emailuser   VARCHAR(255) NOT NULL default '',
            emailpasswd VARCHAR(255) NOT NULL default '',
            folder      CHAR(32) NOT NULL,
            PRIMARY KEY     (lid),
            KEY status      (status))";

    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }
    
    // create table if it dosent exist
    $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}mailarchiver_messages (
            mid      INT(11) NOT NULL auto_increment,
            lid      INT(11) NOT NULL,
            mes_id   CHAR(32) NOT NULL,
            subject  TEXT NOT NULL  default '',
            sender   TEXT NOT NULL  default '',
            mdate    DATETIME default '0000-00-00 00:00:00' NOT NULL,
            body     MEDIUMTEXT default '' NOT NULL,
            folder   CHAR(32) NOT NULL,
            PRIMARY KEY     (mid),
            KEY lid         (lid),
            KEY mes_id      (mes_id))";

    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    // create table if it dosent exist
    $sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}mailarchiver_attach (
            aid      INT(11) NOT NULL auto_increment,
            mid      INT(11) NOT NULL,
            file     TEXT NOT NULL  default '',
            size     INT(11) NOT NULL,
            type     VARCHAR(200) NOT NULL  default '',
            PRIMARY KEY     (aid),
            KEY mid         (mid))";

    if ($B->conn->Execute($sql) === FALSE)
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }
    unset($sql);
}

?>