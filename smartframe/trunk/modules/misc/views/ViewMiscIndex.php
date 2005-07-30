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
 * ViewMiscIndex class
 *
 */

class ViewMiscIndex extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'index';
    
     /**
     * Template folder for this view
     * @var string $templateFolder
     */    
    public $templateFolder = 'modules/misc/templates/';
    
    /**
     * Execute the view
     *
     */
    function perform()
    {
        // set template var to show user options link
        // only on user main page and if the user role is at least an "admin"
        if(isset($_REQUEST['view']) && ($this->viewVar['loggedUserRole'] > 20))
        {
            $this->tplVar['show_admin_link'] = FALSE;
        }
        else
        {
            $this->tplVar['show_admin_link'] = TRUE;
        }    
    }     
}

?>