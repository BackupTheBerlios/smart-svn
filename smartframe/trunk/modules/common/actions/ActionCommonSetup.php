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
            throw new Exception( $e->getNativeError(), 1 );
        }
        
        // Rollback if there are somme error in other modules setup actions
        if(isset($data['rollback']))
        {
            $this->rollback();
            return TRUE;
        }
            
        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_config (
                 `charset` varchar(255) NOT NULL default '',
                 `templates_folder` varchar(255) NOT NULL default '',
                 `views_folder`     varchar(255) NOT NULL default '')";
        $this->model->db->executeUpdate($sql);

        $charset = SmartCommonUtil::stripSlashes($data['request']['charset']);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_config
                 (`charset`,`templates_folder`, `views_folder`)
                VALUES
                 ('{$charset}','templates_default/','views_default/')";
        $this->model->db->executeUpdate($sql);

        $sql = "CREATE TABLE IF NOT EXISTS {$data['config']['db']['dbTablePrefix']}common_module (
                 `id_module`   int(11) NOT NULL auto_increment,
                 `name`        varchar(255) NOT NULL default '',
                 `version`     varchar(255) NOT NULL default '',
                 `description` text NOT NULL default '',
                 `release`     text NOT NULL default '',
                 PRIMARY KEY   (`id_module`))";
        $this->model->db->executeUpdate($sql);

        $sql = "INSERT INTO {$data['config']['db']['dbTablePrefix']}common_module
                 (name, version, description, release)
                VALUES
                 ('common','0.1','This is the base module','DATE: 6.5.2005 AUTHOR: Armand Turpel <smart@open-publisher.net>')";
        $this->model->db->executeUpdate($sql);            

        return TRUE;
    } 
    
    /**
     * Rollback setup
     * Delete db tables of this module 
     *
     */    
    public function rollback()
    {
        if(is_resource($this->model->db))
        {
            $sql = "DROP TABLE IF EXISTS 
                        {$data['config']['db']['dbTablePrefix']}common_module,
                        {$data['config']['db']['dbTablePrefix']}common_config";
            $this->model->db->executeUpdate($sql); 
        }
    }     
}

?>