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
 * user_sys_authenticate class 
 *
 * The variables produced by the authentication process:
 *
 * $B->is_logged
 * -------------
 * If a user was successfully authenticated this variable is set to TRUE.
 * Else this variable is set to FALSE.
 *
 * $B->logged_user_rights
 * ----------------------
 * User rights of the logged user
 *
 * $B->logged_id_user
 * ------------------
 * ID of the logged user
 *
 * $B->logged_user_forename
 * $B->logged_user_lastname
 * $B->logged_user_login
 * ---------------------
 * Lastname, forename and login of the logged user
 * 
 *
 */
 
class user_sys_authenticate
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
    function user_sys_authenticate()
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
     * User authentication
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $this->_auth( SF_SECTION );  
       
        if( (SF_SECTION == 'admin') && (FALSE == $this->_is_logged) )
        {
            $_REQUEST['view'] = 'login';
            $_REQUEST['m']    = SF_AUTH_MODULE;

            $this->B->is_logged = FALSE;
            return FALSE;
        }
        else
        {
            $this->B->is_logged = $this->_is_logged;
            return TRUE;
        }
    } 
    
    /**
     * User authentication
     *
     * @param string $section Section area "admin" or "public"
     */    
    function _auth( $section )
    {
        if(!isset($_SESSION['logged_id_user']) || empty($_SESSION['logged_id_user']))
        {
            $this->_is_logged = FALSE;
        }
        
        if($section == 'admin')
        {
            $_and = 'AND rights>1';
        }
        else
        {
            $_and = '';
        }        
        
        $id_user = (int) $_SESSION['logged_id_user'];
        
        $sql = "SELECT
                    uid,
                    forename,
                    lastname,
                    login
                FROM
                    {$this->B->sys['db']['table_prefix']}user_users
                WHERE
                    uid={$id_user}
                AND
                    status=2 {$_and}";
        
        $result = $this->B->db->query( $sql );
        
        if($result->numRows() == 1)
        {
            $this->B->logged_user_rights = (int) $_SESSION['logged_user_rights'];
            $this->B->logged_id_user     = (int) $_SESSION['logged_id_user'];
            $this->_is_logged  = TRUE;
            
            $row = $result->fetchRow( DB_FETCHMODE_ASSOC );
            
            $this->B->logged_user_forename = stripslashes($row['forename']);
            $this->B->logged_user_lastname = stripslashes($row['lastname']);
            $this->B->logged_user_login    = stripslashes($row['login']);  
        }
        else
        {
            $this->B->logged_user_rights = 0;
            $this->_is_logged            = FAlSE;
        }    
    }
}

?>
