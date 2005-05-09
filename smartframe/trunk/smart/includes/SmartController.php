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

/*
 * The base controller class, from which all other controllers extends
 *
 */

class SmartController extends SmartObject
{
    /**
     * Controller object
     *
     * @var object $instance
     */ 
    private static $instance = null;

    /**
     * Model object
     *
     * @var object $model
     */        
    public $model;
    
    /**
     * Main Smart Config array
     *
     * @var array $config
     */        
    public $config;    

    /**
     * Controller construct
     * It must be called from within a child controller constructor.
     *  parent::__construct();
     *
     * Here we register the modules, create some base class instances
     * and run a broadcast init event to all modules
     * 
     * @param array $config Main Smart config array
     */
    function __construct( & $config )
    {
        try
        {
            // set error reporting
            error_reporting( E_ALL | E_STRICT);
            
            // set reference to the config array
            $this->config = $config;

            // create base smart container instance
            $SmartContainer = new SmartContainer( $config );

            // create smart model instance
            $this->model = new SmartModel( $config );          

            // create user-defined error handler
            new SmartErrorHandler( $config );

            // check if the modules directory exists
            if(!is_dir(SMART_BASE_DIR . 'modules'))
            {
                throw new SmartInitException("Missing '".SMART_BASE_DIR . "modules' directory.");
            }

            // A "common" module must be loaded and registered first
            //
            $mod_common = SMART_BASE_DIR . 'modules/' . $this->config['base_module'] . '/init.php';

            if(file_exists( $mod_common ))
            {
                // include module init file of the common module
                include_once ( $mod_common );
            }
            else
            {
                throw new SmartInitException("The module '/modules/{$mod_common}/init.php'  must be installed!");
            }

            // Include smart system init.php
            //
            $mod_system = SMART_BASE_DIR . 'smart/init.php';

            if(file_exists( $mod_system ))
            {
                // include module init file of the common module
                include_once ( $mod_system );
            }
            else
            {
                throw new SmartInitException("The system module '/smart/init.php'  must be installed!");
            }

            // check if a module was declared, which should play the last role in a broadcast action event
            if( isset($this->config['last_module']) )
            {
                $last_module = $this->config['last_module'];
            }
            else
            {
                $last_module = FALSE;
            }

            // include other modules init files
            //
            $tmp_directory =& dir( SMART_BASE_DIR . 'modules');
    
            while (FALSE != ($tmp_dirname = $tmp_directory->read()))
            {
                if ( ( $tmp_dirname == '.' ) || ( $tmp_dirname == '..' ) || ( $tmp_dirname == '.svn' ) )
                {
                    continue;
                }
                // dont load last module here
                if( $tmp_dirname == $last_module )
                {
                    continue;
                }

                 if ( ($tmp_dirname != $this->config['base_module']) && @is_dir( SMART_BASE_DIR . 'modules/'.$tmp_dirname) )
                 {
                      // include module init file
                      $mod_init = SMART_BASE_DIR . 'modules/' . $tmp_dirname . '/init.php';
  
                      if ( @is_file( $mod_init ) )
                      {
                          include_once ($mod_init);
                      }
                }
            }
  
            $tmp_directory->close();

            // Load last module
            if( $last_module != FALSE )
            {
                // include module init file
                $mod_init = SMART_BASE_DIR . 'modules/' . $last_module . '/init.php';
  
                if ( @is_file( $mod_init ) )
                {
                    include_once ($mod_init);
                }
                else
                {
                    throw new SmartInitException("The 'last' module file '{$mod_init}' is missing!");
                }
            }

            unset($tmp_directory);
            unset($mod_init);
            unset($mod_common);
            unset($last_module);
                         
        } catch(SmartInitException $e)
        {
           $e->performStackTrace();
        }
    }
    /**
     * Set exception flags.
     *
     * @param object $e Exception 
     */
    protected function setExceptionFlags( $e )
    {
        $e->flag = array('debug'          => $this->config['debug'],
                         'logs_path'      => $this->config['logs_path'],
                         'message_handle' => $this->config['message_handle']);  
        return;
    }

    /**
     * Retrieve a new Controller instance.
     *
     * @param string $class Controller class name.
     * @param array $config Main Smart config array
     */
    public static function newInstance($class, & $config)
    {
        try
        {
            if (!isset(self::$instance))
            {
                $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new SmartInitException($class_file.' dosent exists');
                }
                
                include_once($class_file);
                
                $object = new $class( $config );

                if (!($object instanceof SmartController))
                {
                    throw new SmartInitException($class.' dosent extends SmartController');
                }

                // set singleton instance
                self::$instance = $object;
 
                return $object;
            } 
            else
            {
                $type = get_class(self::$instance);

                throw new SmartInitException('Controller instance exists: '.$type);
            }

        } catch (SmartInitException $e)
        {
            $e->performStackTrace();
        } 
    }        
}

?>