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
    $base->tmp_error[]['error'] = 'Sysadmin name field is empty!';
}
if( empty($_POST['syslastname']) )
{
    $base->tmp_error[]['error'] = 'Sysadmin lastname field is empty!';
}
if( empty($_POST['syslogin']) )
{
    $base->tmp_error[]['error'] = 'Sysadmin login field is empty!';
}
if( empty($_POST['syspassword1']) || ($_POST['syspassword1'] != $_POST['syspassword2']) )
{
    $base->tmp_error[]['error'] = 'Sysadmin password fields are empty or not equal!';
} 

if( count($base->tmp_error) == 0 )
{
    switch($_POST['db_type'])
    {
        case 'mysql':
            // Create mysql tables
            include( SF_BASE_DIR.'/admin/modules/user/init_mysql.php' );  
            break;
        case 'sqlite':
            // Create sqlite tables
            include( SF_BASE_DIR.'/admin/modules/user/init_sqlite.php' );        
            break;   
        default:
            $base->tmp_error[]['error'] = 'The user module isnt supporting the selected database type!';        
            break;
    }

    include_once(SF_BASE_DIR.'/admin/modules/user/patUser/include/patUser.php');
    include_once(SF_BASE_DIR.'/admin/modules/user/class.sfPatUser.php');

    // patUser instance
    $base->user = new sfPatUser( TRUE, "smartUserData", "{$_POST['table_prefix']}user_sequence" );

    // set encrypting function
    if(!$base->user->setCryptFunction( array( $base->util, "md5_crypter" ) ))
    {
        patErrorManager::raiseError( "user", "setCryptFunction error", "File: ".__FILE__."\nLine: ".__LINE__ );    
    }

    $base->user->setAuthDbc( $base->db );

    //  this table stores all users
    $base->user->setAuthTable( $base->tmp_table_prefix.'user' );

    //  set required fieldnames
    $base->user->setAuthFields( array( 'primary'   =>  'uid',
                                       'username'  =>  'username',
                                       'passwd'    =>  'passwd' ) );

    //  this table stores group data
    $base->user->setGroupTable( $base->tmp_table_prefix.'user_group' );
    //  set fieldnames in the grouptable
    $base->user->setGroupFields( array( 'primary'   =>  'gid',
                                        'name'      =>  'name' ) );

    //  this table stores group data
    $base->user->setGroupRelTable( $base->tmp_table_prefix.'user_group_rel' );
    //  set fieldnames in the user - group relation table
    $base->user->setGroupRelFields( array( 'uid'   =>  'uid',
                                           'gid'   =>  'gid' ) );

    //  set tabel which stores permissions
    $base->user->setPermTable( $base->tmp_table_prefix.'user_permission' );
    //  set names of required fields and add an additional field 'part'
    $base->user->setPermFields( array( 'id'      =>  'id',
                                       'id_type' =>  'id_type',
                                       'id_part' =>  'id_part',
                                       'part'    =>  'part',
                                       'perms'   =>  'perms' ) );
    // add sysadmin group                            
    $base->tmp_gid = $base->user->addGroup( array('name' => 'sysadmin') );
    if(!$base->tmp_gid)
    {
        patErrorManager::raiseError( "user", "add group error", "File: ".__FILE__."\nLine: ".__LINE__ );    
    }

    // add sysadmin user
    $base->tmp_uid = $base->user->addUser( 
                          array( 'name'     => $_POST['sysname'],
                                 'lastname' => $_POST['syslastname'],
                                 'username' => $_POST['syslogin'],
                                 'passwd'   => $_POST['syspassword1']) );
    if(!$base->tmp_uid)
    {
        patErrorManager::raiseError( "user", "add user error", "File: ".__FILE__."\nLine: ".__LINE__ );    
    }    
    
    if(!$base->user->addUserToGroup( array( 'uid' => $base->tmp_uid, 'gid' => $base->tmp_gid ) ))
    {
        patErrorManager::raiseError( "user", "add userTOgroup error", "File: ".__FILE__."\nLine: ".__LINE__ );    
    }     
    
    if(!$base->user->addPermission( array( 'id_type' => 'group',
                                       'id'      => $base->tmp_gid,
                                       'id_part' =>  0,
                                       'part'    =>  '',
                                       'perms'   => array( 'read', 'delete', 'modify', 'add' ) ) ))
    {
        patErrorManager::raiseError( "user", "add permission error", "File: ".__FILE__."\nLine: ".__LINE__ );    
    }     
    
}

?>