<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * action_system_sys_setup class 
 *
 */
 
class action_system_sys_setup extends action
{
    /**
     * Setup proceedure for the base system
     *
     *
     * @param array $data
     */  
    function perform( $data )
    {
        $success = TRUE;
        if(!is_writeable( SF_BASE_DIR . 'logs' ))
        {
            trigger_error("Must be writeable: " . SF_BASE_DIR . "logs\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'logs';
            $success = FALSE;
        }

        // the version to install
        include_once( SF_BASE_DIR . 'smart/includes/system_version.php' );

        // set name and version of the framework
        $this->B->conf_val['info']['name']    = $this->B->system_name;
        $this->B->conf_val['info']['version'] = $this->B->system_version;
        
        // create a unique hash id
        mt_srand((double) microtime()*1000000);
        $this->B->conf_val['system']['hashid'] = md5(str_replace(".","",$_SERVER["REMOTE_ADDR"]) + mt_rand(100000,999999)+uniqid(microtime()));            
        
        return $success;
    }
}

?>
