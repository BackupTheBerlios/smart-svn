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
 * view_index class
 *
 */
 
class view_index extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'index';
        
    /**
     * Execute the view of the template "templates_default/tpl.index.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
        /* A simple event call directed to the test module. It is a trivial example, but
         it show you how the following event call interact with the corresponding
         action class "class.action_test_print.php" of the test module.
         See: modules/test/actions/class.action_test_print.php
         
         A welcome string is passed to the perform($data) function of this class
         and this function assign the welcome string to the template 
         var $B->tpl_welcome_string. In template 'templates_default/tpl.index.php' var is printed out.
          */
        M( MOD_TEST, 
           'print', 
           array('var'    => 'tpl_welcome_string',
                 'string' => 'Welcome to the SMART Framework test page!')); 
        
        return TRUE;
    }    
}

?>
