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
 * SETUP_SYS_SETUP class 
 *
 */
 
class SETUP_SYS_SETUP
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function SETUP_SYS_SETUP()
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
     * Control the main setup process
     *
     * @param array $data
     */
    function perform( $data )
    {            
        $this->B->conf_val = array();
        // Init error array
        $this->B->setup_error = array();

        // launch setup
        if( $_POST['do_setup'] )
        {
            // Send a setup message to the system handler
            $success = $this->B->M( MOD_SYSTEM,           'SYS_SETUP' );
            
            // Send a setup message to the common handler
            if($success == TRUE)    
                $success = $this->B->M( MOD_COMMON,       'SYS_SETUP' );
   
            // Send a setup message to the entry handler
            if($success == TRUE)    
                $success = $this->B->M( MOD_DEFAULT,      'SYS_SETUP' );
    
            // Send a setup message to the test handler
            if($success == TRUE)
                $success = $this->B->M( MOD_TEST,         'SYS_SETUP' );
    
            // Send a setup message to the option handler
            if($success == TRUE)
                $success = $this->B->M( MOD_OPTION,       'SYS_SETUP' );
        
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
        
                @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
                exit;  
            }
        }

        // Include the setup template
        include(  SF_BASE_DIR . 'modules/setup/templates/index.tpl.php' ); 

        // Send the output buffer to the client
        ob_end_flush();

        // Basta
        exit;
    }    
}

?>
