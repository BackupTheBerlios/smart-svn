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
 * NAVIGATION_GET class 
 *
 */
 
class NAVIGATION_GET
{
    /**
     * constructor
     *
     */
    function NAVIGATION_GET()
    {
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
            $_result = & $GLOBALS['B']->$data['var']; 
            
            // The navigation array
            $_result = array( 'Counter'  => 'counter',
                              'Contact'  => 'contact',
                              'Site Map' => 'sitemap'); 
    }    
}

?>
