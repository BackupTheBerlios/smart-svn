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
 * view_setup_index class of the template "index.tpl.php"
 *
 */
 
class view_setup_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'setup_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/setup/templates/';
     
    /**
     * Execute the view of the template "index.tpl.php"
     * launch setup events of other modules
     *
     * @return bool true on success else false
     */
    function perform( $data = FALSE )
    {
        $this->B->conf_val = array();
        // Init error array
        $this->B->setup_error = array();

        // launch setup
        if( $_POST['do_setup'] )
        {
            // Send a setup message to the system handler
            $success = M( MOD_SYSTEM,           'sys_setup' );
            
            // Send a setup message to the common handler
            if($success == TRUE)    
                $success = M( MOD_COMMON,       'sys_setup' );
   
            // Send a setup message to the entry handler
            if($success == TRUE)    
                $success = M( MOD_DEFAULT,      'sys_setup' );
    
            // Send a setup message to the test handler
            if($success == TRUE)
                $success = M( MOD_NAVIGATION,   'sys_setup' );
    
            // Send a setup message to the option handler
            if($success == TRUE)
                $success = M( MOD_OPTION,       'sys_setup' );
                
            // Send a setup message to the option handler
            if($success == TRUE)
                $success = M( MOD_USER,         'sys_setup' );                

            // check on errors before proceed
            if( $success == TRUE )
            {   
                // write the system config file
                $this->B->conf_val['info']['status'] = TRUE;
        
                // update config file with the data array $this->B->conf_val        
                // see modules/SF_BASE_MODULE/actions/class.action_SF_BASE_MODULE_sys_update_config.php
                M( SF_BASE_MODULE, 
                    'sys_update_config', 
                     array( 'data'      => $this->B->conf_val,
                            'var_name' => 'this->B->sys',
                            'type'     => 'PHPArray') );     
                             
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1');
                exit;  
            }
        }
        
        return TRUE;
    }  
    
    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // check if setup was done. If so, we dont need to launch
        // the setup process once again.
        if( $this->B->sys['info']['status'] )
        {
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1');
            exit;          
        }
    }    
}

?>