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
include_once( SF_BASE_DIR .'modules/user/actions/captcha/class.captcha.php' );

 
class action_user_captcha_validate extends action
{
    /**
     * Validate capcha public key/turing key
     *
     * @param array $data
     */
    function perform( $data )
    {
        // get var name to store the result
        $this->B->$data['error_var'] = FALSE;
        $_error = & $this->B->$data['error_var'];

        // Captcha privat key!!!
        $captcha_privat_key = md5(implode('',file(SF_BASE_DIR.'data/common/config/config.php')));
        
        // The ttf font to create turing chars images
        $captcha_ttf_font = SF_BASE_DIR .'modules/user/actions/captcha/ttf_font/activa.ttf';
    
        // Relative folder of captcha pictures
        $captcha_pictures_folder = SF_RELATIVE_PATH . 'data/captcha';
    
        // Type of turing chars
        $captcha_char_type = 'num'; // or 'hex' 

        $captcha = & new captcha( $captcha_privat_key, SF_BASE_DIR, $captcha_ttf_font, $captcha_pictures_folder, $captcha_char_type );

        if(FALSE == $captcha->check_captcha($data['public_key'], $data['turing_key']))
        {
             $_error .= '- Wrong turing key<br /><br />'; 
             return FALSE;
        }

        return TRUE;
    } 
}

?>
