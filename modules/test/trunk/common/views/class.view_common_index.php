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
 * view_common_index class
 *
 */

class view_common_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'common_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/common/templates/';
    
    /**
     * Execute the view of the template "tpl.common_index.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // check permission to access the admin interface
        if( FALSE == $this->B->_is_logged )
        {
            // switch to the login view of the user module
            $_REQUEST['m']   = 'user';
            $_REQUEST['sec'] = 'login';    
        }
        else
        {
            // get modules names to show them in the html select menu
            M( MOD_SYSTEM, 
               'module_names', 
                array('var'             => 'tpl_mod_list',
                      'menu_visibility' => TRUE));    
        }         
        
        return TRUE;
    }  
    
    /**
     * authentication
     *
     */
    function auth()
    {
        // Directed authentication event to the module handler, 
        // which takes the authentication part
        // The variable SF_AUTH_MODULE must be declared in the "common"
        // module event_handler.php file
        M( SF_AUTH_MODULE, 'auth' );
    }
    
    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // Directed intercepting filter event (auto_prepend)
        // see smart/actions/class.system_sys_prepend.php
        M( MOD_SYSTEM, 'sys_prepend' );    
    }        
}

?>
