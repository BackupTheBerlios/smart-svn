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
 * view_list class of the template "tpl.list.php"
 *
 */
 
class view_list extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'list';
    
    /**
     * Execute the view of the template "tpl.list.php"
     *
     * @return bool false on error else true
     */
    function perform()
    {
        /* check if registered user is required to access this list */
        M( MOD_EARCHIVE, 
           'have_access', 
           array( 'lid' => (int)$_GET['lid'])); 

        // Prepare variables for the html view -> flat/tree
        $this->B->tpl_select_tree = '';
        $this->B->tpl_select_flat = '';
        if($_REQUEST['mode'] == 'tree')
        {
            $this->B->tpl_mode = 'tree';
            $this->B->tpl_select_tree = 'selected="selected"';
        }
        else
        {
            $this->B->tpl_mode = 'flat'; 
            $this->B->tpl_select_flat = 'selected="selected"';
        }
        
        /* get the messages of the requested email list and store the result in the array $B->tpl_msg 
           assign template vars with message data */
        M( MOD_EARCHIVE, 
           'get_messages', 
           array( 'lid'    => (int)$_GET['lid'], 
                  'var'    => 'tpl_msg',
                  'mode'   => $this->B->tpl_mode,
                  'fields' => array('mid','lid','subject','sender','mdate'),
                  'order'  => 'mdate DESC',
                  'pager'  => array( 'var'   => 'tpl_prevnext', 
                                     'limit' => 15, 
                                     'delta' => 3)));
 
        
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
