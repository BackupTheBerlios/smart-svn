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
 * view_contact class of the template "/xxx_contact.tpl.php"
 *
 */
 
class view_contact
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
    function view_contact()
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
     * Execute the view of the template "/xxx_contact.tpl.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
         /* Contact Event call. See: modules/test/actions/class.test_contact.php 
         It assign contact data to the template var $B->tpl_contact which
         is printed out in the "/xxx_contact.tpl.php" template. */          
         
         $this->B->M( MOD_TEST, 
                      'contact', 
                      array('var' => 'tpl_contact')); 

        
        return TRUE;
    }    
}

?>
