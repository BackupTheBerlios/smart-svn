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
 * view_nodetree class
 *
 */
 
class view_nodetree extends view
{
     /**
     * Default template
     * @var string $template
     */
    var $template = 'nodetree';

    /**
     * Execute the view of the template "templates_xxx/tpl.node.php"
     * Create the template variables
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

        // get navigation node sitemap         
        M( MOD_NAVIGATION, 
           'get_tree', 
           array('result' => 'tpl_tree',
                 'status' => 2)); 

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
        
        // send http headers to prevent browser caching
        M( MOD_COMMON, 'add_headers' );    
    }    
    
    /**
     * append filter chain
     *
     */
    function appendFilterChain()
    {
        // Launch default appended filters (after the view template was rendered)
        // 
        
        // does email obfuscating
        M( MOD_COMMON, 'email_obfuscating' );     
    }   
}

?>