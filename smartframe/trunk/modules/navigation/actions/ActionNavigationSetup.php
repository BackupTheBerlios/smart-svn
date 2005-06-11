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
 * Setup action of the common module 
 *
 */
 
class ActionNavigationSetup extends SmartAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['rollback']))
        {
            $this->rollback($data);
            return TRUE;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}navigation_node (
                   `id_node`   int(11) NOT NULL auto_increment,
                   `id_parent` int(11) NOT NULL default 0,
                   `id_sector` int(11) NOT NULL default 0,
                   `status`    int(11) NOT NULL default 0,
                   `rank`      int(11) NOT NULL default 0,
                   `lock`        int(11) NOT NULL default 0,
                   `lock_time`   datetime NOT NULL default '0000-00-00 00:00:00',
                   `title`       text NOT NULL default '',
                   `short_text`  text NOT NULL default '',
                   `body`        mediumtext NOT NULL default '',
                   `logo`         varchar(255) NOT NULL default '',
                   `media_folder` char(64) NOT NULL,
                   PRIMARY KEY     (`id_node`),
                   KEY (`id_parent`,`id_sector`,`rank`,`status`),
                   KEY `lock`      (`lock`),
                   KEY `lock_time` (`lock_time`))";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `release`)
                  VALUES
                   ('navigation',
                    'Navigation Nodes Management',
                    2,
                    '0.1',
                    1,
                    'DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->dba->query($sql);            
    } 

    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        $sql = "DROP TABLE IF EXISTS {$data['dbtablesprefix']}navigation_node";
        $this->model->dba->query($sql);  
    }
}

?>