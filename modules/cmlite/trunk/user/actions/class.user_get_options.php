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
 * user_get_options class 
 *
 */
 
class user_get_options
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
    function user_get_options()
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
        // get user options template 
        // to include in the option module        
        $this->B->mod_option[] = $this->B->M( MOD_COMMON, 'get_module_view', array('m' => 'user', 'tpl' => 'option') );
    } 
}

?>
