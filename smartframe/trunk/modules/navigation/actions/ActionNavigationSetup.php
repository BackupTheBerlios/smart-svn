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
                   `id_node`      int(11) unsigned NOT NULL auto_increment,
                   `id_parent`    int(11) unsigned NOT NULL default 0,
                   `id_sector`    int(11) unsigned NOT NULL default 0,
                   `status`       tinyint(1) NOT NULL default 0,
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `logo`         varchar(255) NOT NULL default '',
                   `media_folder` char(32) NOT NULL,
                   PRIMARY KEY     (`id_node`),
                   KEY (`id_parent`,`rank`,`status`),
                   KEY `id_sector` (`id_sector`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}navigation_node_lang (
                   `id_node`     int(11) unsigned NOT NULL,
                   `lang`        char(2) NOT NULL default 'en',
                   `title`       text NOT NULL default '',
                   `short_text`  text NOT NULL default '',
                   `body`        text NOT NULL default '',
                   KEY `id_node` (`id_node`),
                   KEY `lang`    (`lang`),
                   FULLTEXT (`title`,`short_text`,`body`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_node_lock (
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   KEY `id_node`    (`id_node`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_media_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) unsigned NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `tumbnail`     tinyint(1) NOT NULL default 0,
                   `description`  text NOT NULL default '',
                   PRIMARY KEY     (`id_pic`),
                   KEY (`id_node`,`rank`))";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_media_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `description`  text NOT NULL default '',
                   PRIMARY KEY    (`id_file`),
                   KEY (`id_node`,`rank`))";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}navigation_config (
                 `thumb_width`    smallint(4) NOT NULL default 200,
                 `img_size_max`   int(11) NOT NULL default 100000,
                 `file_size_max`  int(11) NOT NULL default 100000,
                 `force_format`   tinyint(1) NOT NULL default 2,
                 `default_format` tinyint(1) NOT NULL default 2,
                 `default_lang`   char(2) NOT NULL default 'en')";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['dbtablesprefix']}navigation_config
                   (`thumb_width`, `img_size_max`,`file_size_max`)
                  VALUES
                   (120,100000,100000)";
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
        $sql = "DROP TABLE IF EXISTS {$data['dbtablesprefix']}navigation_node,
                                     {$data['dbtablesprefix']}navigation_node_lang,
                                     {$data['dbtablesprefix']}navigation_node_lock,
                                     {$data['dbtablesprefix']}navigation_media_pic,
                                     {$data['dbtablesprefix']}navigation_media_file,
                                     {$data['dbtablesprefix']}navigation_config";
        $this->model->dba->query($sql);  
    }
}

?>