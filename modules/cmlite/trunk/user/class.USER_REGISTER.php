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
 * USER_REGISTER class 
 *
 */
 
class USER_REGISTER
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
    function USER_REGISTER()
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
        // check if option allow_register is not set
        if($this->B->sys['option']['user']['allow_register'] == FALSE)
        {
            @header('Location: '.SF_BASE_LOCATION.'/index.php');
            exit;  
        }        
        
        // captcha class
        //
        include( SF_BASE_DIR .'/admin/modules/user/captcha/class.captcha.php' );
    
        // Captcha privat key!!!
        $captcha_privat_key = md5(implode('',file(SF_BASE_DIR.'/admin/config/config_system.xml.php')));
        
        // The ttf font to create turing chars images
        $captcha_ttf_font = SF_BASE_DIR .'/admin/modules/user/captcha/ttf_font/activa.ttf';
    
        // Relative folder of captcha pictures
        $captcha_pictures_folder = 'admin/modules/user/captcha/pics';
    
        // Type of turing chars
        $captcha_char_type = 'num'; // or 'hex' 
        
        $captcha = & new captcha( $captcha_privat_key, SF_BASE_DIR, $captcha_ttf_font, $captcha_pictures_folder, $captcha_char_type );
        $captcha->captcha_picture_expire = 300;
        $captcha->width = 120;
        $captcha->string_len = 5;
        $captcha->shadow = FALSE;    

        $this->B->captcha_turing_picture = $captcha->make_captcha();
        @chmod(SF_BASE_DIR . '/' . $this->B->captcha_turing_picture, SF_FILE_MODE);
        $this->B->captcha_public_key     = $captcha->public_key;

        if($data['register'] != FALSE)
        {
            // get var name to store the result
            $_error = &$GLOBALS['B']->$data['error_var'];
            $_error = FALSE;

            if(FALSE == $captcha->check_captcha($_POST['captcha_public_key'], $_POST['captcha_turing_key']))
            {
                 $_error .= '- Wrong turing key<br /><br />'; 
            }
        
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
                include( SF_BASE_DIR .'/admin/modules/user/class.user.php' );
                
                $user = & new user;
            
                $data = array();
                $data['forename'] = $this->B->db->quoteSmart($this->B->util->stripSlashes($data['reg_data']['forename']));
                $data['lastname'] = $this->B->db->quoteSmart($this->B->util->stripSlashes($data['reg_data']['lastname']));
                $data['login']    = $this->B->db->quoteSmart($this->B->util->stripSlashes($data['reg_data']['login']));
                $data['passwd']   = $this->B->db->quoteSmart(md5($this->B->util->stripSlashes($data['reg_data']['passwd1'])));
                $data['email']    = $this->B->db->quoteSmart($this->B->util->stripSlashes($data['reg_data']['email']));
                $data['status']   = 0;
                $data['rights']   = 1;
                
                $_succ = &$GLOBALS['B']->$data['success_var'];
                $_succ = FALSE;
                
                if( FALSE === ($uid = $user->add_user( $data )))
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
                    
                    $validate_msg = str_replace("(URL)", "<a href='".SF_BASE_LOCATION."/index.php?tpl=register&md5_str={$ustr}'>".SF_BASE_LOCATION."/index.php?tpl=register&md5_str={$ustr}</a>",$data['email_msg']);
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
                    $msg     .= '<a href="'.SF_BASE_LOCATION.'/admin/index.php?m=USER&mf=edit_usr&uid='.$uid.'">'.$this->B->sys['option']['url'].'/admin/index.php?m=USER&mf=edit_usr&uid='.$uid.'</a>';
                    if(FALSE === @mail($this->B->sys['option']['email'],$subject,$msg,$header))
                    {
                        trigger_error("Sending validation email fails.", E_USER_ERROR);
                    }  
                    $_succ = TRUE;
                    $_error = FALSE;            
                }
                }
            }
            
            if($_error !== FALSE)
            {
                $_form = &$this->B->$data['form_var'];
                $_form = array();
                
                $_form['forename'] = $this->B->util->stripslashes($data['reg_data']['forename']); 
                $_form['lastname'] = $this->B->util->stripslashes($data['reg_data']['lastname']); 
                $_form['login']    = $this->B->util->stripslashes($data['reg_data']['login']); 
                $_form['email']    = $this->B->util->stripslashes($data['reg_data']['email']); 
            }            
        }
    } 
}

?>
