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
 * Setup action of the user module 
 *
 */
 
class ActionUserSetup extends SmartAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(isset($data['rollback']))
        {
            $this->rollback( $data );
            return TRUE;
        }
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_user (
                   `id_user`      int(11) unsigned NOT NULL auto_increment,
                   `login`        varchar(20) NOT NULL default '',
                   `passwd`       char(32) NOT NULL,
                   `role`         tinyint(3) unsigned NOT NULL default 10,
                   `status`       tinyint(1) NOT NULL default 1,
                   `name`         varchar(255) NOT NULL default '',
                   `lastname`     varchar(255) NOT NULL default '',
                   `email`        varchar(255) NOT NULL default '',
                   `description`  text NOT NULL default '',
                   `logo`         varchar(255) NOT NULL default '',
                   `media_folder` char(32) NOT NULL,
                   PRIMARY KEY     (`id_user`),
                   KEY (`login`,`passwd`,`status`),
                   KEY (`role`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_access (
                   `id_user`   int(11) unsigned NOT NULL default 0,
                   `access`    datetime NOT NULL default '0000-00-00 00:00:00',
                   UNIQUE KEY `id_user` (`id_user`),
                   KEY `access`         (`access`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_lock (
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `by_id_user`   int(11) unsigned NOT NULL default 0,
                   KEY `id_user`    (`id_user`),
                   KEY `lock_time`  (`lock_time`),
                   KEY `by_id_user` (`by_id_user`))";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_media_pic (
                   `id_pic`       int(11) unsigned NOT NULL auto_increment,
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) unsigned NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `tumbnail`     tinyint(1) NOT NULL default 0,
                   `description`  text NOT NULL default '',
                   PRIMARY KEY     (`id_pic`),
                   KEY (`id_user`,`rank`))";
        $this->model->dba->query($sql);
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['dbtablesprefix']}user_media_file (
                   `id_file`      int(11) unsigned NOT NULL auto_increment,
                   `id_user`      int(11) unsigned NOT NULL default 0,
                   `file`         varchar(255) NOT NULL default '',
                   `size`         int(11) NOT NULL default 0,
                   `mime`         varchar(255) NOT NULL default '',
                   `rank`         smallint(4) unsigned NOT NULL default 0,
                   `description`  text NOT NULL default '',
                   PRIMARY KEY     (`id_file`),
                   KEY `id_user`   (`id_user`,`rank`))";
        $this->model->dba->query($sql);        

        $sql = "INSERT INTO {$data['dbtablesprefix']}user_user
                   (`login`, `passwd`,`status`, `role`)
                  VALUES
                   ('superuser',?,2,10)";
        $this->model->dba->prepare($sql); 

        // register a superuser
        $superuser_passwd = md5($data['superuser_passwd']);

        $this->model->dba->bindParam(array("s",$superuser_passwd)); 
        
        $stmt = $this->model->dba->execute();

        // insert module info data
        $sql = "INSERT INTO {$data['dbtablesprefix']}common_module
                   (`name`, `alias`, `rank`, `version`, `visibility`, `release`)
                  VALUES
                   ('user','User Management',3,'0.1',1,'DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        
        $this->model->dba->query($sql);         
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( & $data )
    {
        $sql = "DROP TABLE IF EXISTS 
                     {$data['dbtablesprefix']}user_user,
                     {$data['dbtablesprefix']}user_access,
                     {$data['dbtablesprefix']}user_lock,
                     {$data['dbtablesprefix']}user_media_pic,
                     {$data['dbtablesprefix']}user_media_file";
        $this->model->dba->query($sql);  
    }    
}

?>