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
     * Create template variables
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
        
        // It assign navigation node template array $B->tpl_node
        // with data of the node defined in $_GET['node'] with status 2=public
        // the text body is formated by PEAR's text_wikki
        M( MOD_NAVIGATION, 
           'get_node', 
           array('node'   => (int)$_GET['node'],
                 'error'  => 'tpl_error',
                 'result' => 'tpl_node',
                 'status' => 2,
                 'format' => 'wikki' )); 

        // assign the template array $B->tpl_branch with navigation branch nodes
        // of the node defined in $_GET['node']
        M( MOD_NAVIGATION, 
           'get_branch', 
           array('result' => 'tpl_branch',
                 'error'  => 'tpl_branch_error',
                 'node'   => (int)$_GET['node']));            

        // assign the template array $B->tpl_child_nodes with navigation child nodes
        // of node defined in $_GET['node']
        // which has the status 2=public
        M( MOD_NAVIGATION, 
           'get_childs', 
           array('result' => 'tpl_child_nodes',
                 'node'   => (int)$_GET['node'],
                 'error'  => 'tpl_childs_error',
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