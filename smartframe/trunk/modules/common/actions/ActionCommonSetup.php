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
 
class ActionCommonSetup extends SmartAction
{
    /**
     * Run setup process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        if(!isset($data['rollback']))
        {
            $this->checkFolders();
        }
        
        $data['config']['db']['dbTablePrefix'] = SmartCommonUtil::stripSlashes($data['request']['dbtablesprefix']);    
        $data['config']['db']['dbtype']        = 'mysql';
        $data['config']['db']['dbhost']        = SmartCommonUtil::stripSlashes($data['request']['dbhost']);
        $data['config']['db']['dbuser']        = SmartCommonUtil::stripSlashes($data['request']['dbuser']);
        $data['config']['db']['dbpasswd']      = SmartCommonUtil::stripSlashes($data['request']['dbpasswd']);
        $data['config']['db']['dbname']        = SmartCommonUtil::stripSlashes($data['request']['dbname']);
        
        // set database variables
        $dsn = array( 'phptype'  => $data['config']['db']['dbtype'],
                      'hostspec' => $data['config']['db']['dbhost'],
                      'username' => $data['config']['db']['dbuser'],
                      'password' => $data['config']['db']['dbpasswd'],
                      'database' => $data['config']['db']['dbname']);

        try
        {
            $this->model->db = Creole::getConnection($dsn);
        }
        catch(SQLException $e)
        {
            // if no database connection stop here
            throw new Exception( $e->getNativeError());
        }
        
        // Rollback if there are somme error in other modules setup actions
        if(isset($data['rollback']))
        {
            $this->rollback($data);
            return TRUE;
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_session (
                 `session_id` varchar(32) NOT NULL default '', 
                 `modtime` int(11) NOT NULL default '0',
                 `session_data` text NOT NULL default '',
                 PRIMARY KEY   (`session_id`))";
        $this->model->db->executeUpdate($sql);
            
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_config (
                 `charset` varchar(255) NOT NULL default '',
                 `templates_folder` varchar(255) NOT NULL default '',
                 `views_folder`     varchar(255) NOT NULL default '')";
        $this->model->db->executeUpdate($sql);

        $charset = SmartCommonUtil::stripSlashes($data['request']['charset']);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_config
                 (`charset`,`templates_folder`, `views_folder`)
                VALUES
                 ('{$charset}','templates_default','views_default')";
        $this->model->db->executeUpdate($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_module (
                 `id_module`   int(11) NOT NULL auto_increment,
                 `rank`        int(11) NOT NULL default 0,
                 `name`        varchar(255) NOT NULL default '',
                 `version`     varchar(255) NOT NULL default '',
                 `description` text NOT NULL default '',
                 `release`     text NOT NULL default '',
                 PRIMARY KEY   (`id_module`))";
        $this->model->db->executeUpdate($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                 (`name`, `rank`, `version`, `description`, `release`)
                VALUES
                 ('common', 0,'0.1','This is the base module','DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->db->executeUpdate($sql);            

        return TRUE;
    } 
    /**
     * Check if folders are writeable
     *
     */ 
    private function checkFolders()
    {
        $captcha_folder = SMART_BASE_DIR . 'data/common/captcha';
        if(!is_writeable($captcha_folder))
        {
            throw new Exception('Must be global readable, and writeable by php scripts: '.$captcha_folder);    
        }

        $config_folder = $this->model->config['config_path'];
        if(!is_writeable($config_folder))
        {
            throw new Exception('Must be writeable by php scripts: '.$config_folder);    
        }

        $logs_folder = $this->model->config['logs_path'];
        if(!is_writeable($logs_folder))
        {
            die('Must be writeable by php scripts: '.$logs_folder.'. Correct this and reload the page!');    
        }
        $cache_folder = $this->model->config['cache_path'];
        if(!is_writeable($cache_folder))
        {
            throw new Exception('Must be writeable by php scripts: '.$cache_folder);    
        }      
    }
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback( &$data )
    {
        if(is_resource($this->model->db))
        {
            $sql = "DROP TABLE IF EXISTS 
                        {$data['request']['dbtablesprefix']}common_module,
                        {$data['request']['dbtablesprefix']}common_config";
            $this->model->db->executeUpdate($sql); 
        }
    }     
}

?>