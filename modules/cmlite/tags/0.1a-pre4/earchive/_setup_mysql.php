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
 * MySql setup of the user module
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}


// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}earchive_lists (
        lid         INT(11) NOT NULL default 0,
        status      TINYINT NOT NULL default 1,
        name        VARCHAR(255) NOT NULL default '',
        description TEXT NOT NULL  default '',
        email       TEXT NOT NULL default '',
        emailserver TEXT NOT NULL default '',
        folder      CHAR(32) NOT NULL,
        KEY lid         (lid),
        KEY status      (status))";

$result = $B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

$result = $B->db->createSequence($B->conf_val['db']['table_prefix'].'earchive_seq_add_list');
if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}
   
// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}earchive_messages (
        mid      INT(11) NOT NULL default 0,
        lid      INT(11) NOT NULL,
        subject  TEXT NOT NULL  default '',
        sender   TEXT NOT NULL  default '',
        mdate    DATETIME default '0000-00-00 00:00:00' NOT NULL,
        body     MEDIUMTEXT default '' NOT NULL,
        folder   VARCHAR(32) default '' NOT NULL,
        KEY mid         (mid),
        KEY lid         (lid))";

$result = $B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

$result = $B->db->createSequence($B->conf_val['db']['table_prefix'].'earchive_seq_add_message');

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

// create table if it dosent exist
$sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}earchive_attach (
        aid      INT(11) NOT NULL default 0,
        mid      INT(11) NOT NULL,
        lid      INT(11) NOT NULL,
        file     TEXT NOT NULL  default '',
        size     INT(11) NOT NULL,
        type     VARCHAR(200) NOT NULL  default '',
        KEY aid         (aid),
        KEY mid         (mid),
        KEY lid         (lid))";

$result = $B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

$result = $B->db->createSequence($B->conf_val['db']['table_prefix'].'earchive_seq_add_attach');

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

$sql = "CREATE TABLE IF NOT EXISTS {$B->conf_val['db']['table_prefix']}earchive_words_crc32 (
          word int(11) NOT NULL default 0,
          mid  int(11) NOT NULL default 0,
          lid  int(11) NOT NULL default 0)";
$result = $B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}
    
unset($sql);

?>