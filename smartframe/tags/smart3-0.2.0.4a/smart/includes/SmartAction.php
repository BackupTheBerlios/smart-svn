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
 * Parent action from which all child actions extends
 *
 */

class SmartAction extends SmartObject
{
    /**
     * Data passed to the Constructor
     * @var mixed $constructorData
     */
    public $constructorData;
    
    /**
     * Smart Model
     * @var object $model
     */
    public $model;    

     /**
     * Smart main configuration array
     * @var array $config
     */
    public $config;   

    /**
     * constructor
     *
     * @param mixed $data Data passed to the constructor
     */
    function __construct( $data = FALSE )
    {
        $this->constructorData = & $data;
    }

    /**
     * validate the action request
     *
     * @param mixed $data
     * @return bool
     */
    public function validate( $data = FALSE )
    {
        return TRUE;
    }

    /**
     * perform on the action request
     *
     * @param mixed $data
     * @return bool
     */
    public function perform( $data = FALSE )
    {
       return TRUE;
    }
}

?>