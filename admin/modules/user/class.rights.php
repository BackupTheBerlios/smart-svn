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
 * The user class 
 *
 * 
 *
 */
 
class rights
{   
    function is_login_user( $uid )
    {
        if($GLOBALS['B']->auth->id_user == (int) $uid)
            return TRUE;
        return FALSE;
    }
    
    function can_change_rights( $uid )
    {
        if( TRUE == rights::is_login_user( $uid ) )
            return FALSE;
            
        //$fields = array('rights');
        //$data = $GLOBALS['B']->user->get_user( $uid, $fields );
        
        
    } 
    
}

?>
