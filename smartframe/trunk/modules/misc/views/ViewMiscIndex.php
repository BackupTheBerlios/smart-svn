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
    } 
    
    /**
     * prepend filter chain
     *
     */
    public function prependFilterChain()
    {
        // only administrators can access misc module
        if($this->viewVar['loggedUserRole'] > $this->model->config['module']['misc']['perm'])
        {
            // reload admin
            @header('Location: '.$this->model->baseUrlLocation.'/'.SMART_CONTROLLER);
            exit;  
        }
    }         
}

?>