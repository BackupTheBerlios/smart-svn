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
 * action_navigation_add_node class 
 *
 */

// PEAR File
include_once('File.php');

class action_navigation_add_node extends action
{
    /**
     * Add a navigation node (title and body)
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        $fp = new File();
        
        // make node id
        $node_id = commonUtil::unique_crc32();    
    
        // Add navigation node body
        $node_body  = SF_BASE_DIR . 'data/navigation/'.$node_id; 
        if (!is_int($fp->write  ( $node_body, $data['body'], FILE_MODE_WRITE )))
        {
            $this->B->$data['error'] = 'Could not write file: '.$node_body;
            return FALSE;
        }
        $fp->unlock ( $node, FILE_MODE_WRITE );        

        // load exsisting navigation nodes
        $nav = array();
        $nav[0] = 0;
        include(SF_BASE_DIR . 'data/navigation/nodes.php');

        // replace single and double quotes
        $search_array = array('\'','"');
        $replace_array = array('&#039;','&quot;');
        
        // add new node
        $nav[][$node_id] = array('title'  => str_replace ( $search_array, $replace_array, commonUtil::stripSlashes_special($data['title'])),
                                 'status' => 'drawt');

        // Update navigation node config file
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $nav,
                  'file'     => SF_BASE_DIR . 'data/navigation/nodes.php',
                  'var_name' => 'nav',
                  'type'     => 'PHPArray') );
        
        return TRUE;
    }  
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // validate $data['title']. Must contains data
        if( empty( $data['title'] ) )
        {   
            $this->B->$data['error'] = 'Title field is empty!!!';
            return FALSE;
        }            
        
        return TRUE;
    }     
}

?>
