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
 * common_sys_init class 
 *
 */
 
class action_common_sys_init extends action
{
    /**
     * Check if version number has changed and perfom additional upgarde code
     * Furthermore assign array with module menu names for the top right
     * module html seletor
     *
     * @param array $data
     */
    function perform( $data )
    {  
        // get data of the client browser
        $this->_get_client_data();
        
        // get os related separator to set include path
        if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
            $tmp_separator = ';';
        else
            $tmp_separator = ':';

        // set include path to the PEAR packages
        ini_set( 'include_path', SF_BASE_DIR . 'modules/common/PEAR' . $tmp_separator . ini_get('include_path') );
        unset($tmp_separator); 

        // Define base location
        define('SF_BASE_LOCATION', commonUtil::base_location());

        // init system config array
        $this->B->sys = array();

        // include system config array $this->B->sys
        if(file_exists(SF_BASE_DIR . 'data/common/config/config.php'))
            include_once( SF_BASE_DIR . 'data/common/config/config.php' );  

        // if setup was done
        if($this->B->sys['info']['status'] == TRUE)
        { 
            // here you may create db connection and start a session.
            // .... things, which are required by all other modules
            
            // include session class
            include_once( SF_BASE_DIR . 'modules/common/includes/class.session.php' ); 
            @ob_start();
            /* Create new object of session class */
            $this->B->session = & new session();  
            @ob_end_flush();              
        }
        // else launch setup
        else
        {
            // switch to the admin section if we comes from the public section
            if(SF_SECTION == 'public')
            {
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1');
                exit;  
            }
            
            // launch setup screen
            M( MOD_SYSTEM, 'get_view', array('m' => 'setup', 'view' => 'index')); 
            
            // Send the output buffer to the client
            ob_end_flush();
            exit;
        }    
    
        // Check for upgrade  
        if(MOD_COMMON_VERSION != (string)$this->B->sys['module']['common']['version'])
        {
            // set the new version num of this module
            $this->B->sys['module']['common']['version']  = MOD_COMMON_VERSION;
            $this->B->system_update_flag = TRUE;  
                
            // include here additional upgrade code
        }
        
        return SF_IS_VALID_ACTION;
    }   
    /**
     * get data of the client browser
     *
     */    
    function _get_client_data()
    {
        // assign browser language
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            @preg_match_all('/([a-z]*)/',$_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang, PREG_SET_ORDER);
            define( 'SF_CLIENT_LANG', $lang[0][1]);
        }    
    }    
}

?>