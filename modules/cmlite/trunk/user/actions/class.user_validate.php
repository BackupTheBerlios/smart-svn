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
 * user_validate class 
 *
 */
 
class user_validate
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
    function user_validate()
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
     * Validate new user
     *
     * @param array $data
     */
    function perform( $data )
    {
        if(isset($_GET['usr_id']))
        {            
            include( SF_BASE_DIR .'modules/user/includes/class.user.php' );
            $user = & new user;
            return $user->auto_validate_registered_user( $_GET['usr_id'] ));
        }
        return FALSE;
    } 
}

?>
