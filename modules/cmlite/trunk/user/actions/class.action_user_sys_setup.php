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
 * action_user_sys_setup class 
 *
 */
 
class action_user_sys_setup extends action
{
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
        // Do setup 
        if( empty($_POST['sysname']) )
        {
            $this->B->setup_error[] = 'Sysadmin name field is empty!';
            $success = FALSE;
        }
        if( empty($_POST['syslastname']) )
        {
            $this->B->setup_error[] = 'Sysadmin lastname field is empty!';
            $success = FALSE;
        }
        if( empty($_POST['syslogin']) )
        {
            $this->B->setup_error[] = 'Sysadmin login field is empty!';
            $success = FALSE;
        }
        if( empty($_POST['syspassword1']) || ($_POST['syspassword1'] != $_POST['syspassword2']) )
        {
            $this->B->setup_error[] = 'Sysadmin password fields are empty or not equal!';
            $success = FALSE;
        } 
  
        if( $success == TRUE )
        {
            //create captcha_pics dir if it dosent exist
            if(!is_writeable( SF_BASE_DIR . 'data/captcha' ))
            {
                $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/captcha';
                $success = FALSE;
            }  
    
            if($success == TRUE)
            {
                // create db tables
                if(file_exists(SF_BASE_DIR . 'modules/user/actions/sys_setup/_setup_'.$_POST['dbtype'].'.php'))
                {
                    // include mysql setup
                    include_once( SF_BASE_DIR . 'modules/user/actions/sys_setup/_setup_'.$_POST['dbtype'].'.php' );    
                }
                else
                {
                    $this->B->setup_error[] = 'USER module: This db type isnt supported: ' . $_POST['dbtype'];
                    $success = FALSE;
                }
            }
    
            $this->B->conf_val['module']['user']['name']     = 'user';
            $this->B->conf_val['module']['user']['version']  = MOD_USER_VERSION;
            $this->B->conf_val['module']['user']['mod_type'] = 'openpublisher';
        
            $this->B->conf_val['db']['dbtype'] = $_POST['dbtype'];
    
            $this->B->conf_val['option']['user']['allow_register']  = TRUE;
            $this->B->conf_val['option']['user']['register_type']   = 'auto';
        }
        return $success;
    }
}

?>
