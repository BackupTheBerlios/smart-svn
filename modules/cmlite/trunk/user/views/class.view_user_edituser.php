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
 * view_user_edituser class of the template "tpl.user_edituser.php"
 *
 */
 
class view_user_edituser extends view
{
    /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'user_edituser';
    
    /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/user/templates/';
  
    /**
     * Execute the view of the template "login.tpl.php"
     * 
     * edit and update or delete user data
     *
     * @return bool
     */
    function perform()
    {  
        // check permission to modify user data
        M( MOD_USER,
           'permission',
           array( 'action'  => 'modify',
                  'user_id' => (int)$_REQUEST['uid']));
        
        // if some user data has change
        if( isset($_POST['modifyuserdata']) )
        {
            if(FALSE == $this->_modify_user())
            {
                return TRUE;
            }
        }     
    
        // prepare data array
        $_data = array( 'error'   => 'tpl_error',
                        'user_id' => (int)$_REQUEST['uid'],
                        'result'  => 'tpl_data',
                        'fields'  => array('uid',
                                           'rights',
                                           'status',
                                           'email',
                                           'login',
                                           'forename',
                                           'lastname'));
        // get user data
        M( MOD_USER,
           'get',
           $_data );        
         
        return  TRUE;
    }    

    /**
     * check form user data, rights and update user data
     *
     * @return bool true on success else false
     * @access privat
     */
    function _modify_user()
    {
        if (FALSE == $this->_check_delete_user())
        {
            $this->_reset_old_fields_data();
            return FALSE;
        }        
        if (FALSE == $this->_check_own_changes())
        {
            $this->_reset_old_fields_data();
            return FALSE;
        }  
        if (FALSE == $this->_check_rights_level_changes())
        {
            $this->_reset_old_fields_data();
            return FALSE;
        }  
        if (FALSE == $this->_check_set_status())
        {
            $this->_reset_old_fields_data();
            return FALSE;
        }   
        if (FALSE == $this->_update_user_data())
        {
            $this->_reset_old_fields_data();
            return FALSE;
        }  
        return TRUE;
    }

    /**
     * delete user
     *
     * @return bool true on success else false
     * @access privat
     */    
    function _check_delete_user()
    {
        if($_POST['deluser'] == "1")
        {
            // check permission
            $_is_logged_user = M( MOD_USER,
                                  'permission',
                                  array( 'action'  => 'is_logged_user',
                                         'user_id' => (int) $_POST['uid']));  
            if(TRUE == $_is_logged_user)
            {
                $this->B->tpl_error   = 'You can remove your own user account!';
                return FALSE;
            }
            else
            {
                // Delete user
                if(TRUE == M( MOD_USER,
                              'delete',
                              array( 'error'   => 'tpl_error',
                                     'user_id' => (int) $_POST['uid'])))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=user');
                    exit;   
                }
            }
        }
        return TRUE;
    }

    /**
     * check if a user try to change own rights and status
     *
     * @return bool true on success else false
     * @access privat
     */   
    function _check_own_changes()
    {
        if( ($_POST['rights_orig'] != (int)$_POST['rights']) || ($_POST['status_orig'] != (int)$_POST['status']) )    
        {
            // check permission
            $_is_logged_user = M( MOD_USER,
                                  'permission',
                                  array( 'action'  => 'is_logged_user',
                                         'user_id' => (int) $_POST['uid']));  
            if(TRUE == $_is_logged_user)
            {
                $this->B->tpl_error   = 'You can not change your own rights or status!';
                return FALSE;
            }        
        }
        return TRUE;
    }

    /**
     * check rights to change the rights of an other user
     *
     * @return bool true on success else false
     * @access privat
     */   
    function _check_rights_level_changes()
    {
        if( $_POST['rights_orig'] != (int)$_POST['rights'] )
        {
            // check permission
            $_success = M( MOD_USER,
                           'permission',
                           array( 'action'  => 'set_rights',
                                  'user_id' => (int) $_POST['uid'],
                                  'right'   => (int)$_POST['rights'])); 
                                               
            if(FALSE == $_success)
            {
                $this->B->tpl_error = 'You can not change to this rights level!'; 
                return FALSE;
            }
        }  
        return TRUE;
    }

    /**
     * check rights to set the status of an other user
     *
     * @return bool true on success else false
     * @access privat
     */       
    function _check_set_status()
    {
        if( $_POST['status_orig'] != (int)$_POST['status'] )
        {
            // check permission
            $_success = M( MOD_USER,
                           'permission',
                           array( 'action'  => 'set_status',
                                  'user_id' => (int) $_POST['uid'])); 
           
           if(FALSE == $_success)
           {
              $this->B->tpl_error = 'You can not change status of this user!';
              return FALSE;
           }
        } 
        return TRUE;;
    }

    /**
     * update user data
     *
     * @return bool true on success else false
     * @access privat
     */   
    function _update_user_data()
    {
        // prepare user data array
        $_data = array( 'error'     => 'tpl_error',
                        'user_id'   => (int)$_REQUEST['uid'],
                        'user_data' => array( 'forename' => commonUtil::stripSlashes($_POST['forename']),
                                              'lastname' => commonUtil::stripSlashes($_POST['lastname']),
                                              'email'    => commonUtil::stripSlashes($_POST['email']),
                                              'rights'   => (int)$_POST['rights'],
                                              'status'   => (int)$_POST['status'] ));
            
        // update password if it isnt empty
        if(!empty($_POST['passwd']))
        {
            $_data['user_data']['passwd'] = md5($_POST['passwd']);
        }
            
        // update user data
        if(FALSE != M( MOD_USER,
                       'update',
                       $_data))
        {
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=user');
            exit;
        }   
        return FALSE;
    }

    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        $this->B->tpl_data['forename'] = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
        $this->B->tpl_data['lastname'] = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
        $this->B->tpl_data['email']    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
        $this->B->tpl_data['login']    = htmlspecialchars(commonUtil::stripSlashes($_POST['_login']));
        $this->B->tpl_data['passwd']   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));
        $this->B->tpl_data['rights']   = $_POST['rights'];
        $this->B->tpl_data['status']   = $_POST['status'];            
    }       
}

?>
