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
 * view_user_index class of the template "tpl.user_index.php"
 *
 */
 
class view_user_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'user_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/user/templates/';
    
    /**
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        if(!isset($_REQUEST['sec']) || empty($_REQUEST['sec'])) 
        {
            $_REQUEST['sec'] = 'default';
        }
        return TRUE;
    }     
}

?>