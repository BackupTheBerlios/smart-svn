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
 * view_index class of the template "/xxx_index.tpl.php"
 *
 */
 
class view_index
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
    function view_index()
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
     * Execute the view of the template "/xxx_index.tpl.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
        /* A simple event call directed to the test module. It is a trivial example, but
         it show you how the following event call interact with the corresponding
         action class "class.test_print.php" of the test module.
         See: modules/test/actions/class.test_print.php
         
         A welcome string is passed to the perform($data) function of this class
         and this function assign the welcome string to the template 
         var $B->tpl_welcome_string. In template 'xxx_index.tpl.php' var is printed out.
          */
        $this->B->M( MOD_TEST, 
                     'print', 
                     array('var'    => 'tpl_welcome_string',
                           'string' => 'Welcome to the SMART Framework test page!')); 
        
        return TRUE;
    }    
}

?>
