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
     * Controller construct
     * It must be called from within a child controller constructor.
     *  parent::__construct();
     *
     * Here we register the modules, create some base class instances
     * and run a broadcast init event to all modules
     */
    function __construct()
    {
        try
        {
            // set error reporting
            error_reporting(SMART_ERROR_REPORTING);

            // create base smart container instance
            $SmartContainer = new SmartContainer;

            // create smart model instance
            $this->model = new SmartModel;

            // create user-defined error handler
            new SmartErrorHandler;

            // check if the modules directory exists
            if(!is_dir(SMART_BASE_DIR . 'modules'))
            {
                throw new SmartInitException("Missing '".SMART_BASE_DIR . "modules' directory.", SMART_DIE);
            }

            // A "common" module must be loaded and registered first
            //
            $mod_common = SMART_BASE_DIR . 'modules/' . SMART_COMMON_MODULE . '/init.php';

            if(file_exists( $mod_common ))
            {
                // include module init file of the common module
                include_once ( $mod_common );
            }
            else
            {
                throw new SmartInitException("The module '/modules/{$mod_common}/init.php'  must be installed!", SMART_DIE);
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
                throw new SmartInitException("The system module '/smart/init.php'  must be installed!", SMART_DIE);
            }

            // check if a module was declared, which should play the last role in a broadcast action event
            if( defined('SMART_LAST_MODULE') )
            {
                $last_module = SMART_LAST_MODULE;
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

                 if ( ($tmp_dirname != SMART_COMMON_MODULE) && @is_dir( SMART_BASE_DIR . 'modules/'.$tmp_dirname) )
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
                    throw new SmartInitException("The 'last' module file '{$mod_init}' is missing!", SMART_DIE);
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
     * Retrieve a new Controller instance.
     *
     * @param string $class Controller class name.
     */
    public static function newInstance($class)
    {
        try
        {
            if (!isset(self::$instance))
            {
                $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new SmartInitException($class_file.' dosent exists', SMART_DIE);
                }
                
                include_once($class_file);
                
                $object = new $class();

                if (!($object instanceof SmartController))
                {
                    throw new SmartInitException($class.' dosent extends SmartController', SMART_DIE);
                }

                // set singleton instance
                self::$instance = $object;

                return $object;
            } 
            else
            {
                $type = get_class(self::$instance);

                throw new SmartInitException('Controller instance exists: '.$type, SMART_DIE);
            }

        } catch (SmartInitException $e)
        {
            $e->performStackTrace();
        } 
    }        
}

?>
