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
 * ActionCommonUpgrade
 *
 * USAGE:
 * $model->action( 'common', 'upgrade', 
 *                 array('new_version' => string ); // new module version
 *
 */

class ActionCommonUpgrade extends SmartAction
{
    /**
     * Do upgrade
     *
     * @param mixed $data
     */
    public function perform( $data = FALSE )
    {
        // do upgrade
        //
        if(1 == version_compare('0.1', $this->config['module']['common']['version'], '=') )
        {
            // upgrade from module version 0.1 to 0.2
            $this->upgrade_0_1_to_0_2();          
        }
        if(1 == version_compare('0.2', $this->config['module']['common']['version'], '=') )
        {
            // upgrade from module version 0.2 to 0.3
            $this->upgrade_0_2_to_0_3();          
        }
        if(1 == version_compare('0.3', $this->config['module']['common']['version'], '=') )
        {
            // upgrade from module version 0.3 to 0.4
            $this->upgrade_0_3_to_0_4();          
        }
        if(1 == version_compare('0.4', $this->config['module']['common']['version'], '=') )
        {
            // upgrade from module version 0.3 to 0.4
            $this->upgrade_0_4_to_0_5();          
        }
        
        // update to new module version number
        $this->setNewModuleVersionNumber( $data['new_version'] ); 
    }

    /**
     * upgrade from module version 0.1 to 0.2
     *
     */
    private function upgrade_0_1_to_0_2()
    {
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}common_config
                ADD session_maxlifetime int(11) NOT NULL default 7200 
                AFTER max_lock_time";
               
        $this->model->dba->query($sql);
        $this->config['module']['common']['version'] = '0.2';
    }

    /**
     * upgrade from module version 0.2 to 0.3
     *
     */
    private function upgrade_0_2_to_0_3()
    {
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}common_config
                ADD `smart_version_num` varchar(20) NOT NULL default '{$this->config['smart_version']}' 
                AFTER `charset`";
               
        $this->model->dba->query($sql);
        $this->config['smart_version_num'] = '0.3';
    }

    /**
     * upgrade from module version 0.3 to 0.4
     *
     */
    private function upgrade_0_3_to_0_4()
    {
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}common_config
                ADD `smart_version_num` varchar(20) NOT NULL default '{$this->config['smart_version']}' 
                AFTER `charset`";
               
        $this->model->dba->query($sql);
        $this->config['smart_version_num'] = '0.4';
    }

    /**
     * upgrade from module version 0.4 to 0.5
     *
     */
    private function upgrade_0_4_to_0_5()
    {
        $server_timezone = (int)(date("Z") / 3600);
        
        if( ($server_timezone < -12 ) || ($server_timezone > -12 ) )
        {
            $server_timezone = 1;
        }
        
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}common_config
                ADD `server_gmt` tinyint(2) NOT NULL default {$server_timezone} 
                AFTER `textarea_rows`";
               
        $this->model->dba->query($sql);
        
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}common_config
                ADD `default_gmt` tinyint(2) NOT NULL default {$server_timezone}  
                AFTER `textarea_rows`";
               
        $this->model->dba->query($sql);
        
        $sql = "ALTER TABLE {$this->config['dbTablePrefix']}common_config
                ADD `css_folder` varchar(255) NOT NULL default '{$this->config['smart_version']}' 
                AFTER `templates_folder`";
               
        $this->model->dba->query($sql);
        
        $this->config['smart_version_num'] = '0.5';
    }

    /**
     * Validate data passed to this action
     */
    public function validate( $data = FALSE )
    {
        if(!isset($data['new_version']))
        {
            throw new SmartModelException('data var "new_version" is required');        
        }  
        if(!is_string($data['new_version']))
        {
            throw new SmartModelException('data var "new_version" isnt from type string');        
        }   
        
        return TRUE;
    }    
    
    /**
     * update to new module version number
     *
     * @param string $version  New module version number
     */
    private function setNewModuleVersionNumber( $version )
    {
        $sql = "UPDATE {$this->config['dbTablePrefix']}common_module
                    SET
                        `version`='{$version}'
                    WHERE
                        `id_module`={$this->config['module']['common']['id_module']}";

        $this->model->dba->query($sql);          
    }   
}

?>