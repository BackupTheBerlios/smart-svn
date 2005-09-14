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
 * Setup action of the link module 
 *
 */
 
class ActionLinkSetup extends SmartAction
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
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}link_links (
                   `id_link`     int(11) unsigned NOT NULL auto_increment,
                   `id_node`     int(11) unsigned NOT NULL default 0,
                   `status`      tinyint(1) NOT NULL default 0,
                   `title`       text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description` text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `url`         text NOT NULL default '',
                   `hits`        int(11) unsigned NOT NULL default 0,
                   PRIMARY KEY   (`id_link`),
                   KEY `id_node` (`id_node`),
                   KEY `status`  (`status`),
                   KEY `hits`    (`hits`),
                   FULLTEXT      (`title`,`description`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}link_lock (
                   `id_link`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_link`    (`id_link`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`))";
        $this->model->dba->query($sql);
       
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `release`)
                  VALUES
                   ('link',
                    'Links Management',
                    3,
                    '0.1',
                    1,
                    'DATE: 23.8.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->dba->query($sql);            
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        $sql = "DROP TABLE IF EXISTS {$data['dbtablesprefix']}link_links,
                                     {$data['dbtablesprefix']}link_lock,
                                     {$data['dbtablesprefix']}link_node_rel";
        $this->model->dba->query($sql);  
    }
}

?>