<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Public user module event handler
 */

// Check if this file is included in the environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Name of the event handler
define ( 'MOD_USER' , 'USER');

define ( 'EVT_USER_LOGIN' ,        'USER_0');
define ( 'EVT_USER_REGISTER' ,     'USER_1');
define ( 'EVT_USER_VALIDATE' ,     'USER_2');


// register this handler                       
if (FALSE == $B->register_handler(MOD_USER,
                           array ( 'module'        => MOD_USER,
                                   'event_handler' => 'user_event_handler') ))
{
    trigger_error( 'The handler '.MOD_USER.' exist: '.__FILE__.' '.__LINE__, E_USER_ERROR  );        
} 

// The handler function
function user_event_handler( $evt )
{
    global $B;

    switch( $evt['code'] )
    {
        case EVT_AUTHENTICATE:
            include_once(SF_BASE_DIR.'/admin/modules/user/class.auth.php');
            $B->auth = & new auth( SF_SECTION );  
            break;
        case EVT_USER_LOGIN:
            if(!empty($evt['data']['login']) && !empty($evt['data']['passwd']))
            {
                include_once(SF_BASE_DIR.'/admin/modules/user/class.auth.php');
                if(FALSE !== auth::checklogin($evt['data']['login'], $evt['data']['passwd']))
                {
                    $query = base64_decode($evt['data']['urlvar']);
                    @header('Location: '.SF_BASE_LOCATION.'/index.php'.$query);
                    exit;                
                }
            }
            break;  
        case EVT_USER_REGISTER:
            // check if option allow_register is not set
            if($B->sys['option']['user']['allow_register'] == FALSE)
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
            $captcha_pictures_folder = 'admin/tmp/captcha_pics';
    
            // Type of turing chars
            $captcha_char_type = 'num'; // or 'hex' 
        
            $captcha = & new captcha( $captcha_privat_key, SF_BASE_DIR, $captcha_ttf_font, $captcha_pictures_folder, $captcha_char_type );
            $captcha->captcha_picture_expire = 300;
            $captcha->width = 120;
            $captcha->string_len = 5;
            $captcha->shadow = FALSE;    

            $B->captcha_turing_picture = $captcha->make_captcha();
            @chmod(SF_BASE_DIR . '/' . $B->captcha_turing_picture, SF_FILE_MODE);
            $B->captcha_public_key     = $captcha->public_key;

            if($evt['data']['register'] != FALSE)
            {
                // get var name to store the result
                $_error = &$GLOBALS['B']->$evt['data']['error_var'];
                $_error = FALSE;

                if(FALSE == $captcha->check_captcha($_POST['captcha_public_key'], $_POST['captcha_turing_key']))
                {
                     $_error .= '- Wrong turing key<br /><br />'; 
                }
        
                if(empty($evt['data']['reg_data']['login']))
                {
                    $_error .= '- Login field is empty<br /><br />';              
                }
                if(empty($evt['data']['reg_data']['passwd1']) ||
                   ($evt['data']['reg_data']['passwd1'] != $evt['data']['reg_data']['passwd2']))
                {
                    $_error .= '- Password fields are empty or have different entries<br /><br />';              
                }            
                if(empty($evt['data']['reg_data']['forename']))
                {
                    $_error .= '- Forename field is empty<br /><br />';              
                }
                if(empty($evt['data']['reg_data']['lastname']))
                {
                    $_error .= '- Lastname field is empty<br /><br />';              
                }            
                if(empty($evt['data']['reg_data']['email']))
                {
                    $_error .= '- Email field is empty<br /><br />';              
                }             
                

                if($_error === FALSE)
                {
                    // captcha class
                    include( SF_BASE_DIR .'/admin/modules/user/class.user.php' );
                    
                    $user = & new user;
                
                    $data = array();
                    $data['forename'] = $B->db->quoteSmart($B->util->stripSlashes($evt['data']['reg_data']['forename']));
                    $data['lastname'] = $B->db->quoteSmart($B->util->stripSlashes($evt['data']['reg_data']['lastname']));
                    $data['login']    = $B->db->quoteSmart($B->util->stripSlashes($evt['data']['reg_data']['login']));
                    $data['passwd']   = $B->db->quoteSmart(md5($B->util->stripSlashes($evt['data']['reg_data']['passwd1'])));
                    $data['email']    = $B->db->quoteSmart($B->util->stripSlashes($evt['data']['reg_data']['email']));
                    $data['status']   = 0;
                    $data['rights']   = 1;
                    
                    $_succ = &$GLOBALS['B']->$evt['data']['success_var'];
                    $_succ = FALSE;
                    
                    if( FALSE === ($uid = $user->add_user( $data )))
                    {
                        $_error .= 'Login exists. Chose an other one';  
                        $_succ  = FALSE;
                        $_error = TRUE;
                    }
                    else
                    {
                        $header  = "From: {$B->sys['option']['email']}\r\n";
                        $header .= "MIME-Version: 1.0\r\n";
                        $header .= "Content-type: text/html; charset={$B->sys['option']['charset']}\r\n";
                    
                        if($B->sys['option']['user']['register_type'] == 'auto')
                        {
                            $ustr = $user->add_registered_user_data( $uid ); 
                            
                            $validate_msg = str_replace("(URL)", "<a href='".SF_BASE_LOCATION."/index.php?tpl=register&md5_str={$ustr}'>".SF_BASE_LOCATION."/index.php?tpl=register&md5_str={$ustr}</a>",$evt['data']['email_msg']);
                            $validate_msg = str_replace("(EMAIL)", "<a href='mailto:{$B->sys['option']['email']}'>{$B->sys['option']['email']}</a>",$validate_msg);
 
                            if(FALSE === @mail($evt['data']['reg_data']['email'],$evt['data']['email_subject'],$validate_msg,$header))
                            {
                                trigger_error("Email couldnt be sended to registered user: {$evt['data']['reg_data']['email']}", E_USER_ERROR);
                                $_succ   = FALSE;
                                $_error = TRUE;
                            }
                            else
                            {
                                $_succ = TRUE;
                                $_error = FALSE;
                            }
                        }
                        elseif($B->sys['option']['user']['register_type'] == 'manual')
                        {
                            $subject = 'User validation needed';
                            $msg     = 'You have to validate a user registration:<br />';
                            $msg     .= '<a href="'.SF_BASE_LOCATION.'/admin/index.php?m=USER&mf=edit_usr&uid='.$uid.'">'.$B->sys['option']['url'].'/admin/index.php?m=USER&mf=edit_usr&uid='.$uid.'</a>';
                            if(FALSE === @mail($B->sys['option']['email'],$subject,$msg,$header))
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
                    $_form = &$GLOBALS['B']->$evt['data']['form_var'];
                    $_form = array();
                    
                    $_form['forename'] = $B->util->stripslashes($evt['data']['reg_data']['forename']); 
                    $_form['lastname'] = $B->util->stripslashes($evt['data']['reg_data']['lastname']); 
                    $_form['login']    = $B->util->stripslashes($evt['data']['reg_data']['login']); 
                    $_form['email']    = $B->util->stripslashes($evt['data']['reg_data']['email']); 
                }                
            }
            break;             
        case EVT_USER_VALIDATE:
            if(isset($_GET['md5_str']))
            {
                $_succ = &$GLOBALS['B']->$evt['data']['success_var'];
                $_succ = NULL;
                
                // get var name to store the result
                $_error = &$GLOBALS['B']->$evt['data']['error_var'];
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
            break;          
    }
}

?>
