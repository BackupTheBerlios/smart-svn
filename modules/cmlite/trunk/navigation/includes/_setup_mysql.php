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
 * Setup of the navigation module
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}
    
// create table if it dosent exist
$sql = "CREATE TABLE {$this->B->conf_val['db']['table_prefix']}navigation_nested_set (
          node_id     int(10) unsigned NOT NULL default '0',
          parent_id   int(10) unsigned NOT NULL default '0',
          node_status tinyint(2) unsigned NOT NULL default '1',
          order_num   tinyint(4) unsigned NOT NULL default '0',
          level       int(10) unsigned NOT NULL default '0',
          left_id     int(10) unsigned NOT NULL default '0',
          right_id    int(10) unsigned NOT NULL default '0',
          name        varchar(255) NOT NULL default '',
          short_desc  text NOT NULL default '',
          long_desc   mediumtext NOT NULL default '',
          folder      char(32) NOT NULL default '',
          logo        varchar(255) NOT NULL default '',
          template    varchar(255) NOT NULL default '',
          PRIMARY KEY     (node_id),
          KEY right_id    (right_id),
          KEY status      (node_status),
          KEY left_id     (left_id),
          KEY order_num   (order_num),
          KEY level       (level),
          KEY parent_id   (parent_id),
          KEY right_left  (node_id,parent_id,left_id,right_id))";

$result = $this->B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

$result = $this->B->db->createSequence($this->B->conf_val['db']['table_prefix'].'navigation_nested_set');

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

// create table if it dosent exist
$sql = "CREATE TABLE {$this->B->conf_val['db']['table_prefix']}navigation_nested_set_locks (
          lockID char(32) NOT NULL default '',
          lockTable char(32) NOT NULL default '',
          lockStamp int(11) NOT NULL default '0',
          PRIMARY KEY  (lockID,lockTable)
        )";

$result = $this->B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

// create table if it dosent exist
$sql = "CREATE TABLE {$this->B->conf_val['db']['table_prefix']}navigation_attach (
          id_attach   int(11) unsigned NOT NULL auto_increment,
          id_node     int(11) unsigned NOT NULL default '0',
          order_num   tinyint(4) unsigned NOT NULL default '0',
          file        varchar(255) NOT NULL default '',
          type        varchar(255) NOT NULL default '',
          size        int(11) unsigned NOT NULL default '0',
          short_desc  varchar(255) NOT NULL default '',
          long_desc   text NOT NULL default '',
          PRIMARY KEY     (id_attach),
          KEY id_node     (id_node),
          KEY order_num   (order_num))";

$result = $this->B->db->query($sql);

if (DB::isError($result))
{
    trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
    $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    $success = FALSE;
}

?>