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
 * view_user_editnode class of the template "tpl.user_editnode.php"
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
     * Execute the view of the template "tpl.user_editnode.php"
     * 
     * update or delete navigation node data
     *
     * @return bool
     */
    function perform()
    { 
        // update navigation node
        if( $_POST['modifynodedata'] == TRUE ) 
        {
            if( $_POST['delnode'] == '1' )
            {
                if ( FALSE == M( MOD_NAVIGATION, 
                                'delete_node', 
                                 array('node'  => $_REQUEST['node'],
                                       'error' => 'tpl_error')) )
                {
                    // if it fails reset form fields
                    $this->_reset_old_fields_data();                
                    return TRUE;
                }   
                // on success switch to the main navigation page
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=navigation');
                exit;
            }
            
            if ( TRUE == M( MOD_NAVIGATION, 
                            'update_node', 
                            array('node'   => $_REQUEST['node'],
                                  'title'  => $_POST['title'],
                                  'body'   => $_POST['body'],
                                  'status' => $_POST['status'],
                                  'error'  => 'tpl_error')) )
            {  
                // on success switch to the main navigation page
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=navigation');
                exit;
            }
            else
            {
                // if it fails reset form fields
                $this->_reset_old_fields_data();
                return TRUE;
            }
        }

        // get navigation node         
        M( MOD_NAVIGATION, 
           'get_node', 
           array('node'    => $_REQUEST['node'],
                 'title'   => 'tpl_title',
                 'body'    => 'tpl_body',
                 'status'  => 'tpl_status')); 

        return  TRUE;
    }    

    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        $this->B->tpl_title = str_replace ( "'", "&#039;",commonUtil::stripSlashes($_POST['title']));
        $this->B->tpl_body  = commonUtil::stripSlashes($_POST['body']);
    }     
}

?>