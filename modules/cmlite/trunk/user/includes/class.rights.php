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
 * The rights class 
 *
 * Existing rights levels:
 * 1 Registered (No access to the admin interface. Only access to protected content of the public page)
 * 2 Contributor (Can propose content. But not modify)
 * 3 Author (Can add content and modify own content)
 * 4 Editor (Can add content, modify content and add/mod/delete users below own rights level)
 * 5 Administrator (Can do every thing)
 *
 */
 
class rights
{ 
    /**
     * check if a given user id is the same
     * as the logged user
     *
     * @param int $uid user id
     * @return bool
     */
    function is_logged_user ( $uid )
    {
        if($GLOBALS['B']->logged_id_user == (int) $uid)
            return TRUE;
        return FALSE;
    }
    
    /**
     * check if the logged user have rights to add users
     * 
     *
     * @return bool
     */
    function ask_access_to_add_user ()
    {       
        if( ($GLOBALS['B']->logged_user_rights > 3) )
        {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check if the logged user have rights to modify
     * data of other users
     *
     * @param int $uid user id to modify
     * @return bool
     */   
    function ask_access_to_modify_user ( $uid )
    {
        if(TRUE == rights::is_logged_user( $uid ))
            return TRUE;
            
        $fields = array('rights');
        $data = $GLOBALS['B']->user->get_user($uid, $fields);

        if( ($GLOBALS['B']->logged_user_rights == 4) )
        {
            if($data['rights'] < 4)
                return TRUE;
            return FALSE;
        }
        elseif( ($GLOBALS['B']->logged_user_rights == 5) )
        {
            return TRUE;
        }
        return FALSE;
    } 

    /**
     * check if the logged user have rights to set rights
     * of other users
     *
     * @param int $uid user id to modify
     * @param int $new_rights New rights level to set
     * @return bool
     */     
    function ask_set_rights ( $uid, $new_rights )
    {
        if( ($GLOBALS['B']->logged_user_rights == 5) )
        {
            return TRUE;
        } 
        if( ($GLOBALS['B']->logged_user_rights == 4) )
        {
            $fields = array('rights');
            $data = $GLOBALS['B']->user->get_user($uid, $fields);     
            
            if( ($data['rights'] > 3) || ($new_rights > 3) )
                return FALSE;
            else
                return TRUE;
        }
        return FALSE;
    }

    /**
     * check if the logged user have rights to set status
     * of other users
     *
     * @param int $uid user id to modify
     * @return bool
     */     
    function ask_set_status ( $uid )
    {
        if( ($GLOBALS['B']->logged_user_rights == 5) )
        {
            return TRUE;
        } 
        if( ($GLOBALS['B']->logged_user_rights == 4) )
        {
            $fields = array('rights');
            $data = $GLOBALS['B']->user->get_user($uid, $fields);     
            
            if( $data['rights'] >= $GLOBALS['B']->logged_user_rights )
                return FALSE;
            else
                return TRUE;
        }
        return FALSE;
    }    
}

?>
