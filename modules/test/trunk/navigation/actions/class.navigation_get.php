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
 * navigation_get class 
 *
 */
 
class navigation_get
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function navigation_get()
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
     * Fill up an array with navigation elements
     *
     * Structure of the $data array:
     * $data['var']           - array name where to store navigation array
     *
     * @param array $data
     */
    function perform( $data )
    {
            // get var name defined in the public template to store the result
            $_result = & $this->B->$data['var']; 
            
            // The navigation array
            $_result = array( 'Counter'  => 'counter',
                              'Contact'  => 'contact',
                              'Site Map' => 'sitemap'); 
    }    
}

?>
