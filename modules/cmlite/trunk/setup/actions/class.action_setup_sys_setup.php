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
 * action_setup_sys_setup class 
 *
 */
 
class action_setup_sys_setup extends action
{
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // Init error array
        $this->B->setup_error = array();

        $success = TRUE;
     
        if($success == TRUE)
            $success = M( MOD_SYSTEM,       'sys_setup' );
    
        if($success == TRUE)    
            $success = M( MOD_USER,         'sys_setup' );
            
        if($success == TRUE)    
            $success = M( MOD_COMMON,       'sys_setup' );            
        /*
        if($success == TRUE)
            $success = M( MOD_EARCHIVE,     'sys_setup' );
*/
        if($success == TRUE)
            $success = M( MOD_OPTION,       'sys_setup' );
    
        // close db connection if present
        if(is_object($this->B->db))
            $this->B->db->disconnect();
        
        // check on errors before proceed
        if( $success == TRUE )
        {
            // set default template group that com with this package
            $this->B->conf_val['option']['tpl'] = 'earchive';    
            $this->B->conf_val['info']['status'] = TRUE;
                
            // update config file with the data array $this->B->conf_val        
            // see modules/SF_BASE_MODULE/actions/class.action_SF_BASE_MODULE_sys_update_config.php
            M( SF_BASE_MODULE, 
               'sys_update_config', 
               array( 'data'      => $this->B->conf_val,
                      'file'     => SF_BASE_DIR . 'modules/'.SF_BASE_MODULE.'/config/config.php',
                      'var_name' => 'this->B->sys',
                      'type'     => 'PHPArray') );     
                
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
            exit;  
        }  
        return $success;
    } 
}

?>
