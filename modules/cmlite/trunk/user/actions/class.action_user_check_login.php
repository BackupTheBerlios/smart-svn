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
 * action_user_check_login class 
 *
 */
 
class action_user_check_login extends action
{
    /**
     * Check login data and set session vars and url forward  on success
     *
     * @param array $data
     */
    function perform( & $data )
    {    
        // get login and password in sql conform format
        $passwd = $this->B->db->quoteSmart( md5($data['passwd']) );
        $login  = $this->B->db->quoteSmart( $data['login']       );
        
        $sql = "SELECT 
                    uid,
                    rights
                FROM
                    {$this->B->sys['db']['table_prefix']}user_users
                WHERE
                    login={$login}
                AND
                    passwd={$passwd}
                AND
                    status=2";
        
        $result = $this->B->db->query($sql);
     
        if($result->numRows() == 1)
        {
            $row = $result->fetchRow( DB_FETCHMODE_ASSOC );
            
            // set session data
            $this->B->session->set('xxxxx',    TRUE);
            $this->B->session->set('user_logged_uid',    $row['uid']);
            $this->B->session->set('user_logged_login',  $login);
            $this->B->session->set('user_logged_rights', $row['rights']);
            $this->B->session->set('user_logged_hashid', md5($this->B->sys['system']['hashid'].$login.$row['uid']));

            // if login was done from the admin section
            $admin = '';
            if( SF_SECTION == 'admin')
            {
                $admin = '?admin=1';
            }
            
            // add additional query
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
            return FALSE;
        }  
    } 

    /**
     * Validate data before passed to the perform methode
     *
     * @param array $data
     */    
    function validate( & $data )
    {
        if( @preg_match("/[^a-zA-Z0-9]/", $data['login']) )
        {
            $this->B->$data['error'] = 'Login entry is not correct! Only 3-30 chars a-zA-Z0-9 are accepted.';
            return FALSE;        
        }
        if( @preg_match("/[^a-zA-Z0-9]/", $data['passwd']) )
        {
            $this->B->$data['error'] = 'Password entry is not correct! Only 3-30 chars a-zA-Z0-9 are accepted.';
            return FALSE;        
        }  
        
        return TRUE;
    }
}

?>
