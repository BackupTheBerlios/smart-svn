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
 * common_cache_save class 
 *
 */
 
class common_cache_save
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function common_cache_save()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Cache data
     *
     * @param array $data
     */
    function perform( & $data )
    {
        // Include cache and create instance
        if(!is_object($this->B->cache))
        {
            trigger_error('No cache object. You have to call "common_cache_get" first!', E_USER_ERROR);
            return FALSE;
        }
        
        // set default cache id name value
        $_id_name = 'cacheID_1';
        if(!empty($data['id_name']))
        {
            $_id_name = $data['id_name'];
        }    
    
        // set default cache group name value
        $_group_name = 'cacheGroup_1';
        if(!empty($data['group_name']))
        {
            $_group_name = $data['group_name'];
        }
        
        // save result to cache
        $this->B->cache->save($this->B->$_id_name, $data['result'], $this->B->sys['cache']['lifetime'], $this->B->$_group_name);

        return TRUE;
    }   
}

?>
