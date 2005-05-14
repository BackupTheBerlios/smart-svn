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
                   `header`      text NOT NULL default '',
                   `short_text`  text NOT NULL default '',
                   `body`        mediumtext NOT NULL default '',
                   `logo`         varchar(255) NOT NULL default '',
                   `media_folder` char(64) NOT NULL,
                   PRIMARY KEY     (`id_node`),
                   KEY `id_parent` (`id_parent`),
                   KEY `id_sector` (`id_sector`),
                   KEY `rank`      (`rank`),
                   KEY `status`    (`status`),
                   KEY `lock`      (`lock`),
                   KEY `lock_time` (`lock_time`))";
        $this->model->db->executeUpdate($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}navigation_node_media (
                   `id_media`     int(11) NOT NULL auto_increment,
                   `id_node`      int(11) NOT NULL default 0,
                   `media_type`   int(11) NOT NULL default 0,
                   `title`        text NOT NULL default '',
                   `text`         text NOT NULL default '',                   
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `file_type`    varchar(255) NOT NULL default '',
                   PRIMARY KEY      (`id_media`),
                   KEY `id_node`    (`id_node`),
                   KEY `media_type` (`media_type`))";
        $this->model->db->executeUpdate($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `rank`, `version`, `description`, `release`)
                  VALUES
                   ('navigation',
                    1,
                    '0.1',
                    '',
                    'DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->db->executeUpdate($sql);            
    } 

    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        $sql = "DROP TABLE IF EXISTS 
                    {$data['request']['dbtablesprefix']}navigation_node,
                    {$data['request']['dbtablesprefix']}navigation_node_media";
        $this->model->db->executeUpdate($sql);  
    }
}

?>