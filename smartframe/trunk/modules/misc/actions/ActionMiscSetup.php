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
 * Setup action of the misc module 
 *
 */
 
class ActionMiscSetup extends SmartAction
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
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}misc_text (
                   `id_text`       int(11) unsigned NOT NULL auto_increment,
                   `status`        tinyint(1) NOT NULL default 0,
                   `title`         text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`   text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `body`          mediumtext CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `format`        tinyint(1) NOT NULL default 0,
                   `media_folder`  char(32) NOT NULL,
                   PRIMARY KEY     (`id_text`),
                   KEY             (`status`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}misc_text_lock (
                   `id_text`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   KEY `id_text`    (`id_text`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}misc_text_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_text`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) unsigned NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `width`        smallint(4) unsigned NOT NULL default 0,
                   `height`       smallint(4) unsigned NOT NULL default 0,
                   `tumbnail`     tinyint(1) NOT NULL default 0,
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   PRIMARY KEY    (`id_pic`),
                   KEY            (`id_text`,`rank`))";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}misc_text_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_text`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   PRIMARY KEY    (`id_file`),
                   KEY            (`id_text`,`rank`))";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}misc_config (
                 `thumb_width`    smallint(4) NOT NULL default 120,
                 `img_size_max`   int(11) NOT NULL default 100000,
                 `file_size_max`  int(11) NOT NULL default 100000,
                 `force_format`   tinyint(1) NOT NULL default 2,
                 `misc_format` tinyint(1) NOT NULL default 2,
                 `misc_lang`   char(2) NOT NULL default 'en',
                 `use_images`     tinyint(1) NOT NULL default 1,
                 `use_files`      tinyint(1) NOT NULL default 1)";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['dbtablesprefix']}misc_config
                   (`thumb_width`, `img_size_max`,`file_size_max`)
                  VALUES
                   (120,100000,100000)";
        $this->model->dba->query($sql); 
 
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                 (`name`, `alias`, `rank`, `version`, `visibility`, `release`)
                VALUES
                 ('misc','Misc Content Management',5,'0.1',1,'DATE: 30.7.2005 AUTHOR: Armand Turpel <framework@smart3.org>')";
        $this->model->dba->query($sql);            

        return TRUE;
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        $sql = "DROP TABLE IF EXISTS {$data['dbtablesprefix']}misc_text,
                                     {$data['dbtablesprefix']}misc_text_lock,
                                     {$data['dbtablesprefix']}misc_text_pic,
                                     {$data['dbtablesprefix']}misc_text_file,
                                     {$data['dbtablesprefix']}misc_config";
        $this->model->dba->query($sql);  
    }    
}

?>