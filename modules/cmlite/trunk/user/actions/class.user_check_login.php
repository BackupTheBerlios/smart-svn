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
 * user_check_login class 
 *
 */
 
class user_check_login
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
    function user_check_login()
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
     * Check login data and set session vars and url forward  on success
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $passwd = md5($data['passwd']);
        
        $sql = "SELECT 
                    uid,
                    rights
                FROM
                    {$this->B->sys['db']['table_prefix']}user_users
                WHERE
                    login='{$data['login']}'
                AND
                    passwd='{$passwd}'
                AND
                    status=2";
        
        $result = $this->B->db->query($sql);
     
        if($result->numRows() == 1)
        {
            $row = $result->fetchRow( DB_FETCHMODE_ASSOC );
            $this->B->session->set('logged_id_user',       $row['uid']);
            $this->B->session->set('logged_user_rights',   $row['rights']);

            $admin = '';
            if( SF_SECTION == 'admin')
            {
                $admin = '?admin=1';
            }
            
            $query = '';
            if(isset($data['forward_urlvar']))
            {
                $amp = '?';
                if(!empty($admin))
                {
                    $amp = '&';
                }
                $query = $amp.base64_decode($data['forward_urlvar']);
            }
            
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.$admin.$query);
            exit;
        }
        else
        {
            return FAlSE;
        }  
    } 
}

?>
