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
 * SYSTEM_SYS_SETUP class 
 *
 */
 
class SYSTEM_SYS_SETUP
{
    /**
     * constructor php4
     *
     */
    function SYSTEM_SYS_SETUP()
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
     * Setup proceedure for the base system
     *
     *
     * @param array $data
     */  
    function perform( $data )
    {
            $success = TRUE;
            if(!is_writeable( SF_BASE_DIR . '/admin/logs' ))
            {
                trigger_error("Must be writeable: " . SF_BASE_DIR . "/admin/logs\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $GLOBALS['B']->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . '/admin/logs';
                $success = FALSE;
            }

            // the version to install
            include_once( SF_BASE_DIR . '/admin/include/system_version.php' );

            // set name and version of the framework
            $GLOBALS['B']->conf_val['info']['name']    = $B->system_name;
            $GLOBALS['B']->conf_val['info']['version'] = $B->system_version;
            return $success;
    }
}

?>
