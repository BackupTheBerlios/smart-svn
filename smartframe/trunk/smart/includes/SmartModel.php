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
     * Database resource
     * @var resource $db
     */    
    public $db;
    
    /**
     * Modules Configuration array
     * @var array $configVars
     */  
    private $configVars = array();

    /**
     * register a module
     *
     */
    public function register( $module, $data )
    {
        if(!isset($this->registeredModules[$module]))
        {
            $this->registeredModules[$module] = $data;
            return TRUE;
        }
        throw new SmartInitException("Duplicate error of module name: '{$module}'", SMART_DIE);
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
     * check if a module is active
     *
     */
    public function is_active( $module )
    {
        if( TRUE == $this->is_module($module) )
        {
            if( $registeredModules[$module]['active'] == TRUE )
            {
                return TRUE;
            }
        }
        return FALSE;
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
        if( !isset($this->configVars[$module]) )
        {
            $this->configVars[$module] = $data;
            return TRUE;
        }
        throw new SmartInitException('Module config array exists: '.$module, SMART_DIE);
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
        if( isset($this->configVars[$module][$var_name]) )
        {
            return $this->configVars[$module][$var_name];
        }
        return NULL;
    }

    /**
     * dynamic call of model action classe (Factory)
     *
     * @param string $action Action name
     * @param array $args Arguments passed to the action ($args[1] passed to the constructor)
     * @return mixed Action result
     */    
    public function __call( $action, &$args )
    {
        $action_match = $this->split_action_name( $action );

        $class_name = 'Action'.$action;

        try
        { 
            if( !isset($this->$class_name) || ($args[2] == TRUE) )
            {
                // path to the modules action class
                $class_file = SMART_BASE_DIR . 'modules/'.strtolower($action_match[1]).'/actions/'.$class_name.'.php';

                if(@file_exists($class_file))
                {
                    include_once($class_file);

                    // force a new instance
                    if( $args[2] == TRUE )
                    {
                        $i = 1;
                        $new_instance = $class_name . $i;
                        
                        while( isset($this->new_instance) )
                        {
                            $i++;
                            $new_instance = $class_name . $i;
                        }
                        
                        // make new instance of the module action class
                        $this->$new_instance = new $class_name( $args[1] );
                        $class_name = & $new_instance;
                    }
                    else
                    {
                        // make instance of the module action class
                        $this->$class_name = new $class_name( $args[1] );
                    }
                }
                else
                {
                    return SMART_NO_ACTION;
                }
            }
            
            // aggregate the model object to the action object
            $this->$class_name->model = $this;
            
            // validate the request
            if( FALSE == $this->$class_name->validate( $args[0] ) )
            {
                return SMART_NO_VALID_ACTION;
            }

            // perform the request if the requested object exists
            return $this->$class_name->perform( $args[0] );
        }
        catch(SmartModelException $e)
        {
            $e->performStackTrace();

            return $e->getCode();
        }
    }

    /**
     * broadcast an action
     *
     * @param string $action Action name
     * @param mixed $data Data passed to the action perfom() and validate() methode
     * @param mixed $data Data passed to the action constructor
     * @param bool $instance Force new action instances
     */ 
    public function broadcast( $action, $data = FALSE, $constructor_data = FALSE, $instance = FALSE )
    {
        foreach($this->registeredModules as $module => $data)
        {
            $mod_action = ucfirst($module).ucfirst($action);
            $this->$mod_action( $data, $constructor_data, $instance );
        }
    }

    /**
     * Split action call into module and action name
     *
     * @param string $action Action call name
     * @return array [0] = Module name / [1] Action name
     */ 
    private function split_action_name( $action )
    {
        if(@preg_match("/([A-Z]{1}[a-z0-9]+)([a-zA-Z0-9]+)/", $action, $action_match))
        {
            return $action_match;
        }
        else
        {
            throw new SmartModelException('Wrong action call name: ' . $action, SMART_NO_ACTION);
        }
    }    
}

?>