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
 * SYSTEM_SYS_INIT class 
 *
 */
 
class SYSTEM_SYS_INIT
{
    /**
     * constructor php4
     *
     */
    function SYSTEM_SYS_INIT()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
    }
    
    /**
     * Only check if the version number has changed > include upgrade code
     *
     *
     * @param array $data
     */  
    function perform( $data )
    {
            include_once(SF_BASE_DIR.'/admin/include/system_version.php');
            // Check for upgrade  
            if($GLOBALS['B']->system_version != (string)$GLOBALS['B']->sys['info']['version'])
            {
                $GLOBALS['B']->sys['info']['name']    = $B->system_name;
                $GLOBALS['B']->sys['info']['version'] = $B->system_version;
                $GLOBALS['B']->system_update_flag = TRUE;  
                
                // include additional upgrade code here
            }   
            return TRUE;
    }
}

?>
