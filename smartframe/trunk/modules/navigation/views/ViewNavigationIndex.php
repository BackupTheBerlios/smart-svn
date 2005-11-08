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
 * ViewNavigationIndex
 *
 */
 
class ViewNavigationIndex extends SmartView
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
    public  $templateFolder = 'modules/navigation/templates/';
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // all accounts can access the map view
        if( !isset($_REQUEST['view']) || ($_REQUEST['view'] != "nodemap") )
        {
            // only administrators can access keyword module
            if($this->viewVar['loggedUserRole'] > $this->model->config['module']['navigation']['perm'])
            {
                // reload admin
                @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER);
                exit;  
            }
        }
    }     
}

?>