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
 * setup_view_index class of the template "index.tpl.php"
 *
 */
 
class setup_view_index
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function setup_view_index()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Execute the view of the template "index.tpl.php"
     * launch setup events of other modules
     *
     * @return bool true on success else false
     */
    function perform()
    {
        $this->B->conf_val = array();
        // Init error array
        $this->B->setup_error = array();

        // launch setup
        if( $_POST['do_setup'] )
        {
            // Send a setup message to the system handler
            $success = $this->B->M( MOD_SYSTEM,           'sys_setup' );
            
            // Send a setup message to the common handler
            if($success == TRUE)    
                $success = $this->B->M( MOD_COMMON,       'sys_setup' );
   
            // Send a setup message to the entry handler
            if($success == TRUE)    
                $success = $this->B->M( MOD_DEFAULT,      'sys_setup' );
    
            // Send a setup message to the test handler
            if($success == TRUE)
                $success = $this->B->M( MOD_TEST,         'sys_setup' );
    
            // Send a setup message to the option handler
            if($success == TRUE)
                $success = $this->B->M( MOD_OPTION,       'sys_setup' );
        
            // check on errors before proceed
            if( $success == TRUE )
            {   
                // write the system config file
                $this->B->conf_val['info']['status'] = TRUE;
        
                // include PEAR Config class
                include_once( SF_BASE_DIR . 'modules/common/PEAR/Config.php');

                $c = new Config();
                $root =& $c->parseConfig($this->B->conf_val, 'PHPArray');
                    
                // write config array
                $c->writeConfig(SF_BASE_DIR . 'modules/common/config/config.php', 'PHPArray', array('name' => 'this->B->sys'));
        
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
                exit;  
            }
        }
        
        return TRUE;
    }    
}

?>
