<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Smart template container class
 *
 *
 */
 
class SmartTplContainer extends SmartContainer
{
    /**
     * Template variables
     *
     * @var mixed $vars
     */
    public $vars;
    
    /**
     * View loader object
     *
     * @var object $viewLoader
     */
    public $viewLoader;    
    
    /**
     * render the template
     *
     */
    function renderTemplate()
    {
        // get reference of the template variables
        $tpl = & $this->vars;
        // get reference of the view loader methode to include
        // nested views in templates
        $viewLoader = & $this->viewLoader;
        
        // build the whole file path to the TEMPLATE file
        $template = SMART_BASE_DIR . $this->templateFolder . '/tpl.' . $this->template . '.php';
        if ( !@file_exists( $template ) )
        {
            throw new SmartTplException("Template dosent exists: ".$template, SMART_NO_TEMPLATE);
        }

        $this->tplBufferContent = '';
        ob_start();

        include( $template );

        $this->tplBufferContent = ob_get_clean();
    } 
}

?>