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
 * Init action of the common module 
 *
 */

class ActionCommonInit extends SmartAction
{
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
                    
        // Check if a setup was successfull done else launch setup > 'setup' module
        if(file_exists($this->config['config_path'] . 'dbConnect.php'))
        {
            include_once($this->config['config_path'] . 'dbConnect.php');
        }
        else
        {
            throw new SmartForwardAdminViewException( $this->config['setup_module'] );        
        }

        // set db config vars
        $this->config['dbtype']        = 'mysql';
        $this->config['dbhost']        = $db['dbhost'];
        $this->config['dbuser']        = $db['dbuser'];
        $this->config['dbpasswd']      = $db['dbpasswd'];
        $this->config['dbname']        = $db['dbname'];
        $this->config['dbTablePrefix'] = $db['dbTablePrefix'];

        // set database variables
        $dsn = array( 'phptype'  => 'mysql',
                      'hostspec' => $db['dbhost'],
                      'username' => $db['dbuser'],
                      'password' => $db['dbpasswd'],
                      'database' => $db['dbname']);

        try
        {
            $this->model->db = Creole::getConnection($dsn);
        }
        catch(SQLException $e)
        {
            // if no database connection stop here
            throw new SmartModelException( $e->getNativeError() );
        }
       
        // set base url
        $this->model->baseUrlLocation = SmartCommonUtil::base_location();
       
        // set session handler
        $this->model->sessionHandler = new SmartSessionHandler( $this->model->db, $this->config['dbTablePrefix'] );
       
        // start session
        $this->model->session = new SmartCommonSession();
       
        // load global config variables of the common module   
        $this->loadConfig(); 
        
        // load module descriptions into config array   
        $this->loadModulesInfo();         

    } 

    /**
     * Load module descriptions in $this->config['module']['name']
     *
     */    
    private function loadModulesInfo()
    {
        $sql = "SELECT * FROM {$this->config['dbTablePrefix']}common_module ORDER BY `rank` ASC";
        
        $rs = $this->model->db->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
        
        $this->config['module'] = array();
        
        while($rs->next())
        {
            $row = array();
            $row = $rs->getRow();
            $this->model->update($row['name'], $row); 
        } 
    }

    /**
     * Load config values
     *
     */    
    private function loadConfig()
    {
        $sql = "SELECT * FROM {$this->config['dbTablePrefix']}common_config";
        
        $rs = $this->model->db->executeQuery($sql, ResultSet::FETCHMODE_ASSOC);
        
        $rs->first();
        $fields = $rs->getRow();

        foreach($fields as $key => $val)
        {
            $this->config[$key] = $val;      
        } 
    }
}

?>