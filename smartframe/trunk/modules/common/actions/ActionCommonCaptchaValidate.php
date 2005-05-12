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
 * action_user_captcha_validate class 
 *
 */

// captcha class
//
include_once( SMART_BASE_DIR .'modules/common/includes/class.captcha.php' );

 
class ActionCommonCaptchaValidate extends SmartAction
{
    /**
     * Validate capcha public key/turing key
     *
     * @param array $data
     */
    public function perform( $data = FALSE )
    {
        // Captcha privat key!!!
        $captcha_privat_key = md5(implode('',file($data['configPath'].'dbConnect.php')));
        
        // The ttf font to create turing chars images
        $captcha_ttf_font = SMART_BASE_DIR .'modules/common/includes/ttf_font/activa.ttf';
    
        // Relative folder of captcha pictures
        $captcha_pictures_folder = SMART_RELATIVE_PATH . 'data/common/captcha';
    
        // Type of turing chars
        $captcha_char_type = 'num'; // or 'hex' 

        $captcha = new captcha( $captcha_privat_key, SMART_BASE_DIR, $captcha_ttf_font, $captcha_pictures_folder, $captcha_char_type );

        if(FALSE == $captcha->check_captcha($data['public_key'], $data['turing_key']))
        {
             return FALSE;
        }

        return TRUE;
    } 
}

?>