<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ActionUser class 
 * Some user action classes may extends this class
 *
 */

class ActionUser extends SmartAction
{
    /**
     * Fields and its format of the db table user_user 
     *
     */
    protected $tblFields_user = 
                      array('id_user'   => 'Int',
                            'login'     => 'String',
                            'role'      => 'Int',
                            'status'    => 'Int',
                            'lock'      => 'Int',
                            'lock_time' => 'String',
                            'access'    => 'String',
                            'passwd'    => 'String',
                            'name'      => 'String',
                            'lastname'  => 'String',
                            'email'     => 'String',
                            'description'  => 'String',
                            'format'       => 'Int',
                            'logo'         => 'String',
                            'media_folder' => 'String');

    /**
     * User role levels 
     *
     */                            
    protected $userRole = array('10' => 'Superuser',
                                '20' => 'Administrator',
                                '40' => 'Editor',
                                '60' => 'Author',
                                '80' => 'Contributor',
                                '100' => 'Webuser');                            
}

?>
