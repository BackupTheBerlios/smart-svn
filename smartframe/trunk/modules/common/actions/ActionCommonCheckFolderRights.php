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
 * ActionCommonCheckFolderRights 
 *
 * USAGE:
 *
 * $model->action('common','checkFolderRights', array('error' => & array() )); 
 */
 
class ActionCommonCheckFolderRights extends SmartAction
{
    /**
     * check if folders are writeable by php scripts
     *
     */
    public function perform( $data = FALSE )
    {
        $captcha_folder = SMART_BASE_DIR . 'data/common/captcha';
        if(!is_writeable($captcha_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$captcha_folder;    
        }

        $config_folder = $this->model->config['config_path'];
        if(!is_writeable($config_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$config_folder;    
        }

        $logs_folder = $this->model->config['logs_path'];
        if(!is_writeable($logs_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$logs_folder;    
        }
        
        $cache_folder = $this->model->config['cache_path'];
        if(!is_writeable($cache_folder))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$cache_folder;    
        }          

        $smartyCompiled = $this->model->config['cache_path'] . 'smartyCompiled';
        if(!is_writeable($smartyCompiled))
        {
            $data['error'][] = 'Must be writeable by php scripts: '.$smartyCompiled;    
        }    

        return TRUE;
    } 
    /**
     * validate $data
     *
     */ 
    public function validate( $data = FALSE )
    {
        if(!isset($data['error']))
        {
            throw new SmartModelException("'error' isnt defined");
        }
        if(!is_array($data['error']))
        {
            throw new SmartModelException("'error' isnt from type array");
        }
        
        return TRUE;
    }
}

?>