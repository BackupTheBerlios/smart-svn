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
 * ActionArticleUpgrade
 *
 * USAGE:
 * $model->action( 'article', 'upgrade', 
 *                 array('new_version' => string ); // new module version
 *
 */

class ActionArticleUpgrade extends SmartAction
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
        if(1 == version_compare('0.1', $this->config['module']['article']['version'], '=') )
        {
            // upgrade from module version 0.1 to 0.2
            $this->upgrade_0_1_to_0_2();     
            $this->config['module']['article']['version'] = '0.2';
        }

        if(1 == version_compare('0.2', $this->config['module']['article']['version'], '=') )
        {
            // upgrade from module version 0.2 to 0.3
            $this->upgrade_0_2_to_0_3();     
            $this->config['module']['article']['version'] = '0.3';
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
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config['dbTablePrefix']}article_node_view_rel (
                   `id_view`      int(11) unsigned NOT NULL default 0,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_node` (`id_node`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "CREATE TABLE IF NOT EXISTS {$this->config['dbTablePrefix']}article_view (
                   `id_view`      int(11) unsigned NOT NULL auto_increment,
                   `name`         varchar(255) NOT NULL default '',
                   PRIMARY KEY    (`id_view`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      
    }

    /**
     * upgrade from module version 0.2 to 0.3
     *
     */
    private function upgrade_0_2_to_0_3()
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$this->config['dbTablePrefix']}article_view_rel (
                   `id_view`      int(11) unsigned NOT NULL default 0,
                   `id_article`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);    
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
                        `id_module`={$this->config['module']['article']['id_module']}";

        $this->model->dba->query($sql);          
    }   
}

?>