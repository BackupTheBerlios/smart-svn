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
 * user_view_default class of the template "default.tpl.php"
 *
 */
 
class user_view_default
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
    function user_view_default()
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
     * Execute the view of the template "default.tpl.php"
     * create the template variables
     *
     * @return bool true on success else false
     */
    function perform()
    {
        if(isset($_REQUEST['usr_rights']))
            $this->B->tmp_rights = $_REQUEST['usr_rights'];
        else
            $this->B->tmp_rights = FALSE;

        // the user class
        include_once SF_BASE_DIR . 'modules/user/includes/class.user.php';

        //User Class instance
        $this->B->user = & new user; 

        $this->B->tmp_fields = array('uid','rights','status','email','login','forename','lastname');
        $this->B->all_users = $this->B->user->get_users( $this->B->tmp_fields, $this->B->tmp_rights );  

        return TRUE;
    }    
}

?>
