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
 * user_login class 
 *
 */
 
class user_login
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
    function user_login()
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
        if(!empty($data['login']) && !empty($data['passwd']))
        {
            include_once(SF_BASE_DIR.'/admin/modules/user/actions/sys_authentication/class.auth.php');
            // Check login data
            if(isset($_POST['login']))
            {
                if(FALSE !== ($rights = $this->B->auth->checklogin($_POST['login_name'], $_POST['password'])))
                {
                    if($rights > 1)
                        @header('Location: index.php');
                    else
                        @header('Location: ../index.php');
                    exit;
                }
            }

            if(FALSE !== auth::checklogin($data['login'], $data['passwd']))
            {
                $query = base64_decode($data['urlvar']);
                @header('Location: '.SF_BASE_LOCATION.'/index.php'.$query);
                exit;                
            }
        }
    } 
}

?>
