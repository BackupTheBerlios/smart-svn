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
 * filter_user_permission class 
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
 
class action_user_permission extends action
{
    /**
     * Check user permission to execute user edit (modify) operations
     *
     * @return bool true on success else false
     */
    function perform( $data )
    {    
        switch($data['action'])
        {
            case 'modify':
                // check if the user of this request have rights to modify this user data
                if(FALSE == $this->ask_access_to_modify_user( $data['user_id'] ))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
                    exit;
                }            
                break;
            case 'add':
                // have rights to add users?
                if(FALSE == $this->ask_access_to_add_user ())
                {
                    @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
                    exit;
                }               
                break;                
            case 'set_rights':
                return $this->ask_set_rights ( $data['user_id'], $data['right'] );                        
                break;  
            case 'set_status':
                return $this->ask_set_status ( $data['user_id'] );                        
                break;                  
            case 'is_logged_user':
                return $this->is_logged_user ( $data['user_id'] );                         
                break;                
            default:
                trigger_error("Unknown permission action: {$data['action']} \nFILE:".__FILE__."\nLINE:".__LINE__, E_USER_ERROR);
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
                exit;
        }   
        return TRUE;
    }  
    
    /**
     * check if a given user id is the same
     * as the logged user
     *
     * @param int $uid user id
     * @return bool
     */
    function is_logged_user ( $uid )
    {
        if($this->B->user_logged_uid == (int) $uid)
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
        if( ($this->B->user_logged_rights > 3) )
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
        if(TRUE == $this->is_logged_user( $uid ))
            return TRUE;
        
        // get user rights
        M( MOD_USER,
           'get',
           array( 'error'   => 'tmp_error',
                  'result'  => 'tmp_data',
                  'user_id' => $uid,
                  'fields'  => array('rights') ));

        $rights = $this->B->tmp_data['rights'];
        $this->B->tmp_data['rights'] = NULL;

        if( ($this->B->user_logged_rights == 4) )
        {
            if($rights < 4)
            {
                return TRUE;
            }
            return FALSE;
        }
        elseif( ($this->B->user_logged_rights == 5) )
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
        if( ($this->B->user_logged_rights == 5) )
        {
            return TRUE;
        } 
        if( ($this->B->user_logged_rights == 4) )
        {
            // get user rights
            M( MOD_USER,
               'get',
               array( 'error'   => 'tmp_error',
                      'result'  => 'tmp_data',
                      'user_id' => $uid,
                      'fields'  => array('rights') ));     

            $rights = $this->B->tmp_data['rights'];
            $this->B->tmp_data['rights'] = NULL;
            
            if( ($rights > 3) || ($new_rights > 3) )
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
        if( ($this->B->user_logged_rights == 5) )
        {
            return TRUE;
        } 
        if( ($this->B->user_logged_rights == 4) )
        {
            // get user rights
            M( MOD_USER,
               'get',
               array( 'error'   => 'tmp_error',
                      'result'  => 'tmp_data',
                      'user_id' => $uid,
                      'fields'  => array('rights') ));
                                
            $rights = $this->B->tmp_data['rights'];
            $this->B->tmp_data['rights'] = NULL;
            
            if( $rights >= $this->B->user_logged_rights )
                return FALSE;
            else
                return TRUE;
        }
        return FALSE;
    }        
}

?>
