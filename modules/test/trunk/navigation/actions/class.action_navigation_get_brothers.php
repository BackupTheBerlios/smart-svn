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
 * action_navigation_get_brothers class 
 *
 */

class action_navigation_get_brothers extends action
{
    /**
     * Get all nodes from the same node level as the current node
     *
     * Structure of the $data array:
     * $data['result'] - name of the navigation array result
     * $data['status'] - status of the nodes to get
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        // get var name defined in the public view to store the result
        $_result = & $this->B->$data['result']; 
        $_result = array();

        // check if a tree object exists
        if(!is_array($this->B->node))
        {
            // load navigation nodes  
            $node = array();
            include ( SF_BASE_DIR . 'data/navigation/nodes.php' ); 
            $this->B->node = & $node;     
        }         

        if( !isset($data['node']) )
        {
            $data['node'] = 0;
            //return FALSE;        
        }
        else
        {
            // assign parent node
            $data['node'] = $this->B->node['node']['parent_id'];
        }
        
        // get child nodes of a given node id
        $_result = $this->getChildren( $data ); 
        
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
     
        return SF_IS_VALID_ACTION; 
    } 
    
    /**
     * get child nodes sorted by order
     *
     * @param array $data
     */     
    function & getChildren( & $data )
    {
        $tmp = array();
        foreach ($this->B->node as $key => $val)
        {
            if( $val['parent_id'] == $data['node'] )
            {
                if( isset($data['status']) )
                {
                    if( $val['status'] == $data['status'] )
                    {
                        $tmp[$val['order']] = $key;
                    }
                    continue;
                }
                $tmp[$val['order']] = $key;
            }
        }
        // ordered
        ksort($tmp);
        
        $result = array();
        
        foreach ($tmp as $val)
        {
            $result[$val]['title']     = $this->B->node[$val]['title'];
            $result[$val]['status']    = $this->B->node[$val]['status'];
            $result[$val]['order']     = $this->B->node[$val]['order'];
            $result[$val]['parent_id'] = $this->B->node[$val]['parent_id'];
        }  
        
        unset($tmp);

        return $result;
    }        
}

?>