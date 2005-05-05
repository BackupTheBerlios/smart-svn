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
 * Parent cache class from which all child cache classes extends
 *
 *
 */
 
class SmartCache extends SmartObject
{
    /**
     * Retrieve a new Cache instance.
     *
     * @param string $class Cache class name.
     */
    public static function newInstance($class)
    {
        $class_file = SMART_BASE_DIR . 'smart/includes/'.$class.'.php';
                
        if(!@file_exists($class_file))
        {
            throw new SmartCacheException($class_file.' dosent exists', SMART_DIE);
        }
                
        include_once($class_file);
                
        // the class exists
        $object = new $class();

        if (!($object instanceof SmartCache))
        {
            throw new SmartCacheException($class.' dosent extends SmartCache', SMART_DIE);
        }

        // register and return singleton instance
        return $object;              
    }        
}

?>