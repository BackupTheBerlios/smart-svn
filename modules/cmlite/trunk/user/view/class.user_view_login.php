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
            include_once(SF_BASE_DIR.'modules/user/actions/includes/class.auth.php');
            if(FALSE !== ($rights = $this->B->auth->checklogin($_POST['login_name'], $_POST['password'])))
            {
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
                exit;
            }
        }
            
        return TRUE;
    }    
}

?>
