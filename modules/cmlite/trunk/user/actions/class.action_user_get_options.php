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
 * action_user_get_options class 
 *
 */
 
class action_user_get_options extends action
{
    /**
     * Execute view of the user module
     *
     * @param array $data
     */
    function perform( $data )
    {      
        M( MOD_SYSTEM, 'get_view', array('m' => 'user', 'view' => 'option') );
    } 
}

?>
