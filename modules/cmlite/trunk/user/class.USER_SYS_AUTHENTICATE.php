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
 * USER_SYS_LOAD_MODULE class 
 *
 */
 
class USER_SYS_AUTHENTICATE
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
    function USER_SYS_AUTHENTICATE()
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
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $this->B->login = TRUE;
        include_once(SF_BASE_DIR.'/admin/modules/user/class.auth.php');
        $this->B->auth = & new auth('admin');  
        if( ($this->B->auth->is_user == FALSE) && (SF_SECTION == 'admin') )
        {
            $this->B->login = FALSE;

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
            // load the login template
            include SF_BASE_DIR . '/admin/modules/user/templates/login.tpl.php';    
            exit; 
        }
    } 
}

?>
