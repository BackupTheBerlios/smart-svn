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
 
class view_navigation_editnode extends view
{
    /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'navigation_editnode';
    
    /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/navigation/templates/';
  
    /**
     * Execute the view of the template "login.tpl.php"
     * 
     * edit and update or delete user data
     *
     * @return bool
     */
    function perform()
    {                 
         /* Contact Event call. See: modules/test/actions/class.action_test_contact.php 
         It assign contact data to the template var $B->tpl_contact which
         is printed out in the "templates_default/tpl.contact.php" template. */          
         
         M( MOD_NAVIGATION, 
            'get_node', 
            array('node'             => $_REQUEST['node'],
                  'title'            => 'tpl_title',
                  'body'             => 'tpl_body',
                  'nl2br'            => FALSE,
                  'htmlspecialchars' => TRUE)); 
            
        return  TRUE;
    }    

    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        $this->B->tpl_data['email']    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
        $this->B->tpl_data['login']    = htmlspecialchars(commonUtil::stripSlashes($_POST['user']));
        $this->B->tpl_data['passwd']   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));
    }     
}

?>
