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
 * COMMON_SYS_SETUP class 
 *
 */
 
class COMMON_SYS_SETUP
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
    function COMMON_SYS_SETUP()
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
        // init the success var
        $success = TRUE;
            
        // include here all stuff to get work this module:
        // creating db tables

        // The module name and version
        // these array vars were saved later by the setup handler
        // in the file /admin/config/config_system.xml.php
        //
        $this->B->conf_val['module']['common']['name']     = 'common';
        $this->B->conf_val['module']['common']['version']  = MOD_COMMON_VERSION;
        $this->B->conf_val['module']['common']['mod_type'] = 'common';
        $this->B->conf_val['module']['common']['info']     = 'This is the common modul';

        if(($success == TRUE) && !is_writeable( SF_BASE_DIR . '/admin/modules/common/tmp/session_data' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/modules/common/tmp/session_data';
            $success = FALSE;
        }            

        if(!is_writeable( SF_BASE_DIR . '/admin/modules/common/config' ))
        {
            trigger_error("Must be writeable: " . SF_BASE_DIR . "/admin/config\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/modules/common/config';
            $success = FALSE;
        }
            
        // if noting is going wrong $success is still TRUE else FALSE
        // ex.: if creating db tables fails you must set this var to false
        return $success;     
    }    
}

?>
