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
 * EARCHIVE_GET_OPTIONS class 
 *
 */
 
class EARCHIVE_GET_OPTIONS
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
    function EARCHIVE_GET_OPTIONS()
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
     * Define the options template for this module
     * This is a subtemplate of the options module main template
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // get earchive options template 
        // to include in the option module
        $this->B->mod_option[] = SF_BASE_DIR.'/admin/modules/earchive/templates/option.tpl.php';
    } 
}

?>
