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
 * option_sys_init class 
 *
 */
 
class option_sys_init
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
    function option_sys_init()
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
            if(MOD_OPTION_VERSION != (string)$this->B->sys['module']['option']['version'])
            {
                // set the new version num of this module
                $this->B->sys['module']['option']['version'] = MOD_OPTION_VERSION;
                $this->B->system_update_flag = TRUE;  
                
                // include here additional upgrade code
            }
    }    
}

?>
