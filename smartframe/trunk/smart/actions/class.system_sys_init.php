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
 * system_sys_init class 
 *
 */
 
class system_sys_init
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor php4
     *
     */
    function system_sys_init()
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
     * Only check if the version number has changed > include upgrade code
     *
     *
     * @param array $data
     */  
    function perform( $data )
    {   
            include_once(SF_BASE_DIR.'smart/includes/system_version.php');           
            // Check for upgrade  
            if($this->B->system_version != (string)$this->B->sys['info']['version'])
            {
                $this->B->sys['info']['name']    = $this->B->system_name;
                $this->B->sys['info']['version'] = $this->B->system_version;
                $this->B->system_update_flag = TRUE;  
                
                // include additional upgrade code here
            }   
            return TRUE;
    }
}

?>
