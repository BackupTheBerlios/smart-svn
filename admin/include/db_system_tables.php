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
 * Create system db tables
 *
 */
// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

$sql = "CREATE TABLE info ( 
name VARCHAR(50) not null, 
version VARCHAR(50) not null)";

if( FALSE == $B->dbsystem->query($sql) )
{
    $B->setup_error[] = $B->dbsystem->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
}


$sql = "CREATE TABLE modules (
name VARCHAR(50) NOT NULL PRIMARY KEY, 
version VARCHAR(20) NOT NULL, 
info TEXT NOT NULL default '')";

if( FALSE == $B->dbsystem->query($sql) )
{
    $B->setup_error[] = $B->dbsystem->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
}

$sql = "CREATE TABLE options ( 
db_prefix VARCHAR(30) NOT NULL, 
css_folder VARCHAR(30) NOT NULL)";

if( FALSE == $B->dbsystem->query($sql) )
{
    $B->setup_error[] = $B->dbsystem->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
}

?>