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
 * action_common_sys_update_config class 
 *
 */
 
class action_common_sys_update_config extends action
{ 
    /**
     * Control the main setup process
     *
     * @param array $data
     * $data['data'] Data (array or ...) to store
     * $data['file'] File to store
     * $data['var_name'] Name of the data array or ...
     * $data['type'] Data type
     */
    function perform( $data = FALSE )
    {                 
        // include PEAR Config class
        include_once( SF_BASE_DIR . 'modules/common/PEAR/Config.php');

        $c = new Config();
        $root =& $c->parseConfig( $data['data'], $data['type'] );

        // save the modified config array
        $c->writeConfig( SF_BASE_DIR . 'data/'.SF_BASE_MODULE.'/config/config.php', $data['type'], array('name' => $data['var_name']) );  
    }    
}

?>