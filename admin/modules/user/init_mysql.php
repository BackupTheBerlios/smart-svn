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
 * Create mysql tables for this (user) module
 *
 *
 */

// Check if this file is included in the SMART environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

// Create user module tables
//

// User table
$res = & $base->db->query(
"CREATE TABLE IF NOT EXISTS {$base->tmp_table_prefix}user (
  uid int(10) unsigned NOT NULL,
  username varchar(20) NOT NULL default '',
  passwd char(20) NOT NULL default '',
  name varchar(100) NOT NULL default '',
  lastname varchar(100) NOT NULL default '',
  email varchar(200) NOT NULL default '',
  nologin tinyint(1) NOT NULL default '0',
  first_login datetime NOT NULL default '',
  last_login datetime NOT NULL default '',
  count_logins int(10) unsigned NOT NULL default '0',
  count_pages int(10) unsigned NOT NULL default '0',
  time_online int(11) NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY username (username)) TYPE=MyISAM");

if (DB::isError($res)) 
{
    patErrorManager::raiseError( 111, $res->getMessage() );
}

// User group table
$res = & $base->db->query(
"CREATE TABLE IF NOT EXISTS {$base->tmp_table_prefix}user_group (
  gid int(11) unsigned NOT NULL default '0',
  name varchar(50) default NULL,
  PRIMARY KEY (gid)) TYPE=MyISAM");
    
if (DB::isError($res)) 
{
    patErrorManager::raiseError( 111, $res->getMessage() );
}

// User group relation table
$res = & $base->db->query(
"CREATE TABLE IF NOT EXISTS {$base->tmp_table_prefix}user_group_rel (
  id int(10) NOT NULL,
  uid int(11) unsigned NOT NULL default '0',
  gid int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)) TYPE=MyISAM");
    
if (DB::isError($res)) 
{
    patErrorManager::raiseError( 111, $res->getMessage() );
}

// User permission table
$res = & $base->db->query(
"CREATE TABLE IF NOT EXISTS {$base->tmp_table_prefix}user_permission (
  id int(11) unsigned NOT NULL,
  id_type enum('group','user') NOT NULL default 'group',
  id_part int(11) NOT NULL default '0',
  part varchar(50) NOT NULL default '',
  perms set('read','delete','modify','add') NOT NULL default '') TYPE=MyISAM");
    
if (DB::isError($res)) 
{
    patErrorManager::raiseError( 111, $res->getMessage() );
}

?>