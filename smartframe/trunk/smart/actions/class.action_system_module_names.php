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
 * action_system_sys_logout class 
 *
 */
 
class action_system_module_names extends action
{
    /**
     * If a logout request was done
     *
     * @param array $data
     */
    function perform( $data )
    {            
        $h_list = $GLOBALS['handler_list'];
            
        // sort handler array by name
        ksort($h_list);
        
        // assign template handler names array
        // this array is used to build the modul select form of the admin menu
        $result = & $this->B->$data['var']; 
        $result = array();

        foreach ($h_list as $key => $value)
        {
            if( $value['menu_visibility'] == $data['menu_visibility'] )
            {
                $result[$key] =  $value;
            }
        }
        
        return TRUE;
    }    
}

?>
