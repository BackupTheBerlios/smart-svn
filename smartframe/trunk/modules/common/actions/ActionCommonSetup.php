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
        
        $data['config']['db']['dbTablePrefix'] = $data['dbtablesprefix'];    
        $data['config']['db']['dbhost']        = $data['dbhost'];
        $data['config']['db']['dbport']        = $data['dbport'];
        $data['config']['db']['dbuser']        = $data['dbuser'];
        $data['config']['db']['dbpasswd']      = $data['dbpasswd'];
        $data['config']['db']['dbname']        = $data['dbname'];
        $data['config']['db']['dbcharset']     = $this->mysqlEncoding( $data['charset'] );

        try
        {
            $this->model->dba = new DbMysql( $data['config']['db']['dbhost'] ,
                                              $data['config']['db']['dbuser'],
                                              $data['config']['db']['dbpasswd'],
                                              $data['config']['db']['dbname'],
                                              $data['config']['db']['dbport']);
                                              
            $this->model->dba->connect();  
            $this->model->dba->query("SET NAMES '{$data['config']['db']['dbcharset']}'"); 
        }
        catch(SmartDbException $e)
        {
            // if no database connection stop here
            throw new Exception("Connection to the database (server?) fails. Please check connection data!",0);
        }
        
        // Rollback if there are somme error in other modules setup actions
        if(isset($data['rollback']))
        {
            $this->rollback($data);
            return TRUE;
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_session (
                 `session_id`   varchar(32) NOT NULL default '', 
                 `modtime`      int(11) NOT NULL default '0',
                 `session_data` text NOT NULL default '',
                 PRIMARY KEY   (`session_id`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);
            
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_config (
                 `charset`          varchar(50) NOT NULL default '',
                 `templates_folder` varchar(255) NOT NULL default '',
                 `views_folder`     varchar(255) NOT NULL default '',
                 `disable_cache`    tinyint(1) NOT NULL default 1,
                 `textarea_rows`    tinyint(2) NOT NULL default 25,
                 `max_lock_time`    int(11) NOT NULL default 7200,
                 `rejected_files`   text NOT NULL default '') 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_config
                 (`charset`,`templates_folder`, `views_folder`,`rejected_files`)
                VALUES
                 ('{$data['charset']}','{$this->model->config['default_template_folder']}','{$this->model->config['default_view_folder']}','.php,.php3,.php4,.php5,.phps,.pl,.py')";
        $this->model->dba->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_module (
                 `id_module`   int(11) NOT NULL auto_increment,
                 `rank`        smallint(3) NOT NULL default 0,
                 `name`        varchar(255) NOT NULL default '',
                 `alias`       varchar(255) NOT NULL default '',
                 `version`     varchar(255) NOT NULL default '',
                 `visibility`  tinyint(1) NOT NULL default 0,
                 `perm`        tinyint(3) NOT NULL default 0,
                 `release`     text NOT NULL default '',
                 PRIMARY KEY   (`id_module`)) 
                ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci";
        $this->model->dba->query($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                 (`name`, `alias`, `rank`, `version`, `visibility`, `perm`, `release`)
                VALUES
                 ('common','', 0,'0.1',0,10,'DATE: 6.5.2005 AUTHOR: Armand Turpel <framework@smart3.org>')";
        $this->model->dba->query($sql);            

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
     * Get mysql charset encoding
     * 
     * @param string $charset 
     * @return string Mysql encoding
     */    
    public function mysqlEncoding( $charset )
    {
        $_charset = array("iso-8859-1"   => 'latin1',
                          "iso-8859-2"   => 'latin2',
                          "iso-8859-13"  => 'latin7',
                          "iso-8859-7"   => 'greek',
                          "iso-8859-8"   => 'hebrew',
                          "iso-8859-9"   => 'latin5',
                          "utf-8"        => 'utf8',
                          "windows-1250" => 'cp1250',
                          "windows-1256" => 'cp1256',
                          "windows-1257" => 'cp1257',
                          "windows-1251" => 'cp1251',
                          "GB2312"       => 'gb2312',
                          "Big5"         => 'big5',
                          "EUC-KR"       => 'euckr',
                          "TIS-620"      => 'tis620',
                          "EUC-JP"       => 'ujis',
                          "KOI8-U"       => 'koi8u',
                          "KOI8-R"       => 'koi8r');
                          
        if(isset($_charset[$charset])) 
        {
            return $_charset[$charset];
        }
        else
        {
            throw new SmartModelException('Charset not supported: '.$charset);
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
                        {$data['dbtablesprefix']}common_module,
                        {$data['dbtablesprefix']}common_config,
                        {$data['dbtablesprefix']}common_session";
            $this->model->dba->query($sql); 
        }
        
        if(file_exists($this->model->config['config_path'] . 'dbConnect.php'))
        {
            @unlink($this->model->config['config_path'] . 'dbConnect.php');
        }
    }     
}

?>