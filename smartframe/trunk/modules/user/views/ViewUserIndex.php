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
 
class ViewUserIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/user/templates/';
    
    /**
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // set template var to show user options link
        // only on user main page and if the user role is at least an "editor"
        if(isset($_REQUEST['view']) || ($this->viewVar['loggedUserRole'] > 40))
        {
            $this->tplVar['show_options_link'] = FALSE;
        }
        else
        {
            $this->tplVar['show_options_link'] = TRUE;
        }
    }     
}

?>