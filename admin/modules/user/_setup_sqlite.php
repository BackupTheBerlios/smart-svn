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
    if(!is_dir(SF_BASE_DIR . '/data/db_sqlite'))
    {
        if(!@mkdir(SF_BASE_DIR . '/data/db_sqlite'))
        {
            $B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . '/data/db_sqlite';
        }
        elseif(!@is_writeable( SF_BASE_DIR . '/data/db_sqlite' ))
        {
            $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/data/db_sqlite';
        }  
    }
        
    @copy(SF_BASE_DIR . '/admin/include/.htaccess', SF_BASE_DIR . '/data/db_sqlite/.htaccess');

    // include sqlite class
    include_once( SF_BASE_DIR . '/admin/modules/user/class.sqlite.php' );
    
    // Connect to the database
    $B->db = & new DB(SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php');
    $B->db->turboMode(); 

    //$B->tmp_inf = $B->db->getTableInfo('user_users', 'tbl_name');

    if(!empty($B->tmp_inf))
    {
        $sql = "DROP TABLE user_users";
        if( FALSE == $B->db->query($sql) )
        {
            $B->setup_error[] = $B->db->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
    }
    
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

    if( FALSE == $B->db->query($sql) )
    {
        $B->setup_error[] = $B->db->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }

    $forename  = $B->db->escapeString($_POST['sysname']);
    $lastename = $B->db->escapeString($_POST['syslastname']);
    $login     = $B->db->escapeString($_POST['syslogin']);
    $passwd    = md5($_POST['syspassword1']);

    $sql = "INSERT INTO user_users 
                (forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('{$forename}','{$lastename}','{$login}','{$passwd}',2,5)";
    
    if( FALSE == $B->db->query($sql) )
    {
        $B->setup_error[] = $B->db->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }
}

?>