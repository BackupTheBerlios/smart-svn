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
            
            $row = &$result->fetchRow( DB_FETCHMODE_ASSOC );
            
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
