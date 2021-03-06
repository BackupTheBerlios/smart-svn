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
 * NAVIGATION_SYS_INIT class 
 *
 */
 
class NAVIGATION_SYS_INIT
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
    function NAVIGATION_SYS_INIT()
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
        if(MOD_NAVIGATION_VERSION != (string)$this->B->sys['module']['navigation']['version'])
        {        
            // set the new version num of this module
            $this->B->sys['module']['navigation']['version']  = MOD_NAVIGATION_VERSION;
            $this->B->system_update_flag = TRUE;                    
        }
    }    
}

?>
