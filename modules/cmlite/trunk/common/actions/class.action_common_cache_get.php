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
 * action_common_cache_get class 
 *
 */
 
class action_common_cache_get extends action
{
    /**
     * Get cache result
     *
     * @param array $data
     */
    function perform( & $data )
    {
        // get var name to store the result
        $result = & $this->B->$data['result'];
      
        // Include cache and create instance
        if(!is_object($this->B->cache))
        {            
            include_once(SF_BASE_DIR . 'modules/common/PEAR/Cache/Lite.php');
            // Set a few options
            $options = array( 'cacheDir' => SF_BASE_DIR . 'modules/common/tmp/cache/',
                              'lifeTime' => 9999999,
                              'automaticSerialization' => TRUE);  
            // Create a Cache_Lite object
            $this->B->cache = new Cache_Lite($options);
        }

        $_id_name = 'cacheID_1';
        if(!empty($data['id_name']))
        {
            $_id_name = $data['id_name'];
        }
        
        // create cache ID
        $this->B->$_id_name = md5( $data['cacheID'] );

        // set default cache id name value
        $_group_name = 'cacheGroup_1';
        if(!empty($data['group_name']))
        {
            $_group_name = $data['group_name'];
        }
        
        // set default cache group name value
        $this->B->$_group_name = 'default';
        if(!empty($data['cacheGroup']))
        {
            $this->B->$_group_name = $data['cacheGroup'];
        }

        // check if cache ID exists
        if ($result = $this->B->cache->get($this->B->$_id_name, $this->B->$_group_name)) 
        {
            return TRUE;
        }
        return FALSE;
    }   
}

?>