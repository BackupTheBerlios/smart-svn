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
 * Parent factory view class
 *
 */
 
class SmartViewFactory extends SmartObject
{
    /**
     * Model object
     */
    protected $model;
    
    /**
     * Main Smart Config Array
     */
    protected $config;    
    
    public function __construct( $model, & $config )
    {
        $this->model  = $model;
        $this->config = $config;
    }
}

?>
