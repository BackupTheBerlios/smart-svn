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
 * action_common_cache_delete class 
 *
 */
 
class action_common_cache_delete extends action
{
    /**
     * delete cache 
     *
     * @param array $data
     */
    function perform( & $data )
    {  
        // disable cache delete
        if ( $this->B->sys['option']['cache'] != TRUE )
        {
            return TRUE;
        }
        
        // Include cache and create instance
        if(!is_object($this->B->cache))
        {            
            include_once(SF_BASE_DIR . 'modules/common/PEAR/Lite.php');
            // Set a few options
            $options = array( 'cacheDir' => SF_BASE_DIR . 'data/'.SF_BASE_MODULE.'/cache/',
                              'lifeTime' => 9999999,
                              'automaticSerialization' => TRUE );  
            // Create a Cache_Lite object
            $this->B->cache = new Cache_Lite($options);
        }
                
        $group = '';
        if(!empty($data['group']))
        {
            $group = (string)$data['group'];
        }
        
        $id = '';
        if(!empty($data['id']))
        {
            $id = md5($data['id']);
        }        
        
        if(empty($id))
        {
            // Delete all cache data
            $this->B->cache->clean($group);        
        }
        else
        {
            // Delete all cache data
            $this->B->cache->remove($id, $group);         
        }
        return TRUE;
    }   
}

?>