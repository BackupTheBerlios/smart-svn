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
 * user_register class 
 *
 */

class user_register
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
    function user_register()
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
        // get var name to store the result
        $this->B->$data['error_var'] = FALSE;
        $this->_error = & $this->B->$data['error_var'];
        
        if(FALSE === $this->_validate( $data ))
        {
            return FALSE;
        }
           
        $_data = array( 'error'     => 'tmp_error',
                        'user_data' => array('forename' => $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['forename'])),
                                             'lastname' => $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['lastname'])),
                                             'email'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['email'])),
                                             'login'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['login'])),
                                             'passwd'   => $this->B->db->quoteSmart(md5($data['reg_data']['passwd1'])),
                                             'rights'   => 1,
                                             'status'   => 1));
               
        if( FALSE === ($uid = $this->B->M( MOD_USER,
                                           'add',
                                           $_data )))
        {
            $this->_error .= 'Couldnt add user data';  
            return FALSE;
        }
        else
        {
            $header  = "From: {$this->B->sys['option']['email']}\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-type: text/html; charset={$this->B->sys['option']['charset']}\r\n";
                
            if($this->B->sys['option']['user']['register_type'] == 'auto')
            {
                $ustr = $this->_add_registered_user_data( $uid ); 
                    
                $validate_msg = str_replace("(URL)", "<a href='".SF_BASE_LOCATION."/index.php?view=validate&usr_id={$ustr}'>validate</a>",$data['email_msg']);
                $validate_msg = str_replace("(EMAIL)", "<a href='mailto:{$this->B->sys['option']['email']}'>{$this->B->sys['option']['email']}</a>",$validate_msg);
 
                if(FALSE == @mail( $data['reg_data']['email'], $data['email_subject'], $validate_msg, $header ))
                {
                    trigger_error("Email couldnt be sended to the user who want to register: {$data['reg_data']['email']}", E_USER_ERROR);
                    $this->_error .= "Unexpected error: Email couldnt be send to you!<br>Please contact the <a href='mailto:{$this->B->sys['option']['email']}'>admin</a> to validate your account.";  
                    return FALSE;
                }
            }
            elseif($this->B->sys['option']['user']['register_type'] == 'manual')
            {
                $subject = 'User validation needed';
                $msg     = 'You have to validate a user registration:<br />';
                $msg     .= '<a href="'.SF_BASE_LOCATION.'/index.php?admin=1&m=user&sec=edituser&uid='.$uid.'">'.SF_BASE_LOCATION.'/index.php?admin=1&m=user&sec=edituser&uid='.$uid.'</a>';
                if(FALSE === @mail($this->B->sys['option']['email'], $subject, $msg, $header))
                {
                    trigger_error("Sending manual validation email fails for login: {$_data['login']}.", E_USER_ERROR);
                    $this->_error .= "Unexpected error: Email couldnt be send to you!<br>Please contact the <a href='mailto:{$this->B->sys['option']['email']}'>admin</a> to validate your account.";  
                    return FALSE;                    
                }  
                return  TRUE;         
            }
            return TRUE;
        }
    } 

    /**
     * add_registered_user_data
     *
     * @param int $uid user ID
     * @return mixed md5_str|false
     */     
    function _add_registered_user_data( $uid )
    {
        $md5_str = commonUtil::unique_md5_str();
        $_time   = date("Y-m-d H:i:s", time()); 
        
        $sql = '
            INSERT INTO 
                '.$this->B->sys['db']['table_prefix'].'user_registered
                (uid,md5_str,reg_date)
            VALUES
                ('.$uid.',
                 "'.$md5_str.'",
                 "'.$_time.'")';
        
        $res = $this->B->db->query($sql);

        if (DB::isError($res)) 
        {
            trigger_error($res->getMessage()."\n".$res->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        return $md5_str;
    }
    
    function _validate( &$data )
    {
        if(empty($data['reg_data']['login']))
        {
            $this->_error .= '- Login field is empty<br /><br />';          
        }
        if(empty($data['reg_data']['passwd1']) ||
           ($data['reg_data']['passwd1'] != $data['reg_data']['passwd2']))
        {
            $this->_error .= '- Password fields are empty or has different entries<br /><br />';          
        }        
        if(empty($data['reg_data']['forename']))
        {
            $this->_error .= '- Forename field is empty<br /><br />';          
        }
        if(empty($data['reg_data']['lastname']))
        {
            $this->_error .= '- Lastname field is empty<br /><br />';          
        }        
        if(empty($data['reg_data']['email']))
        {
            $this->_error .= '- Email field is empty<br /><br />';          
        }  
        
        if($this->_error !== FALSE)
        {
            return FALSE;
        }
        
        return TRUE;
    }
}

?>
