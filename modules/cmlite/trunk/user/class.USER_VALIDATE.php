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
 * USER_VALIDATE class 
 *
 */
 
class USER_VALIDATE
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
    function USER_VALIDATE()
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
        if(isset($_GET['md5_str']))
        {
            $_succ = &$this->B->$data['success_var'];
            $_succ = NULL;
            
            // get var name to store the result
            $_error = &$this->B->$data['error_var'];
            $_error = NULL;
            
            include( SF_BASE_DIR .'/admin/modules/user/class.user.php' );
            $user = & new user;
            if(FALSE === $user->auto_validate_registered_user( $_GET['md5_str'] ))
            {
                $_error = TRUE;          
            }
            else
            {
                $_succ = TRUE;   
            }
        }
    } 
}

?>
