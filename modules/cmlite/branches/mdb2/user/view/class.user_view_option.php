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
 * user_view_option class 
 *
 */
 
class user_view_option
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
    function user_view_option()
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
     * Set options for this module
     *
     * @param array $data
     */
    function perform( $data )
    {
        // set user options 
        // this event comes from the option module (module_loader.php)
        if(isset($_POST['update_user_options_allowreg']))
        {
            $this->B->sys['option']['user']['allow_register'] = (bool)$_POST['userallowregister'];
            $this->B->sys['option']['user']['register_type']  = $_POST['userregistertype'];
            $this->B->_modified = TRUE;
        }
        
        return TRUE;
    } 
}

?>
