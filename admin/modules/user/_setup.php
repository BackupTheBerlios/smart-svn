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

// Do setup 
if( empty($_POST['sysname']) )
{
    $B->setup_error[] = 'Sysadmin name field is empty!';
}
if( empty($_POST['syslastname']) )
{
    $B->setup_error[] = 'Sysadmin lastname field is empty!';
}
if( empty($_POST['syslogin']) )
{
    $B->setup_error[] = 'Sysadmin login field is empty!';
}
if( empty($_POST['syspassword1']) || ($_POST['syspassword1'] != $_POST['syspassword2']) )
{
    $B->setup_error[] = 'Sysadmin password fields are empty or not equal!';
} 

if( count($B->setup_error) == 0 )
{
    $sql = "CREATE TABLE user_users (
            uid      INTEGER NOT NULL PRIMARY KEY,
            status   TINYINT NOT NULL default 1,
            rights   TINYINT NOT NULL default 1,
            login    VARCHAR(30) NOT NULL,
            passwd   CHAR(32) NOT NULL,
            forename VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            email    VARCHAR(300) NOT NULL default '',
            desc     TEXT NOT NULL default '')";

    if( FALSE == $B->dbdata->query($sql) )
    {
        $B->setup_error[] = $B->dbdata->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }

    $forename  = $B->dbdata->escapeString($_POST['sysname']);
    $lastename = $B->dbdata->escapeString($_POST['syslastname']);
    $login     = $B->dbdata->escapeString($_POST['syslogin']);
    $passwd    = md5($_POST['syspassword1']);

    $sql = "INSERT INTO user_users 
                (forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('{$forename}','{$lastename}','{$login}','{$passwd}',2,5)";
    
    if( FALSE == $B->dbdata->query($sql) )
    {
        $B->setup_error[] = $B->dbdata->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }
    
    $sql = "INSERT INTO modules (name,version) VALUES ('user','0.1')";
    if( FALSE == $B->dbsystem->query($sql) )
    {
        $B->setup_error[] = $B->dbsystem->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }   
}

?>