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
    
    // Connect to the database
    $B->dbsession = & new SqLite(SF_BASE_DIR . '/data/db_sqlite/session.db.php');
        
    include (SF_BASE_DIR . '/admin/include/db_system_tables.php');

    /* Create new object of class */
    $B->session = & new session();
    
    if (count($B->setup_error) == 0)
    {
        $name = $B->dbsystem->escapeString($B->system_name);
        $version = $B->dbsystem->escapeString($B->system_version);
        $sql = "INSERT INTO info (name,version) VALUES ('{$name}','{$version}')";
        if( FALSE == $B->dbsystem->query($sql) )
        {
            $B->setup_error[] = $B->dbsystem->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
        }
        else
        {
            $sql = "INSERT INTO options (db_prefix,css_folder) VALUES ('smart','default')";
            if( FALSE == $B->dbsystem->query($sql) )
            {
                $B->setup_error[] = $B->dbsystem->get_error() . "\nFILE: " . __FILE__ . "\nLINE: ". __LINE__;
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