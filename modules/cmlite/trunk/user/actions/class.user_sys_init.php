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
 * user_sys_init class 
 *
 */
 
class user_sys_init
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
    function user_sys_init()
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
        if(MOD_USER_VERSION != (string)$this->B->sys['module']['user']['version'])
        {
            $this->B->M(MOD_USER, 'upgrade');
        
            // set the new version num of this module
            $this->B->sys['module']['user']['version'] = MOD_USER_VERSION;
            $this->B->system_update_flag = TRUE;  
        }       
    }    
}

?>
