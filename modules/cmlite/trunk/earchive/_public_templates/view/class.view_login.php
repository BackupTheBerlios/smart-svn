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
 * view_login class of the template "group_login.tpl.php"
 *
 */
 
class view_login
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
    function view_login()
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
     * Execute the view of the template "group_login.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        if(isset($_POST['login']))
        {
            /* check login and password */
            $this->B->M( MOD_USER, 
                         'login', 
                         array( 'login'  => $_POST['login_name'],
                                'passwd' => $_POST['password'],
                                'urlvar' => $_GET['url'])); 
        }
        return TRUE;
    }    
}

?>
