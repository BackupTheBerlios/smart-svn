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
     * check login and password
     *
     * @param array $data
     */
    function perform( $data )
    {
        if(!empty($data['login']) && !empty($data['passwd']))
        {
            include_once(SF_BASE_DIR.'modules/user/includes/class.auth.php');
            if(FALSE !== auth::checklogin($data['login'], $data['passwd']))
            {
                $query = base64_decode($data['urlvar']);
                @header('Location: '.SF_BASE_LOCATION.'/index.php?'.$query);
                exit;                
            }
        }
        return FALSE;
    } 
}

?>
