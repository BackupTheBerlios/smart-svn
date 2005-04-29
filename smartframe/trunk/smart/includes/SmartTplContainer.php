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
    public $vars = NULL;
    
    /**
     * View loader object
     *
     * @var object $viewLoader
     */
    public $viewLoader;    

    /**
     * Template buffer content
     *
     * @var string tplBufferContent
     */
    public $tplBufferContent = '';    
    
    /**
     * render the template
     *
     */
    function renderTemplate()
    {
    } 
}

?>