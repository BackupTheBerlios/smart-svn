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
 * default_sys_setup class 
 *
 */
 
class default_sys_setup
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
    function default_sys_setup()
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
        // The module name and version
        $this->B->conf_val['module']['default']['name']     = 'default';
        $this->B->conf_val['module']['default']['version']  = MOD_DEFAULT_VERSION;
        $this->B->conf_val['module']['default']['mod_type'] = 'test';
        $this->B->conf_val['module']['default']['info']     = 'This is the default module';
        
        return $success;
    }    
}

?>
