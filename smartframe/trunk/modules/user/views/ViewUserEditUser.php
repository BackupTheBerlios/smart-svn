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
 * ViewUserEditUser class
 *
 */
 
class ViewUserEditUser extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'edituser';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/user/templates/';

    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check permission to edit/update requested user data
        if(FALSE == $this->model->action('user',
                                         'allowEditUser',
                                         array('id_user' => $_REQUEST['id_user'] ) ))
        {
            throw new SmartViewException('Operation denied');
        }    
    }
    
    /**
     * Execute the view of the template "tpl.adduser.php"
     *
     * @return bool true on success else false
     */
    function perform()
    { 
        // init template user array
        $this->tplVar['user'] = array();
        
        // Init template form field values
        $this->tplVar['error']            = FALSE;
        $this->tplVar['user']['email']       = '';
        $this->tplVar['user']['login']       = '';
        $this->tplVar['user']['passwd']      = '';
        $this->tplVar['user']['name']        = '';
        $this->tplVar['user']['lastname']    = '';  
        $this->tplVar['user']['description'] = '';   
        $this->tplVar['user']['role']        = 0;  
        
        $this->tplVar['id_user'] = $_REQUEST['id_user']; 
    
        // add user on demande
        if( isset($_POST['updatethisuser']) )
        {
            if(FALSE == $this->checkAssignedPermission( (int)$_POST['role'] ))
            {
                $this->resetFormData();
                $this->tplVar['error'] = 'You have no rights to assign the such role to a new user!';
                $this->assignHtmlSelectBoxRole();
                return TRUE;
            }

            if($_POST['deleteuser'] == '1')
            {
                $this->model->action('user',
                                     'delete',
                                     array('id_user' => $_REQUEST['id_user']) ); 
                                     
                // reload the user module on success
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=user');
                exit;                                      
            }
            
            // check if required fields are empty
            if (FALSE == $this->checkEmptyFields())
            {
                // reset form fields on error
                $this->resetFormData();
                $this->tplVar['error'] = 'You have fill out at least the name, lastname and email fields!';
                $this->assignHtmlSelectBoxRole();
                return TRUE;
            }            

            // array with new user data
            $_data = array( 'error'     => & $this->tplVar['error'],
                            'id_user'   => $_REQUEST['id_user'],
                            'user' => array('email'    => SmartCommonUtil::stripSlashes($_POST['email']),
                                            'status'   => $_POST['status'],
                                            'role'     => (int)SmartCommonUtil::stripSlashes($_POST['role']),
                                            'name'     => SmartCommonUtil::stripSlashes($_POST['name']),
                                            'lastname' => SmartCommonUtil::stripSlashes($_POST['lastname']),
                                            'description' => SmartCommonUtil::stripSlashes($_POST['description']) ));

            if(!empty($_POST['passwd']))
            {
                $_data['user']['passwd'] = SmartCommonUtil::stripSlashes($_POST['passwd']);
            }
             
            // add new user data
            if(TRUE == $this->model->action( 'user',
                                             'update',
                                             $_data ))
            {
                // reload the user module on success
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER.'?mod=user');
                exit; 
            }
            else
            {
                // reset form fields on error
                $this->resetFormData();
                $this->assignHtmlSelectBoxRole();
                return TRUE;                
            }
        }

        if(FALSE == $this->model->action('user',
                                         'getUser',
                                         array('result'  => & $this->tplVar['user'],
                                               'id_user' => $_REQUEST['id_user'],
                                               'fields'  => array('login',
                                                                  'name',
                                                                  'lastname',
                                                                  'email',
                                                                  'status',
                                                                  'role',
                                                                  'description')) ))
        {
            throw new SmartViewException('id_user dosent exists');
        }
        
        // convert some field values to safely include it in template html form fields
        $this->convertHtmlSpecialChars( $this->tplVar['user'], array('name','lastname') );

        $this->assignHtmlSelectBoxRole();
        
        return TRUE;
    } 

    /**
     * Convert strings so that they can be safely included in html forms
     *
     * @param array $var_array Associative array
     * @param array $fields Field names
     */
    private function convertHtmlSpecialChars( &$var_array, $fields )
    {
        foreach($fields as $f)
        {
            $var_array[$f] = htmlspecialchars ( $var_array[$f],  ENT_NOQUOTES, $this->config['charset'] );
        }
    }

    /**
     * Assign template variable to build the html role select box
     */
    private function assignHtmlSelectBoxRole()
    {
        // build template variables for the user role html select menu
        $roles = array('10'  => 'Superuser',
                       '20'  => 'Administrator',
                       '40'  => 'Editor',
                       '60'  => 'Author',
                       '80'  => 'Contributor',
                       '100' => 'Webuser'); 
        
        $this->tplVar['form_roles'] = array();
        
        foreach($roles as $key => $val)
        {
            // just the roles on which the logged user have rights
            if(($this->viewVar['loggedUserRole'] < $key) && ($this->viewVar['loggedUserRole'] < 60))
            {
                $this->tplVar['form_roles'][$key] = $val;
            }
        }
    }

    /**
     * A logged user can only create new users with a role
     * value greater than the value of its own role.
     *
     * @param int $assignedRole Role of the new user
     */
    private function checkAssignedPermission( $assignedRole )
    {
        if($this->viewVar['loggedUserRole'] >= (int)$assignedRole)
        {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Check permission to execute this view
     * @return bool
     */
    private function checkViewPermission()
    {
        if($this->viewVar['loggedUserRole'] < 60)
        {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check if required fields are empty
     *
     * @return bool true on success else false
     * @access privat
     */       
    private function checkEmptyFields()
    {
        // check if some fields are empty
        if( empty($_POST['email']) || 
            empty($_POST['lastname']) || 
            empty($_POST['name']) )
        {        
            return FALSE;
        }  
        return TRUE;
    }  
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    private function resetFormData()
    {
        // if empty assign form field with old values
        $this->tplVar['user']['role']     = SmartCommonUtil::stripSlashes($_POST['role']);
        $this->tplVar['user']['email']    = SmartCommonUtil::stripSlashes($_POST['email']);
        $this->tplVar['user']['name']     = SmartCommonUtil::stripSlashes($_POST['name']);
        $this->tplVar['user']['lastname'] = SmartCommonUtil::stripSlashes($_POST['lastname']);
        $this->tplVar['user']['description'] = SmartCommonUtil::stripSlashes($_POST['description']);
        $this->tplVar['user']['login']    = SmartCommonUtil::stripSlashes($_POST['login']);
        $this->tplVar['user']['passwd']   = SmartCommonUtil::stripSlashes($_POST['passwd']);          
    }       
}

?>