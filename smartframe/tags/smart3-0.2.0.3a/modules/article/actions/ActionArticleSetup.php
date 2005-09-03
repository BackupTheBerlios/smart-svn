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
 * Setup action of the article module 
 *
 */
 
class ActionArticleSetup extends SmartAction
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
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}article_article (
                   `id_article`    int(11) unsigned NOT NULL auto_increment,
                   `status`        tinyint(1) NOT NULL default 0,
                   `rank`          smallint(4) unsigned NOT NULL default 0,
                   `lang`          char(2) NOT NULL default 'en', 
                   `pubdate`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `articledate`   datetime NOT NULL default '0000-00-00 00:00:00',
                   `modifydate`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `title`         text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `overtitle`     text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `subtitle`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `header`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`   text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `body`          mediumtext CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `ps`            text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `format`        tinyint(1) NOT NULL default 0,
                   `logo`          varchar(255) NOT NULL default '',
                   `media_folder`  char(32) NOT NULL,                   
                   PRIMARY KEY        (`id_article`),
                   KEY                (`status`,`pubdate`,`modifydate`),
                   KEY `articledate`  (`articledate`),
                   KEY `lang`         (`lang`),
                   KEY `rank`         (`rank`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_changedate (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `changedate`     datetime NOT NULL default '0000-00-00 00:00:00',
                   `status`         tinyint(1) NOT NULL default 0,
                   KEY `changedate` (`changedate`),
                   UNIQUE KEY `id_article` (`id_article`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_index (
                   `id_article` int(11) unsigned NOT NULL default 0,
                   `text1`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text2`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text3`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text4`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',                   
                   UNIQUE KEY `id_article` (`id_article`),
                   FULLTEXT   (`text1`,`text2`,`text3`,`text4`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_lock (
                   `id_article`      int(11) unsigned NOT NULL default 0,
                   `lock_time`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`      int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_node_rel (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `id_node`        int(11) unsigned NOT NULL default 0,
                   KEY `id_article` (`id_article`),
                   KEY `id_node`    (`id_node`))";
        $this->model->dba->query($sql);
  
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_media_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_article`   int(11) unsigned NOT NULL default 0,
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
                   KEY            (`id_article`,`rank`))";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_media_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_article`   int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `title`        text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `description`  text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   PRIMARY KEY    (`id_file`),
                   KEY            (`id_article`,`rank`))";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_config (
                 `thumb_width`     smallint(4) NOT NULL default 120,
                 `img_size_max`    int(11) NOT NULL default 250000,
                 `file_size_max`   int(11) NOT NULL default 2000000,
                 `force_format`    tinyint(1) NOT NULL default 2,
                 `default_format`  tinyint(1) NOT NULL default 2,
                 `default_lang`    char(2) NOT NULL default 'en',
                 `use_users`       tinyint(1) NOT NULL default 0,
                 `use_keywords`    tinyint(1) NOT NULL default 0,
                 `use_articledate` tinyint(1) NOT NULL default 0,
                 `use_changedate`  tinyint(1) NOT NULL default 0,
                 `use_overtitle`   tinyint(1) NOT NULL default 0,
                 `use_subtitle`    tinyint(1) NOT NULL default 0,
                 `use_header`      tinyint(1) NOT NULL default 0,
                 `use_description` tinyint(1) NOT NULL default 0,
                 `use_ps`          tinyint(1) NOT NULL default 0,
                 `use_logo`        tinyint(1) NOT NULL default 0,
                 `use_images`      tinyint(1) NOT NULL default 1,
                 `use_files`       tinyint(1) NOT NULL default 1)";
        $this->model->dba->query($sql);  

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}article_config
                   (`thumb_width`)
                  VALUES
                   (120)";
        $this->model->dba->query($sql);   
  
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `release`)
                  VALUES
                   ('article',
                    'Article Management',
                    3,
                    '0.1',
                    1,
                    'DATE: 25.8.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->dba->query($sql);            
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        $sql = "DROP TABLE IF EXISTS {$data['dbtablesprefix']}article_article,
                                     {$data['dbtablesprefix']}article_index,
                                     {$data['dbtablesprefix']}article_changedate,
                                     {$data['dbtablesprefix']}article_lock,
                                     {$data['dbtablesprefix']}article_node_rel,
                                     {$data['dbtablesprefix']}article_key_rel,
                                     {$data['dbtablesprefix']}article_user_rel,
                                     {$data['dbtablesprefix']}article_media_pic,
                                     {$data['dbtablesprefix']}article_media_file,
                                     {$data['dbtablesprefix']}article_config";
        $this->model->dba->query($sql);  
    }
}

?>