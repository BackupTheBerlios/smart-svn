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
 * view_node class
 *
 */
 
class view_node extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'node';

    /**
     * Execute the view of the template "templates_xxx/tpl.node.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // check if the visitor is a logged user
        if( isset( $this->B->logged_user ) )
        {
            // assign a template variable with the name of the logged user.
            $this->B->tpl_logged_user = $this->B->logged_user;
        }
        
        /* get_node Event call. See: modules/navigation/actions/class.action_navigation_get_node.php 
        It assign navigation node title template variables 
        $B->tpl_title and body $B->tpl_body. */          
         
        M( MOD_NAVIGATION, 
           'get_node', 
           array('node'             => $_REQUEST['node'],
                 'error'            => 'tpl_error',
                 'result'           => 'tpl_node',
                 'nstatus'          => 'publish',
                 'format'           => 'wikki' )); 

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
        // Launch default prepended filters (before the view template is rendered)
        // 
        
        // Directed intercepting filter event (auto_prepend)
        // see smart/actions/class.system_sys_prepend.php
        M( MOD_SYSTEM, 'sys_prepend' );    
    }    
    
    /**
     * prepend filter chain
     *
     */
    function appendFilterChain()
    {
        // Launch default appended filters (after the view template was rendered)
        // 
        
        // Directed intercepting filter event (auto_prepend)
        // see smart/actions/class.system_sys_prepend.php
        M( MOD_SYSTEM, 'sys_append' );    
    }     
}

?>