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
 * User module authentication
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

include_once(SF_BASE_DIR.'/admin/modules/user/patUser/include/patUser.php');

// patUser instance
$base->user = new patUser( TRUE, "smartUserData", "{$base->db_data['db_table_prefix']}user_sequence" );

// set encrypting function
$base->user->setCryptFunction( array( $base->util, "md5_crypter" ) );

$base->user->setAuthDbc( $base->db );

//  this table stores all users
$base->user->setAuthTable( $base->db_data['db_table_prefix']."user" );

//  set required fieldnames
$base->user->setAuthFields( array( "primary"   =>  "uid",
                                   "username"  =>  "username",
                                   "passwd"    =>  "passwd" ) );

// set login template
if(@is_file(SF_BASE_DIR . '/templates/UserLogin.tpl.html'))
{
    $base->user->loginTemplate = "/templates/UserLogin.tpl.html";
}
else
{
    $base->user->loginTemplate = "/admin/modules/user/templates/UserLogin.tpl.html";
}

// set Unauthorized user template
if(@is_file(BASE_DIR . '/templates/UserUnauthorized.tpl.html'))
{
    $base->user->unauthorizedTemplate = "/templates/UserUnauthorized.tpl.html";
}
else
{
    $base->user->unauthorizedTemplate = "/admin/modules/user/templates/UserUnauthorized.tpl.html";
}


//  patTemplate object for Login screen
//  can be left out if you want to use HTTP authentication
$base->user->setTemplate( $base->tpl );

//  maximum login attempts
$base->user->setMaxLoginAttempts( 10 );
    
//  this table stores group data
$base->user->setGroupTable( $base->db_data['db_table_prefix']."user_group" );
//  set fieldnames in the grouptable
$base->user->setGroupFields( array( "primary"   =>  "gid",
                                    "name"      =>  "name" ) );

//  this table stores group data
$base->user->setGroupRelTable( $base->db_data['db_table_prefix']."user_group_rel" );
//  set fieldnames in the user - group relation table
$base->user->setGroupRelFields( array( "uid"   =>  "uid",
                                       "gid"   =>  "gid" ) );

//  set tabel which stores permissions
$base->user->setPermTable( $base->db_data['db_table_prefix']."user_group_permission" );
$base->user->setPermFields( array( "id"      =>  "id",
                                   "id_type" =>  "id_type",
                                   "id_part" =>  "id_part",
                                   "part"    =>  "part",
                                   "perms"   =>  "perms" ) );

//  use statistic functions
$base->user->addStats( "first_login", "first_login" );
$base->user->addStats( "last_login", "last_login" );
$base->user->addStats( "count_logins", "count_logins" );
$base->user->addStats( "count_pages", "count_pages" );
$base->user->addStats( "time_online", "time_online" );


// do authentication
$base->user->requireAuthentication( "displayLogin" );

?>
