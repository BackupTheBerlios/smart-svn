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
    //create sqlite dir if it dosent exist
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
    
    // copy .htaccess to deny access from outside
    @copy(SF_BASE_DIR . '/admin/include/.htaccess', SF_BASE_DIR . '/data/db_sqlite/.htaccess');

    // the sqlite database file
    $db_file = SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php';
    
    if(file_exists($db_file))
        $is_db_file = TRUE;

    // instance of adodb
    $B->conn = ADONewConnection( 'sqlite' );
    
    // connect to the database
    if (!$B->conn->Connect( $db_file ))
    {
        $B->setup_error[] = $B->conn->ErrorMsg()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    }

    if(TRUE == $is_db_file)
    {
        // delete the user_users table if it exist
        $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='user_users'";
        $result = $B->conn->Execute($sql);

        if($result->RecordCount() == 1)
        {
            $sql = "DROP TABLE user_users";
            if ( FALSE === $B->conn->Execute($sql))
            {
                $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
            }
        }
    }
    
    // create the user_users table
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

    if ( FALSE === $B->conn->Execute($sql))
    {
        $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }
    
    // insert an administrator
    $forename  = $B->conn->qstr($_POST['sysname'],           magic_quotes_runtime());
    $lastename = $B->conn->qstr($_POST['syslastname'],       magic_quotes_runtime());
    $login     = $B->conn->qstr($_POST['syslogin'],          magic_quotes_runtime());
    $passwd    = $B->conn->qstr(md5($_POST['syspassword1']), magic_quotes_runtime());

    $sql = 'INSERT INTO user_users 
                (forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('.$forename.','.$lastename.','.$login.','.$passwd.',2,5)';
    
    if ( FALSE === $B->conn->Execute($sql))
    {
        $B->setup_error[] = $B->conn->ErrorMsg() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
    }
}

?>