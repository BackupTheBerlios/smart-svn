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
 * view_message class of the template "tpl.message.php"
 *
 */
 
class view_message extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'message';
 
    /**
     * Execute the view of the template "tpl.message.php"
     *
     * @return bool false on error else true
     */
    function perform()
    {       
        /* get the requested message and store the result in the array $B->tpl_msg 
         assign template vars with message data */
        M( MOD_EARCHIVE, 
           'get_message', 
           array( 'mid'    => (int)$_GET['mid'], 
                  'var'    => 'tpl_msg',
                  'fields' => array('subject','sender','mdate','body','folder')));
        
        /* get the message attachments and store the result in the array $B->tpl_attach */
        M( MOD_EARCHIVE, 
           'get_attachments', 
           array( 'var'    => 'tpl_attach', 
                  'mid'    => (int)$_GET['mid'],  
                  'fields' => array('aid', 'mid', 'lid', 'file', 'size', 'type')));

        return TRUE;
    }  
    
    /**
     * default authentication
     *
     */
    function auth()
    {
        // Directed authentication event to the module handler, 
        // which takes the authentication part
        // The variable SF_AUTH_MODULE must be declared in the "common"
        // module event_handler.php file
        M( SF_AUTH_MODULE, 'sys_authenticate' );
    }
    
    /**
     * default prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // Directed intercepting filter event (auto_prepend)
        // see smart/actions/class.system_sys_prepend.php
        M( MOD_SYSTEM, 'sys_prepend' );    
    }   
    
    /**
     * default append filter chain
     *
     */
    function appendFilterChain()
    {
        // Directed intercepting filter event (auto_append)
        // see smart/actions/class.system_sys_append.php
        M( MOD_SYSTEM, 'sys_append' );   
    }       
}

?>
