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
        
        if($success == TRUE)
            $success = M( MOD_EARCHIVE,     'sys_setup' );

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
                
            M( MOD_COMMON, 'sys_update_config', $this->B->conf_val ); 
                
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1');
            exit;  
        }  
        return $success;
    } 
}

?>