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
 * NAVIGATION_SYS_SETUP class 
 *
 */
 
class NAVIGATION_SYS_SETUP
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
    function NAVIGATION_SYS_SETUP()
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
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
  
        //create captcha_pics dir if it dosent exist
        if(!is_writeable( SF_BASE_DIR . '/admin/modules/navigation/attach' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/modules/navigation/attach';
            $success = FALSE;
        }  
    
        if($success == TRUE)
        {
            // create db tables
            if(file_exists(SF_BASE_DIR . '/admin/modules/navigation/_setup_'.$_POST['dbtype'].'.php'))
            {
                // include mysql setup
                include_once( SF_BASE_DIR . '/admin/modules/navigation/_setup_'.$_POST['dbtype'].'.php' );    
            }
            else
            {
                $this->B->setup_error[] = 'NAVIGATION module: This db type isnt supported: ' . $_POST['dbtype'];
                $success = FALSE;
            }
        }
    
        $this->B->conf_val['module']['user']['name']     = 'navigation';
        $this->B->conf_val['module']['user']['version']  = MOD_NAVIGATION_VERSION;
        $this->B->conf_val['module']['user']['mod_type'] = 'lite';
        $this->B->conf_val['module']['user']['info']     = 'Navigation module. Author: Armand Turpel <smart AT open-publisher.net>';  

        return $success;
    }
}

?>
