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
        // if some user data has change
        if( isset($_POST['modifyuserdata']) )
        {
            if(FALSE == $this->_modify_user())
            {
                return TRUE;
            }
        }     
    
        // prepare data array
        $_data = array( 'user' => urldecode($_REQUEST['user']),
                        'result'  => 'tpl_data');
                        
        // get user data
        if(SF_NO_VALID_ACTION == M( MOD_USER,
                                    'get',
                                    $_data ))
        {
            // if this user dosent exists reload the user module
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1&m=user');
            exit;         
        }
         
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
            // check if some one try to remove his own account. 
            // This isnt possible
            if($this->B->logged_user == $_POST['user'])
            {
                $this->B->tpl_error   = 'You cant remove your own user account!';
                return FALSE;
            }
            else
            {
                // Delete user
                if(SF_IS_VALID_ACTION == M( MOD_USER,
                                            'delete',
                                            array( 'error' => 'tpl_error',
                                                   'user'  => urldecode($_POST['user']))))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1&m=user');
                    exit;   
                }
            }
        }
        return TRUE;
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
        $_data = array( 'error' => 'tpl_error',
                        'user'  => urldecode($_REQUEST['user']),
                        'email' => $_POST['email']);
            
        // update password if it isnt empty
        if(!empty($_POST['passwd']))
        {
            $_data['passwd'] = $_POST['passwd'];
        }
            
        // update user data
        if(SF_IS_VALID_ACTION == M( MOD_USER,
                                    'update',
                                    $_data))
        {
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1&m=user');
            exit;
        }   
        return TRUE;
    }

    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        $this->B->tpl_data['email']    = commonUtil::stripSlashes($_POST['email']);
        $this->B->tpl_data['login']    = commonUtil::stripSlashes($_POST['user']);
        $this->B->tpl_data['passwd']   = commonUtil::stripSlashes($_POST['passwd']);
    }     
}

?>