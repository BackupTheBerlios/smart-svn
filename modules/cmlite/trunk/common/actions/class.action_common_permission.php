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
 * filter_earchive_permission class 
 *
 * The permission rights levels:
 * 
 * -
 * 5 (ADMINISTRATOR)
 * -
 * # all permssion rights
 *
 * -
 * 4 (EDITOR) 
 * -
 * # add user
 * # modify user below level 4
 * 
 * -
 * 3 (AUTHOR) 
 * -
 * # modify own user account
 * 
 * -
 * 2 (CONTRIBUTOR) 
 * -
 * # modify own user account
 *
 * -
 * 1 (RESTRICTED) 
 * -
 * # no admin rights
 *
 */
 
class action_common_permission extends action
{
    /**
     * Check user permission to execute user edit (modify) operations
     *
     * @return bool true on success else false
     */
    function perform( & $data )
    {
        switch($data['action'])
        {
            case 'access':
                return $this->_ask_access ();                        
                break;                                 
            default:
                return FALSE;
        }   
        return TRUE;
    }  
    
    /**
     * check rights to add/modify a list
     * 4 or 5 (editor or administrator) required
     *
     * @return bool
     */
    function _ask_access ()
    {       
        if( ($this->B->user_logged_rights > 1) )
        {
            return TRUE;
        }
        return FALSE;
    }      
}

?>
