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
 * view_contact class
 *
 */
 
class view_contact extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'contact';

    /**
     * Execute the view of the template "templates_default/tpl.contact.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
         /* Contact Event call. See: modules/test/actions/class.action_test_contact.php 
         It assign contact data to the template var $B->tpl_contact which
         is printed out in the "templates_default/tpl.contact.php" template. */          
         
         M( MOD_TEST, 
            'contact', 
            array('var' => 'tpl_contact')); 
 
        return TRUE;
    }    
}

?>
