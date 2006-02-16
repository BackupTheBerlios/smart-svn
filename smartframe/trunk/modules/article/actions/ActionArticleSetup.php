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
            return;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}article_article (
                   `id_article`    int(11) unsigned NOT NULL auto_increment,
                   `id_node`       int(11) unsigned NOT NULL default 1,
                   `status`        tinyint(1) NOT NULL default 0,
                   `rank`          smallint(4) unsigned NOT NULL default 0,
                   `lang`          char(2) NOT NULL default 'en', 
                   `pubdate`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `articledate`   datetime NOT NULL default '0000-00-00 00:00:00',
                   `modifydate`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
                   KEY `rank`         (`rank`),
                   KEY `id_node`      (`id_node`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_changedate (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `changedate`     datetime NOT NULL default '0000-00-00 00:00:00',
                   `status`         tinyint(1) NOT NULL default 0,
                   KEY `changedate` (`changedate`),
                   UNIQUE KEY `id_article` (`id_article`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_index (
                   `id_article` int(11) unsigned NOT NULL default 0,
                   `text1`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text2`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text3`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',
                   `text4`      text CHARACTER SET {$data['config']['db']['dbcharset']} NOT NULL default '',                   
                   UNIQUE KEY `id_article` (`id_article`),
                   FULLTEXT   (`text1`,`text2`,`text3`,`text4`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_lock (
                   `id_article`      int(11) unsigned NOT NULL default 0,
                   `lock_time`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`      int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
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
                   KEY            (`id_article`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
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
                   KEY            (`id_article`,`rank`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);        

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_config (
                 `thumb_width`       smallint(4) NOT NULL default 120,
                 `img_size_max`      int(11) NOT NULL default 500000,
                 `file_size_max`     int(11) NOT NULL default 5000000,
                 `force_format`      tinyint(1) NOT NULL default 2,
                 `default_format`    tinyint(1) NOT NULL default 2,
                 `default_lang`      char(2) NOT NULL default 'en',
                 `default_order`     varchar(10) NOT NULL default '',
                 `default_ordertype` varchar(4) NOT NULL default '',
                 `use_article_view`  tinyint(1) NOT NULL default 0,
                 `use_users`         tinyint(1) NOT NULL default 0,
                 `use_keywords`      tinyint(1) NOT NULL default 1,
                 `use_articledate`   tinyint(1) NOT NULL default 0,
                 `use_changedate`    tinyint(1) NOT NULL default 0,
                 `use_overtitle`     tinyint(1) NOT NULL default 0,
                 `use_subtitle`      tinyint(1) NOT NULL default 0,
                 `use_header`        tinyint(1) NOT NULL default 0,
                 `use_description`   tinyint(1) NOT NULL default 0,
                 `use_ps`            tinyint(1) NOT NULL default 0,
                 `use_logo`          tinyint(1) NOT NULL default 0,
                 `use_images`        tinyint(1) NOT NULL default 1,
                 `use_files`         tinyint(1) NOT NULL default 1) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);  

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_keyword (
                   `id_article`     int(11) unsigned NOT NULL default 0,
                   `id_key`         int(11) unsigned NOT NULL default 0,
                   KEY `id_article` (`id_article`),
                   KEY `id_key`     (`id_key`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_node_view_rel (
                   `id_view`      int(11) unsigned NOT NULL default 0,
                   `id_node`      int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_node` (`id_node`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_view_rel (
                   `id_view`      int(11) unsigned NOT NULL default 0,
                   `id_article`   int(11) unsigned NOT NULL default 0,
                   UNIQUE KEY `id_article` (`id_article`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}article_view (
                   `id_view`      int(11) unsigned NOT NULL auto_increment,
                   `name`         varchar(255) NOT NULL default '',
                   PRIMARY KEY    (`id_view`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);      

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}article_config
                   (`thumb_width`,`default_order`,`default_ordertype`)
                  VALUES
                   (120,'rank','asc')";
        $this->model->dba->query($sql);   
  
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                  VALUES
                   ('article',
                    'Article Management',
                    3,
                    '0.2',
                    1,
                    40,
                    'DATE: 28.12.2005 AUTHOR: Armand Turpel <framework@smart3.org>')";
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
                                     {$data['dbtablesprefix']}article_media_pic,
                                     {$data['dbtablesprefix']}article_media_file,
                                     {$data['dbtablesprefix']}article_config,
                                     {$data['dbtablesprefix']}article_view,
                                     {$data['dbtablesprefix']}article_view_rel,
                                     {$data['dbtablesprefix']}article_node_view_rel";
        $this->model->dba->query($sql);  
    }
}

?>