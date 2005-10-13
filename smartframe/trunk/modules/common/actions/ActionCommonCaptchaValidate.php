<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * action_user_captcha_validate class 
 *
 * USAGE:
 *
 * $res= $this->model->action( 'common','captchaValidate',
 *                             array('turing_key'  => (string),
 *                                   'public_key'  => (string),
 *                                   'configPath'  => (string) ))
 *
 * return TRUE or FALSE
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
    
    public function validate( $data = FALSE )
    {
        if(!is_string($data['public_key']))
        {
            throw new SmartModelException("'public_key' isnt from type string");
        }
        if(!is_string($data['turing_key']))
        {
            throw new SmartModelException("'turing_key' isnt from type string");
        }   
        if(!is_string($data['configPath']))
        {
            throw new SmartModelException("'configPath' isnt from type string");
        }

        return TRUE;
    }
}

?>