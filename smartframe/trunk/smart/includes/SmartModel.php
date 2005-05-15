<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Smart Model Class
 *
 *
 */
class SmartModel extends SmartObject
{
    /**
     * Registered modules
     * @var array $registered_modules
     */
    private $registeredModules = array();

    /**
     * Availaible modules (folders in the module dir)
     * @var array $availaibleModules
     */
    private $availaibleModules = array();
    
    /**
     * Database resource
     * @var resource $db
     */    
    public $db;
    
    /**
     * Main Smart configuration array
     * @var array $config
     */  
    public $config;

    /**
     * Session
     * @var object $session
     */    
    public $session;
    
    /**
     * Session Handler
     * @var object $sessionHandler
     */    
    public $sessionHandler;    

    /**
     * Model constructor
     * 
     * @param array $config Main Smart config array
     */
    function __construct( & $config )
    {
        $this->config = $config;
    }

    /**
     * return module array
     *
     */
    public function & getModules()
    {
        return $this->registeredModules;
    }

    /**
     * return Availaible Modules array
     *
     */
    public function & getAvailaibleModules()
    {
        return $this->availaibleModules;
    }

    /**
     * register a module
     *
     */
    public function register( $module, $data  )
    {
        if(!isset($this->registeredModules[$module]))
        {
            $this->registeredModules[$module] = $data;
            return TRUE;
        }
        throw new SmartModelException("Duplicate error of module name: '{$module}'");
    }  

    /**
     * register a module
     *
     */
    public function init( $module  )
    {
        $this->availaibleModules[] = $module;
    }  

    /**
     * check if a module was registered
     *
     */
    public function is_module( $module )
    {
        if( !isset($registeredModules[$module]) )
        {
            return FALSE;
        }
        return TRUE;
    }  
    
    /**
     * check if a module is visible (if it has an admin web interface)
     *
     */
    public function is_visible( $module )
    {
        if( TRUE == $this->is_module($module) )
        {
            if( TRUE == $this->is_active($module) )
            {
                if( $registeredModules[$module]['visibility'] == TRUE )
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    } 
    
    /**
     * add module related config variable array
     *
     * @param string $module Module name
     * @param array data Associative array
     */     
    public function addConfigVar( $module, & $data )
    {
        if( !isset($this->config[$module]) )
        {
            $this->config[$module] = $data;
            return TRUE;
        }
        throw new SmartInitException('Module config array exists: '.$module);
    }
    
    /**
     * get config variables
     *
     * @param string $module Module name
     * @param string $var_name Variable name
     * @return mixed Null if the requested variable dosent exists
     */     
    public function getConfigVar( $module = FALSE, $var_name = FALSE )
    {
        if( isset($this->config[$module][$var_name]) )
        {
            return $this->config[$module][$var_name];
        }
        return NULL;
    }

    /**
     * dynamic call of model action classe (Factory)
     *
     * @param string $module Module name
     * @param string $action Action name
     * @param mixed $data Data passed to the action
     * @param mixed $constructor_data Data passed to the action constructor
     * @param bool $force_instance If true force a new instance even if it exists
     * @return bool True on success else false
     */    
    public function action( $module, $action, $data = FALSE, $constructor_data = FALSE, $force_instance = FALSE )
    {
        $class_name = 'Action'.ucfirst($module).ucfirst($action);
        
        if( !isset($this->$class_name) || ($force_instance == TRUE) )
        {
            // path to the modules action class
            $class_file = SMART_BASE_DIR . 'modules/'.$module.'/actions/'.$class_name.'.php';

            if(@file_exists($class_file))
            {
                include_once($class_file);

                // force a new instance
                if( $force_instance == TRUE )
                {
                    $i = 1;
                    $new_instance = $class_name . $i;
                        
                    while( isset($this->$new_instance) )
                    {
                        $i++;
                        $new_instance = $class_name . $i;
                    }
                        
                    // make new instance of the module action class
                    $this->$new_instance = new $class_name( $constructor_data );
                    $class_name = & $new_instance;
                }
                else
                {
                    // make instance of the module action class
                    $this->$class_name = new $class_name( $constructor_data );
                }
            }
            else
            {
                return SMART_NO_ACTION;
            }
        }
            
        // aggregate the model object to the action object
        $this->$class_name->model = $this;

        // Aggregate the main configuration array
        $this->$class_name->config = & $this->config;
            
        // validate the request
        if( FALSE == $this->$class_name->validate( $data ) )
        {
            return FALSE;
        }

        // perform the request if the requested object exists
        return $this->$class_name->perform( $data );
    }

    /**
     * broadcast an action event
     *
     * @param string $action Action name
     * @param mixed $data Data passed to the action perfom() and validate() methode
     * @param mixed $data Data passed to the action constructor
     * @param bool $force_instance Force new action instances
     */ 
    public function broadcast( $action, $data = FALSE, $constructor_data = FALSE, $force_instance = FALSE )
    {
        $_modules = $this->getAvailaibleModules();
        
        foreach($_modules as $module)
        {
            $this->action( $module, $action, $data, $constructor_data, $force_instance );
        }
    } 
}

?>