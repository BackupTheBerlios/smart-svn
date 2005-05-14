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
        
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}user_user (
                   `id_user`      int(11) NOT NULL auto_increment,
                   `login`        varchar(20) NOT NULL default '',
                   `passwd`       char(32) NOT NULL,
                   `role`         int(11) NOT NULL default 10,
                   `status`       int(11) NOT NULL default 1,
                   `lock`         int(11) NOT NULL default 0,
                   `lock_time`    datetime NOT NULL default '0000-00-00 00:00:00',
                   `access`       datetime NOT NULL default '0000-00-00 00:00:00',
                   `name`         varchar(255) NOT NULL default '',
                   `lastname`     varchar(255) NOT NULL default '',
                   `email`        varchar(255) NOT NULL default '',
                   `website`      varchar(255) NOT NULL default '',
                   `description`  text NOT NULL default '',
                   `logo`         varchar(255) NOT NULL default '',
                   `media_folder` char(32) NOT NULL,
                   PRIMARY KEY     (`id_user`),
                   KEY `login`     (`login`),
                   KEY `passwd`    (`passwd`),
                   KEY `role`      (`role`),
                   KEY `status`    (`status`),
                   KEY `lock`      (`lock`),
                   KEY `lock_time` (`lock_time`),
                   KEY `access`    (`access`))";
        $this->model->db->executeUpdate($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}user_register (
                   `id_user`   int(11) NOT NULL default 0,
                   `md5_str`   char(32) NOT NULL default '',
                   `confirmed` int(11) NOT NULL default 0,
                   `reg_time`  datetime NOT NULL default '0000-00-00 00:00:00')";
        $this->model->db->executeUpdate($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}user_user
                   (`login`, `name`, `lastname`, `passwd`,`status`, `role`)
                  VALUES
                   (?,?,?,?,?,?)";
        $stmt = $this->model->db->prepareStatement($sql);  

        $stmt->setString(1, SmartCommonUtil::stripSlashes($data['request']['syslogin']));
        $stmt->setString(2, SmartCommonUtil::stripSlashes($data['request']['sysname']));
        $stmt->setString(3, SmartCommonUtil::stripSlashes($data['request']['syslastname']));
        $stmt->setString(4, md5(SmartCommonUtil::stripSlashes($data['request']['syspassword1'])));
        $stmt->setInt(5, 2);
        $stmt->setInt(6, 20);
        
        $stmt->executeUpdate();
        
        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                   (`name`, `rank`, `version`, `description`, `release`)
                  VALUES
                   (?,?,?,?,?)";
        $stmt = $this->model->db->prepareStatement($sql);            

        $stmt->setString(1, 'user');
        $stmt->setInt(2,    2);
        $stmt->setString(3, '0.1');
        $stmt->setString(4, 'This is the user module');
        $stmt->setString(5, 'DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>');

        $stmt->executeUpdate();
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( & $data )
    {
        $sql = "DROP TABLE IF EXISTS 
                    {$data['request']['dbtablesprefix']}user_user,
                    {$data['request']['dbtablesprefix']}user_register";
        $this->model->db->executeUpdate($sql);  
    }    
}

?>