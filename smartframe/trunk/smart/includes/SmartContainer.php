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
 * Parent container class from which all child conatiners extends
 *
 *
 */
 
class SmartContainer extends SmartObject
{
    /**
     * Array that contains child Container instances
     *
     * @var array $instance
     */
    private static $instance = array();

    /**
     * set object variable
     */        
    public function __set( $name, $value)
    {
        if( (SMART_DEBUG == TRUE) && isset( $this->$name) )
        {
            throw new SmartContainerException( "Value {$name} was previously declared" ,SMART_DIE);            
        }
        
        $this->$name = $value;
    }
    /**
     * get object variable
     */ 
    public function __get( $name )
    {
        if( !isset( $this->$name) )
        {
            return NULL;
        }
        else
        {
            return $this->$name;
        }
    }
    
    /**
     * Retrieve a new Container instance.
     *
     * @param string $class Container class name.
     */
    public static function newInstance($class)
    {
        if (!isset(self::$instance[$class]))
        {
            try
            {
                $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
                if(!@file_exists($class_file))
                {
                    throw new SmartContainerException($class_file.' dosent exists', SMART_DIE);
                }
                
                include_once($class_file);
                
                // the class exists
                $object = new $class();

                if (!($object instanceof SmartContainer))
                {
                    throw new SmartContainerException($class.' dosent extends SmartContainer', SMART_DIE);
                }

                // register and return singleton instance
                return self::$instance[$class] = $object;
            } 
            catch (SmartContainerException $e)
            {
                $e->performStackTrace();
            }                 
        } 
        return self::$instance[$class];
    }        
}

?>