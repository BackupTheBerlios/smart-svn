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
 * DEFAULT_SYS_INIT class 
 *
 */
 
class DEFAULT_SYS_INIT
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
    function DEFAULT_SYS_INIT()
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
     * Check if version number has changed and perfom additional upgarde code
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Check for upgrade  
        if(MOD_DEFAULT_VERSION != (string)$B->sys['module']['default']['version'])
        {        
            // The module name and version
            $this->B->sys['module']['default']['name']     = 'default';
            $this->B->sys['module']['default']['version']  = MOD_DEFAULT_VERSION;
            $this->B->sys['module']['default']['mod_type'] = 'test';
            $this->B->sys['module']['default']['info']     = 'This is the default module';
            $this->B->system_update_flag = TRUE; 
        }
    }    
}

?>
