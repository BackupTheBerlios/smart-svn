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

class action_navigation_delete_node extends action
{
    /**
     * Update navigation node title and body
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // Update navigation node body
        if (FALSE == @unlink (SF_BASE_DIR . 'data/navigation/'.$data['node']))
        {
            $data['error'] = 'Could not delete node file: '.SF_BASE_DIR . 'data/navigation/'.$data['node'];
            return FALSE;
        }
        
        // load navigation node titles
        $nav = array();
        include(SF_BASE_DIR . 'modules/navigation/_nav_data/nav_data.php');
        
        // init loop var
        $x = 0;
        
        // Look at the node id and assign the new title
        foreach($nav as $node)
        {
            list($id, $val) = each($node);
            if($data['node'] == $id)
            {
                unset($nav[$x]);
                break;
            }
            $x++;
        } 
        
        // Update navigation node title
        // see modules/common/actions/class.action_common_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $nav,
                  'file'     => SF_BASE_DIR . 'modules/navigation/_nav_data/nav_data.php',
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
        // validate $data['node']. no chars else than 0123456789 and - are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            return FALSE;
        }     
        
        return TRUE;
    }     
}

?>
