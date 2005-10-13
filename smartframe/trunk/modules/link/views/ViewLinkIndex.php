<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewLinkIndex
 *
 */
 
class ViewLinkIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public  $template = 'index';
    
     /**
     * Default template folder for this view
     * @var string $templateFolder
     */    
    public  $templateFolder = 'modules/link/templates/';
    
    /**
     * Perform on the index view
     */
    public function perform()
    {
        // set template var to show user options link
        // only on user main page and if the user role is at least an "editor"
        if(isset($_REQUEST['view']) && ($this->viewVar['loggedUserRole'] > 40))
        {
            $this->tplVar['show_admin_link'] = FALSE;
        }
        else
        {
            $this->tplVar['show_admin_link'] = TRUE;
        }
        return TRUE;
    }     
}

?>