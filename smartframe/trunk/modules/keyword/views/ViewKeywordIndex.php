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
 * ViewKeywordIndex
 *
 */
 
class ViewKeywordIndex extends SmartView
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
    public  $templateFolder = 'modules/keyword/templates/';
    
    /**
     * Perform on the index view
     */
    public function perform()
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
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // all accounts can access the map view
        if( $_REQUEST['view'] != "map" )
        {
            // only administrators can access keyword module
            if($this->viewVar['loggedUserRole'] > $this->model->config['module']['keyword']['perm'])
            {
                // reload admin
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER);
                exit;  
            }
        }
    }     
}

?>