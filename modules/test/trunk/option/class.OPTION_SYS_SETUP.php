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
 * OPTION_SYS_SETUP class 
 *
 */
 
class OPTION_SYS_SETUP
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $this->B;
    
    /**
     * constructor
     *
     */
    function OPTION_SYS_SETUP()
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
     * If a logout request was done
     *
     * @param array $data
     */
    function perform( $data )
    {            
        $success = TRUE;
        // The module name and version
        $this->B->conf_val['module']['option']['name']     = 'option';
        $this->B->conf_val['module']['option']['version']  = MOD_OPTION_VERSION;
        $this->B->conf_val['module']['option']['mod_type'] = 'test';
        $this->B->conf_val['module']['option']['info']     = '';
        // Set some options
        $this->B->conf_val['option']['tpl'] = SF_DEFAULT_TEMPLATE_GROUP;
        $this->B->conf_val['option']['url'] = SF_BASE_LOCATION;
        $this->B->conf_val['option']['site_title'] = 'Site title';
        $this->B->conf_val['option']['site_desc']  = 'My first site';
        $this->B->conf_val['option']['email']      = 'admin@foo.com';
        $this->B->conf_val['option']['charset']    = 'iso-8859-1';
        
        return $success;
    }    
}

?>
