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

    $B->dsn = array('phptype'  => 'sqlite',
                    'database' => $db_file,
                    'mode'     => '0775');

    $B->dboptions = array('debug'       => 2,
                          'portability' => DB_PORTABILITY_ALL);
    
    $B->db =& DB::connect($B->dsn, $B->dboptions);
    if ( DB::isError($B->db) ) 
    {
        $B->setup_error[] = 'Cannot connect to the database: '.__FILE__.' '.__LINE__ ; 
    }

    if(TRUE == $is_db_file)
    {
        // delete the user_users table if it exist
        $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='user_users'";
        $result = $B->db->query($sql);

        if($result->numRows() == 1)
        {
            $sql = "DROP TABLE user_users";
            $result = $B->db->query($sql);
            if ( DB::isError($result) )
            {
                $B->setup_error[] = $result->getMessage() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
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

    $result = $B->db->query($sql);

    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
    
    // insert an administrator
    $forename  = $B->db->quoteSmart($B->util->stripSlashes($_POST['sysname']));
    $lastename = $B->db->quoteSmart($B->util->stripSlashes($_POST['syslastname']));
    $login     = $B->db->quoteSmart($B->util->stripSlashes($_POST['syslogin']));
    $passwd    = $B->db->quoteSmart(md5($_POST['syspassword1']));

    $sql = 'INSERT INTO user_users 
                (forename,lastname,login,passwd,status,rights) 
              VALUES 
                ('.$forename.','.$lastename.','.$login.','.$passwd.',2,5)';

    $result = $B->db->query($sql);
    
    if (DB::isError($result))
    {
        $B->setup_error[] = $result->getMessage()."\nFILE: ".__FILE__."\nLINE: ".__LINE__;
    } 
}

?>