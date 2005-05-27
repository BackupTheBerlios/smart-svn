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
 * First we do some init stuff that is common to all other modules.
 *
 */

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';

// set include path to the PEAR packages
ini_set( 'include_path', SMART_BASE_DIR . 'modules/common/includes/creole' . $tmp_separator . ini_get('include_path') );
unset($tmp_separator); 

// creole db layer
require_once(SMART_BASE_DIR . 'modules/common/includes/creole/creole/Creole.php');

// util class
require_once(SMART_BASE_DIR . 'modules/common/includes/SmartCommonUtil.php');

// session handler class
require_once(SMART_BASE_DIR . 'modules/common/includes/SmartSessionHandler.php');

// session class
require_once(SMART_BASE_DIR . 'modules/common/includes/SmartCommonSession.php');

// get_magic_quotes_gpc
define ( 'SMART_MAGIC_QUOTES', get_magic_quotes_gpc());


class ActionCommonInit extends SmartAction
{
    /**
     * Common Module Version
     */
    const MOD_VERSION = '0.1';    
    
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

        $this->checkModuleVersion();
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
            $this->model->register($row['name'], $row); 
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
    
    /**
     * Check module version and upgrade or install this module if necessairy
     *
     */    
    private function checkModuleVersion()
    {
        // get common module info
        $info = $this->model->getModuleInfo('common');
       
        // need upgrade?
        if(0 != version_compare($info['version'], self::MOD_VERSION))
        {
            // Upgrade this module
            $this->model->action('common','upgrade',array('new_version' => self::MOD_VERSION));           
        }
        
        unset($info);
    }    
}

?>