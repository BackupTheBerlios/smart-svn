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
 * earchive_filter_permission class 
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
 
class earchive_filter_permission
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function earchive_filter_permission()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Check user permission to execute user edit (modify) operations
     *
     * @return bool true on success else false
     */
    function perform( $data )
    {
        switch($data['action'])
        {
            case 'access':
                return $this->ask_access_to_list ();                        
                break;  
            case 'delete':
                return $this->ask_access_to_delete_list ();                        
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
    function ask_access_to_list ()
    {       
        if( ($this->B->logged_user_rights > 3) )
        {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check rights to delete a list
     * 5 (administrator required)
     *
     * @return bool
     */
    function ask_access_to_delete_list ()
    {       
        if( ($this->B->logged_user_rights == 5) )
        {
            return TRUE;
        }
        return FALSE;
    }       
}

?>
