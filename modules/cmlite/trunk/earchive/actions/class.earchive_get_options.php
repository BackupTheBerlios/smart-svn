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
 * earchive_get_options class 
 *
 */
 
class earchive_get_options
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
    function earchive_get_options()
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
     * Assign an array with the whole earchive options template path string
     * This is a subtemplate of the options module main template
     *
     * @param array $data
     */
    function perform( $data )
    {      
        $this->B->mod_option[] = $this->B->M( MOD_COMMON, 'get_module_view', array('m' => 'earchive', 'tpl' => 'option') );
    } 
}

?>
