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
 * user_filter_permission class 
 *
 */
 
class user_filter_permission
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
    function user_filter_permission()
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
        if(!is_object($this->B->user))
        {
            // the user class
            include_once SF_BASE_DIR . 'modules/user/includes/class.user.php';        
            //User Class instance
            $this->B->user = & new user;  
        }

        // User rights class
        include_once(SF_BASE_DIR.'modules/user/includes/class.rights.php');  

        switch($data['action'])
        {
            case 'modify':
                // check if the user of this request have rights to modify this user data
                if(FALSE == rights::ask_access_to_modify_user( $data['user_id'] ))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
                    exit;
                }            
                break;
            case 'add':
                // have rights to add users?
                if(FALSE == rights::ask_access_to_add_user ())
                {
                    @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
                    exit;
                }               
                break; 
            case 'set_rights':
                return rights::ask_set_rights ( $data['user_id'], $data['right'] );                        
                break;  
            case 'set_status':
                return rights::ask_set_status ( $data['user_id'] );                        
                break;                  
            case 'is_logged_user':
                return rights::is_logged_user ( $data['user_id'] );                         
                break;                
            default:
                trigger_error("Unknown permission action: {$data['action']} \nFILE:".__FILE__."\nLINE:".__LINE__, E_USER_ERROR);
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
                exit;
        }   
        return TRUE;
    }    
}

?>
