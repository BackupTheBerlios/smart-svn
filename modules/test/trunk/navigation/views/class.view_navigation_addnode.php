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
 * view_navigation_addnode class of the template "tpl.addnode.php"
 *
 */
 
class view_navigation_addnode extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'navigation_addnode';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/navigation/templates/';
    
    /**
     * Execute the view of the template "tpl.addnode.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {    
        if ( isset($_POST['addnode']) )
        {
            if ( TRUE == M( MOD_NAVIGATION, 
                            'add_node', 
                            array('title'  => commonUtil::stripSlashes($_POST['title']),
                                  'body'   => commonUtil::stripSlashes($_POST['body']),
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
        return TRUE;
    } 
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        $this->B->tpl_title = htmlspecialchars(commonUtil::stripSlashes($_POST['title']));
        $this->B->tpl_body  = commonUtil::stripSlashes($_POST['body']);
    }       
}

?>