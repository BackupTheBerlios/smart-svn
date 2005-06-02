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
 * action_navigation_get_childs class 
 *
 */

class action_navigation_get_tree extends action
{
    /**
     * Fill up an array with navigation elements
     *
     * Structure of the $data array:
     * $data['result'] - name of the navigation array
     * $data['status'] - status of the nodes to get
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        // get var name defined in the public view to store the result
        $this->tree_result = & $this->B->$data['result']; 
        $this->tree_result = array();

        if(!isset($data['node']))
        {
            $_node = 0;
        }
        else
        {
            $_node = $data['node'];
        }

        if(!isset($data['status']))
        {
            $status = FALSE;
        }
        else
        {
            $status = $data['status'];
        }

        if(SF_SECTION == 'public')
        {
            // check if cache ID exists
            if ( M( MOD_COMMON, 
                    'cache_get',
                    array('result'     => $data['result'],
                          'cacheID'    => SF_SECTION.'tree'.$_node.$status,
                          'cacheGroup' => 'navigation-tree'))) 
            {
                return SF_IS_VALID_ACTION;
            }  
        }

        // load navigation nodes
        include (SF_BASE_DIR . 'data/navigation/nodes.php');    
            
        // order the node tree by order
        $this->_tmp_array = array();
        $s=0;
        foreach($node as $n => $x)
        {
            $x['node'] = $n;
            $this->_tmp_array['o'.dechex($x['order']).$s] = $x; 
            $s++;
        }
        
        ksort($this->_tmp_array);

        $this->_level = 0;        

        // get child nodes of a given node id
        $this->_getTreeNodes( $_node, $status ); 

        if(SF_SECTION == 'public')
        {
            // save result to cache
            M( MOD_COMMON, 
               'cache_save',
               array('result' => $this->tree_result));  
        }
 
        return SF_IS_VALID_ACTION;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // validate $data['status']. "publish" or "drawt" are accepted
        if( isset($data['status']) && ( ($data['status'] < 0) || ($data['status'] > 2) ) )
        {
            trigger_error("Wrong 'status' variable: ".$data['status']." Only 2 = 'publish' or 1 = 'drawt' are accepted.\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return SF_NO_VALID_ACTION;
        }     
        // check if node exists
        if(isset($data['node']) && !file_exists(SF_BASE_DIR . 'data/navigation/'.$data['node']))
        {
            $this->B->$data['error']  = 'Node '.$data['node'].' dosent exists';
            return SF_NO_VALID_ACTION;            
        }   
        
        return SF_IS_VALID_ACTION;
    } 

    /**
     * Load the node tree ordered and with level indents
     *
     * @param int $parent_id
     * @param int $status
     */      
    function _getTreeNodes( $parent_id = 0, $status = FALSE )
    {       
        foreach($this->_tmp_array as $val)
        {
            if( $val['parent_id'] == $parent_id )
            {
                if( FALSE != $status )
                {
                    if( $status == $val['status'] )
                    {
                        $val['level'] = $this->_level;
                        $this->tree_result[] = $val;
                        $this->_level++;
                        $this->_getTreeNodes( $val['node'], $status );
                    }
                }
                else
                {
                    $val['level'] = $this->_level;
                    $this->tree_result[] = $val;
                    $this->_level++;
                    $this->_getTreeNodes( $val['node'], $status );                
                }
            }
        }  
        $this->_level--;
        return;
    }    
}

?>