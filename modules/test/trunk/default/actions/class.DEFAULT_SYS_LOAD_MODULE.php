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
 * DEFAULT_SYS_LOAD_MODULE class 
 *
 */
 
class DEFAULT_SYS_LOAD_MODULE
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
    function DEFAULT_SYS_LOAD_MODULE()
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
     * Load the default module
     *
     * @param array $data
     */
    function perform( $data )
    {
        // set the base template for this module   
        $this->B->module = SF_BASE_DIR . '/admin/modules/default/templates/index.tpl.php';    
            
        return TRUE;
    }    
}

?>
