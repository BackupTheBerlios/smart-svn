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
 * user_view_edituser class of the template "login.tpl.php"
 *
 */
 
class user_view_edituser
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
    function user_view_edituser()
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
     * Execute the view of the template "login.tpl.php"
     * 
     * edit and update or delete user data
     *
     * @return bool
     */
    function perform()
    {    
        if(!is_object($this->B->user))
        {
            // the user class
            include_once SF_BASE_DIR . 'modules/user/includes/class.user.php';

            //User Class instance
            $this->B->user = & new user;  
        }
        
        // check permission to modify user data
        $this->B->F( USER_FILTER,
                     'permission',
                     array( 'action'  => 'modify',
                            'user_id' => (int)$_REQUEST['uid']));
        
        // if some user data has change
        if( isset($_POST['modifyuserdata']) )
        {
            $this->_modify_user();
        }     
    
        // assign template array with user data
        $tmp_fields = array('uid','rights','status','email','login','forename','lastname');
        $this->B->tpl_data = $this->B->user->get_user( (int)$_REQUEST['uid'], $tmp_fields );     
        
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
        if (FALSE == $this->_check_empty_fields())
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
            $_is_logged_user = $this->B->F( USER_FILTER,
                                            'permission',
                                             array( 'action'  => 'is_logged_user',
                                                    'user_id' => (int) $_POST['uid']));  
            if(TRUE == $_is_logged_user)
            {
                $this->B->form_error   = 'You can remove your own user account!';
                return FALSE;
            }
            else
            {
                $this->B->user->delete_user( (int) $_POST['uid'] );
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
                exit;          
            }
        }
        return TRUE;
    }

    /**
     * check if required fields are empty
     *
     * @return bool true on success else false
     * @access privat
     */       
    function _check_empty_fields()
    {
        // check if some fields are empty
        if(
            empty($_POST['forename'])||
            empty($_POST['lastname'])||
            empty($_POST['email']))
        {        
            $this->B->form_error = 'You have fill out all fields!';
            return FALSE;
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
            $_is_logged_user = $this->B->F( USER_FILTER,
                                            'permission',
                                            array( 'action'  => 'is_logged_user',
                                                   'user_id' => (int) $_POST['uid']));  
            if(TRUE == $_is_logged_user)
            {
                $this->B->form_error   = 'You can not change your own rights or status!';
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
            $_success = $this->B->F( USER_FILTER,
                                     'permission',
                                     array( 'action'        => 'set_rights',
                                            'user_id'       => (int) $_POST['uid'],
                                            'right'         => (int)$_POST['rights'])); 
                                               
            if(FALSE == $_success)
            {
                $this->B->form_error = 'You can not change to this rights level!'; 
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
            $_success = $this->B->F( USER_FILTER,
                                     'permission',
                                     array( 'action'        => 'set_status',
                                            'user_id'       => (int) $_POST['uid'])); 
           
           if(FALSE == $_success)
           {
              $this->B->form_error = 'You can not change status of this user!';
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
        $tmp_data = array(
                            'forename' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['forename'])),
                            'lastname' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['lastname'])),
                            'email'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                            'rights'   => (int)$_POST['rights'],
                            'status'   => (int)$_POST['status']);
            
        // update password if it isnt empty
        if(!empty($_POST['passwd']))
        {
            $tmp_data['passwd'] == $this->B->db->quoteSmart(md5($_POST['passwd']));
        }
            
        // update user data
        if(FALSE != $this->B->user->update_user( (int)$_REQUEST['uid'], $tmp_data))
        {
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
            exit;
        }
        else
        {
            $this->B->form_error = 'This login exist. Chose a other one!';
            return FALSE;
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
        $this->B->tpl_data['forename'] = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
        $this->B->tpl_data['lastname'] = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
        $this->B->tpl_data['email']    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
        $this->B->tpl_data['login']    = htmlspecialchars(commonUtil::stripSlashes($_POST['login']));
        $this->B->tpl_data['passwd']   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));
        $this->B->tpl_data['rights']   = $_POST['rights'];
        $this->B->tpl_data['status']   = $_POST['status'];            
    }
}

?>