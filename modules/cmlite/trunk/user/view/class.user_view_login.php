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
 * user_view_login class of the template "login.tpl.php"
 *
 */
 
class user_view_login
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
    function user_view_login()
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
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // Check login data
        if(isset($_POST['login']))
        {
            $this->B->M( MOD_USER, 
                         'check_login',
                         array( 'login'       => $_POST['login_name'],
                                'passwd'      => $_POST['password'],
                                'forward_url' => $_REQUEST['url']));
        }
            
        return TRUE;
    }   
}

?>
