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
 * check db connection and write db connect config file config_db_connect.xml.php
 *
 */
// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// Check directories accesses
if(!is_writeable( SF_BASE_DIR . '/data' ))
{
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/data';
}
if(!is_writeable( SF_BASE_DIR . '/data/db_sqlite' ))
{
    $B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/data/db_sqlite';
}

// Do setup if no error
if( $_POST['do_setup'] && (count($B->setup_error) == 0) )
{ 
    // Connect to the database
    $B->dbsystem = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/system.db.php');
        
    $sql_create_tables = implode('', file(SF_BASE_DIR . '/admin/include/db_system_tables.sql'));
    if( FALSE == $B->dbsystem->query($sql_create_tables) )
    {
        $B->setup_error[] = $B->dbsystem->get_error();
    }
    else
    {
        $sql = "INSERT INTO info (name,version) VALUES ('{$B->system_name}','{$B->system_version}')";
        if( FALSE == $B->dbsystem->query($sql) )
        {
            $B->setup_error[] = $B->dbsystem->get_error();
        }
        else
        {
            $sql = "INSERT INTO options (db,css_folder) VALUES ('smart','default')";
            if( FALSE == $B->dbsystem->query($sql) )
            {
                $B->setup_error[] = $B->dbsystem->get_error();
            }
            else
            {
                // Connect to the database
                $B->dbdata = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/smart_data.db.php');
            }
        }
    }
}

?>