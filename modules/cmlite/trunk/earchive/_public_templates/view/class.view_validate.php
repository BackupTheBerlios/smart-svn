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
 * view_validate class of the template "tpl.validate.php"
 *
 */
 
class view_validate extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'validate';
    
    /**
     * Execute the view of the template "tpl.validate.php"
     *
     * @return bool false on error else true
     */
    function perform()
    {
        $this->B->tpl_is_valid = M( MOD_USER, 
                                    'validate',
                                    array('md5_str' => $_GET['usr_id']));
                                              
        if(TRUE === $this->B->tpl_is_valid)
        {
            $this->B->tpl_validation_message = 'Your account is now active.';
        }
        else
        {
            $this->B->tpl_validation_message = 'Account activation fails!!!';
        }
        
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
