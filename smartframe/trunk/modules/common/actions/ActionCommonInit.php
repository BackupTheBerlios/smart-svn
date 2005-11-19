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
 * Init action of the common module 
 *
 * First we do some init stuff that is common to all other modules.
 *
 */

// util class
include_once(SMART_BASE_DIR . 'modules/common/includes/SmartCommonUtil.php');

// session handler class
require_once(SMART_BASE_DIR . 'modules/common/includes/SmartSessionHandler.php');

// session class
require_once(SMART_BASE_DIR . 'modules/common/includes/SmartCommonSession.php');

// get_magic_quotes_gpc
define ( 'SMART_MAGIC_QUOTES', get_magic_quotes_gpc());

// set include path to additional pear packages
ini_set( 'include_path', '.' . PATH_SEPARATOR . SMART_BASE_DIR . 'modules/common/includes/PEAR' . PATH_SEPARATOR . ini_get('include_path') );

class ActionCommonInit extends SmartAction
{
    /**
     * Common Module Version
     */
    const MOD_VERSION = '0.2';    
    
    /**
     * Run init process of this module
     *
     */
    public function perform( $data = FALSE )
    {
        $mysqlExtension = $this->getMySqlExtensionType();
        // db class
        require_once(SMART_BASE_DIR . 'modules/common/includes/Smart'.$mysqlExtension.'.php');
             
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
        $this->config['dbcharset']     = $db['dbcharset'];

        try
        {
            $this->model->dba = new DbMysql( $db['dbhost']  ,$db['dbuser'],
                                             $db['dbpasswd'],$db['dbname'] );
                                              
            //$dbaOptions = array(MYSQLI_OPT_CONNECT_TIMEOUT => 5);
            $this->model->dba->connect();  
            $this->model->dba->query("SET NAMES '{$db['dbcharset']}'");        
        }
        catch(SmartDbException $e)
        {
            // if no database connection stop here
            throw new SmartModelException;
        }

        // load module descriptions into config array   
        $this->loadModulesInfo();         
        // check for module upgrade
        $this->checkModuleVersion();   
       
        // set base url, except if the cli controller is used
        if($this->config['controller_type'] != 'cli')
        {
            $this->model->baseUrlLocation = $this->base_location();
        }
       
        // set session handler
        $this->model->sessionHandler = new SmartSessionHandler( $this->model->dba, $this->config['dbTablePrefix'] );
              
        // load global config variables of the common module   
        $this->loadConfig(); 

        // init and start session
        $this->startSession();

        // enable zlib output compression
        if($this->config['output_compression'] == TRUE)
        {
            ini_set('zlib.output_compression',       '1');     
            ini_set('zlib.output_compression_level', $this->config['output_compression_level']);
            ini_set('zlib.output_handler',           '');
        }
        
        // set charset
        ini_set( "default_charset",$this->config['charset']);
        @header( "Content-type: text/html; charset={$this->config['charset']}" );         
    } 

    /**
     * Load module descriptions in $this->config['module']['name']
     *
     */    
    private function loadModulesInfo()
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}common_module ORDER BY `rank` ASC";
        
        $rs = $this->model->dba->query($sql);
        
        $this->config['module'] = array();
        
        while($row = $rs->fetchAssoc())
        {
            $this->config['module'][$row['name']] = $row;
            $this->model->register($row['name'], $row); 
        } 
    }

    /**
     * Load config values
     *
     */    
    private function loadConfig()
    {
        $sql = "SELECT SQL_CACHE * FROM {$this->config['dbTablePrefix']}common_config";
        
        $rs = $this->model->dba->query($sql);
        
        $fields = $rs->fetchAssoc();

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
    
    /**
     * Get mysql extension type
     *
     */    
    private function getMySqlExtensionType()
    {
        if( function_exists('mysqli_init') )
        {
            return 'MySqli';
        }
        elseif( function_exists('mysql_connect') )
        {
            return 'MySql';
        } 
        else
        {
            throw new SmartModelException( "It seem that there isnt the php extension 'mysql' nor 'mysqli' installed on your system. Check this!" );        
        }
    }    
    
    /**
     * Get the base location
     *
     * @return string base location
     */
    private function base_location()
    {
        $base_dirname = dirname($_SERVER['PHP_SELF']);

        if($base_dirname == '/' )
            $base_dirname = '';

        if(isset($_SERVER['SCRIPT_URI']))
        {
            $referer = $_SERVER['SCRIPT_URI'];
        }
        elseif(isset($_SERVER["HTTP_REFERER"]))
        {
            $referer = $_SERVER["HTTP_REFERER"];
        }
        elseif(isset($_ENV["HTTPS"]))
        {
            $referer = $_ENV["HTTPS"];
        }
        else
        {
            $referer = 'http://';
        }
        
        // Build the http protocol referrer
        //
        if(preg_match("/^http([s]?)/i", $referer, $tmp))
        {
            $http = 'http' . $tmp[1] . '://';
        }
        elseif(preg_match("/^on$/i", $referer))
        {
            $http = 'https://';
        }    
        else
        {
            $http = $referer;
        }    
        
        return $http . $_SERVER['HTTP_HOST'] . $base_dirname;
    }   
    
    /**
     * init and start session
     *
     */    
    private function startSession()
    {
        ini_set('session.gc_probability', 10);
        ini_set('session.gc_maxlifetime', $this->config['session_maxlifetime']);
        $this->model->session = new SmartCommonSession();
        // delete only expired session of the current user
        // this isnt the session garbage collector 
        $this->model->action('common', 'sessionDeleteExpired');   
    }
}

?>