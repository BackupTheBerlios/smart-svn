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
 * action_common_sys_setup class 
 *
 */
 
class action_common_sys_setup extends action
{
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {            
        // init the success var
        $success = TRUE;
            
        // include here all stuff to get work this module:
        // creating db tables

        // The module name and version
        // this array vars were saved later by the setup handler
        // in the file /admin/modules/common/config/config.php
        //
        $this->B->conf_val['module']['common']['name']     = 'common';
        $this->B->conf_val['module']['common']['version']  = MOD_COMMON_VERSION;
        $this->B->conf_val['module']['common']['mod_type'] = 'littlejo';
        
        $this->B->conf_val['option']['cache']              = TRUE;     


        // setup directories
        //
        $old_umask = @umask(0);
        
        if( !is_dir( SF_BASE_DIR . 'data' ) )
        {
            $this->B->setup_error[] = 'This directory must exists: ' . SF_BASE_DIR . 'data';
            return FALSE;
        }        
        elseif( !is_writeable( SF_BASE_DIR . 'data' ) )
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data';
            return FALSE;
        }

        if( !is_dir(SF_BASE_DIR . 'data/common') )
        {
            if( !@mkdir( SF_BASE_DIR . 'data/common', SF_DIR_MODE ) ) 
            {
                trigger_error('Cant create dir: '.SF_BASE_DIR . 'data/common', E_USER_WARNING);
            }
        }
        elseif( !is_writeable( SF_BASE_DIR . 'data/common' ) )
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/common';
            $success = FALSE;
        }        

        if( !is_dir(SF_BASE_DIR . 'data/common/config') )
        {
            if( !@mkdir( SF_BASE_DIR . 'data/common/config', SF_DIR_MODE ) ) 
            {
                trigger_error('Cant create dir: '.SF_BASE_DIR . 'data/common/config', E_USER_WARNING);
            }
            else
            {
                if( !@copy(SF_BASE_DIR . 'smart/.htaccess', SF_BASE_DIR . 'data/common/config') )
                {
                    trigger_error('Could not copy .htaccess to dir: '.SF_BASE_DIR . 'data/common/config', E_USER_WARNING);                
                }
            }
        }
        elseif( !is_writeable( SF_BASE_DIR . 'data/common/config' ) )
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/common/config/';
            $success = FALSE;
        }

        if( !is_dir(SF_BASE_DIR . 'data/common/cache') )
        {
            if( !@mkdir( SF_BASE_DIR . 'data/common/cache', SF_DIR_MODE ) ) 
            {
                trigger_error('Cant create dir: '.SF_BASE_DIR . 'data/common/cache', E_USER_WARNING);
            }
            else
            {
                if( !@copy(SF_BASE_DIR . 'smart/.htaccess', SF_BASE_DIR . 'data/common/cache') )
                {
                    trigger_error('Could not copy .htaccess to dir: '.SF_BASE_DIR . 'data/common/cache', E_USER_WARNING);                
                }
            }            
        }
        elseif( !is_writeable( SF_BASE_DIR . 'data/common/cache' ) )
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/common/config/';
            $success = FALSE;
        }       
        
        @umask($old_umask);
        
        // if noting is going wrong $success is still TRUE else FALSE
        // ex.: if creating db tables fails you must set this var to false
        return $success;  
    }    
}

?>