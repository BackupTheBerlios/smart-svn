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
 * action_navigation_update_node class 
 *
 */
 
class action_navigation_sort_node extends action
{
    /**
     * Move up or down a navigation node
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {      
        // load navigation nodes
        $nav = array();
        include(SF_BASE_DIR . 'data/navigation/nodes.php');
        
        // init loop var
        $x = 1;
        
        // Look at the node id to move
        foreach($nav as $node)
        {
            if($node == 0)
            {
                continue;
            }
            
            list($id, $val) = each($node);
            
            if($data['node'] == $id)
            {
                // move up
                if( ($data['dir'] == 'up') && isset($nav[$x-1]) )
                {
                    $prev_id = $x-1;
                    if($prev_id != 0)
                    {
                    $tmp = $nav[$prev_id];
                    $nav[$prev_id] = $nav[$x];
                    $nav[$x] = $tmp;
                    }
                }
                // move down
                elseif( ($data['dir'] == 'down') && isset($nav[$x+1]) )
                {
                    $tmp = $nav[$x+1];
                    $nav[$x+1] = $nav[$x];
                    $nav[$x] = $tmp;
                }                
                break;
            }
            $x++;
        } 
        
        //$nav['0'] = 0;
        
        // Update navigation nodes config file
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
        // validate $data['dir']. it should be "up" or "down"
        if( !preg_match("/up|down/", $data['dir']) )
        {
            return FALSE;
        }
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            return FALSE;
        }     
        
        return TRUE;
    }
}

?>
