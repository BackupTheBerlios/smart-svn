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
 * view_navigation class
 *
 */
 
class view_navigation extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'navigation';

    /**
     * Execute the view of the template "templates_default/tpl.contact.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
         /* Event to get the navigation menu entries from the navigation module action class. 
         See: modules/navigation/actions/class.action_navigation_get.php 
         The result is in the array $B->tpl_nav which is included as the site navigation menu. */
         M( MOD_NAVIGATION, 
            'get', 
            array('var' => 'tpl_nav'));
        
        return TRUE;
    }    
}

?>