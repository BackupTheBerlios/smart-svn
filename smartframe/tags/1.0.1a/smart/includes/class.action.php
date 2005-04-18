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
 * default action. Parent class of every action class
 *
 */
 
class action
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;

    /**
     * Error array
     * @var array $errors
     */
    var $errors = array();
    
    /**
     * constructor php4
     *
     */
    function action( $data = FALSE )
    {
        $this->__construct( $data );
    }

    /**
     * constructor php5
     *
     */
    function __construct( &$data )
    {
        $this->B = & $GLOBALS['B'];
    }
 
    /**
     * validate the action request
     *
     */
    function validate( & $data )
    {
        return SF_IS_VALID_ACTION;
    }  
    
    /**
     * perform on the action request
     *
     */
    function perform( & $data )
    {
       return SF_IS_VALID_ACTION;
    }    
}

?>
