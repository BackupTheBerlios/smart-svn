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
    function action()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * perform
     *
     */
    function perform()
    {
    }  

    /**
     * get errors as string
     *
     */
    function & getError()
    {   
        $error_str = "";
        foreach ($this->errors as $key => $val)
        { 
            $error_str .= $key . "\n" . $val . "\n\n";  
        }
        
        return $error_str;
    }    
}

?>
