<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
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
                            'lock_time' => 'Timestamp',
                            'access'    => 'Timestamp',
                            'name'      => 'String',
                            'lastname'  => 'String',
                            'email'     => 'String',
                            'website'   => 'String',
                            'description'  => 'String',
                            'logo'         => 'String',
                            'media_folder' => 'String');
}

?>