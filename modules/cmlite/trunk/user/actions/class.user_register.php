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
        $_error = & $this->B->$data['error_var'];
        
        if(empty($data['reg_data']['login']))
        {
            $_error .= '- Login field is empty<br /><br />';          
        }
        if(empty($data['reg_data']['passwd1']) ||
           ($data['reg_data']['passwd1'] != $data['reg_data']['passwd2']))
        {
            $_error .= '- Password fields are empty or have different entries<br /><br />';          
        }        
        if(empty($data['reg_data']['forename']))
        {
            $_error .= '- Forename field is empty<br /><br />';          
        }
        if(empty($data['reg_data']['lastname']))
        {
            $_error .= '- Lastname field is empty<br /><br />';          
        }        
        if(empty($data['reg_data']['email']))
        {
            $_error .= '- Email field is empty<br /><br />';          
        }         
            
        if($_error === FALSE)
        {
            // captcha class
            include( SF_BASE_DIR .'modules/user/includes/class.user.php' );
             
            $user = & new user;
           
            $_data = array();
            $_data['forename'] = $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['forename']));
            $_data['lastname'] = $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['lastname']));
            $_data['login']    = $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['login']));
            $_data['passwd']   = $this->B->db->quoteSmart(md5(commonUtil::stripSlashes($data['reg_data']['passwd1'])));
            $_data['email']    = $this->B->db->quoteSmart(commonUtil::stripSlashes($data['reg_data']['email']));
            $_data['status']   = 0;
            $_data['rights']   = 1;
                
            $this->B->$data['success_var'] = FALSE;
            $_succ = & $this->B->$data['success_var'];
               
            if( FALSE === ($uid = $user->add_user( $_data )))
            {
                $_error .= 'Login exists. Chose an other one';  
                $_succ  = FALSE;
                $_error = TRUE;
            }
            else
            {
                $header  = "From: {$this->B->sys['option']['email']}\r\n";
                $header .= "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html; charset={$this->B->sys['option']['charset']}\r\n";
                
                if($this->B->sys['option']['user']['register_type'] == 'auto')
                {
                    $ustr = $user->add_registered_user_data( $uid ); 
                    
                    $validate_msg = str_replace("(URL)", "<a href='".SF_BASE_LOCATION."/index.php?view=register&md5_str={$ustr}'>validate</a>",$data['email_msg']);
                    $validate_msg = str_replace("(EMAIL)", "<a href='mailto:{$this->B->sys['option']['email']}'>{$this->B->sys['option']['email']}</a>",$validate_msg);
 
                    if(FALSE === @mail($data['reg_data']['email'],$data['email_subject'],$validate_msg,$header))
                    {
                        trigger_error("Email couldnt be sended to registered user: {$data['reg_data']['email']}", E_USER_ERROR);
                        $_succ   = FALSE;
                        $_error = TRUE;
                    }
                    else
                    {
                        $_succ = TRUE;
                        $_error = FALSE;
                    }
                }
                elseif($this->B->sys['option']['user']['register_type'] == 'manual')
                {
                    $subject = 'User validation needed';
                    $msg     = 'You have to validate a user registration:<br />';
                    $msg     .= '<a href="'.SF_BASE_LOCATION.'/index.php?admin=1&m=user&sec=edituser&uid='.$uid.'">'.SF_BASE_LOCATION.'/index.php?admin=1&m=user&sec=edituser&uid='.$uid.'</a>';
                    if(FALSE === @mail($this->B->sys['option']['email'],$subject,$msg,$header))
                    {
                        trigger_error("Sending validation email fails.", E_USER_ERROR);
                    }  
                    $_succ = TRUE;
                    $_error = FALSE;            
                }
            }
        }
        else
        {
            return FALSE;
        }
    } 
}

?>
