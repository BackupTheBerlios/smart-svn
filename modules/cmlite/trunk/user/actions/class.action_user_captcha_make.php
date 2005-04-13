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
 * action_user_captcha_make class 
 *
 */

// captcha class
//
include_once( SF_BASE_DIR .'modules/user/actions/captcha/class.captcha.php' );

 
class action_user_captcha_make extends action
{
    /**
     * Create capcha picture and public key
     *
     * @param array $data
     */
    function perform( $data )
    {
        // get var name to store the result
        $this->B->$data['captcha_pic'] = '';
        $_captcha_pic                  = & $this->B->$data['captcha_pic'];              

        $this->B->$data['public_key'] = '';
        $_public_key                   = & $this->B->$data['public_key'];   
            
        // Captcha privat key!!!
        $captcha_privat_key = md5(implode('',file(SF_BASE_DIR.'data/common/config/config.php')));
        
        // The ttf font to create turing chars images
        $captcha_ttf_font = SF_BASE_DIR .'modules/user/actions/captcha/ttf_font/activa.ttf';
    
        // Relative folder of captcha pictures
        $captcha_pictures_folder = SF_RELATIVE_PATH . 'data/captcha';
    
        // Type of turing chars
        $captcha_char_type = 'num'; // or 'hex' 

        $captcha = & new captcha( $captcha_privat_key, SF_BASE_DIR, $captcha_ttf_font, $captcha_pictures_folder, $captcha_char_type );
   
        $captcha->captcha_picture_expire = 300;
        $captcha->width = 120;
        $captcha->string_len = 5;
        $captcha->shadow = FALSE;    

        $_captcha_pic = $captcha->make_captcha();
        @chmod(SF_BASE_DIR . $_captcha_pic, SF_FILE_MODE);
        $_public_key     = $captcha->public_key;

        return TRUE;
    } 
}

?>
