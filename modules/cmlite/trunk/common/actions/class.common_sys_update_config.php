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
 * common_sys_update_config class 
 *
 */
 
class common_sys_update_config
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function common_sys_finish_update()
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
     * Control the main setup process
     *
     * @param array $data
     */
    function perform( $data )
    {                 
        // include PEAR Config class
        include_once( SF_BASE_DIR . 'modules/common/PEAR/Config.php');

        $c = new Config();
        $root =& $c->parseConfig($this->B->sys, 'PHPArray');

        // save the modified config array
        $c->writeConfig(SF_BASE_DIR . 'modules/common/config/config.php', 'PHPArray', array('name' => 'this->B->sys'));  
    }    
}

?>
