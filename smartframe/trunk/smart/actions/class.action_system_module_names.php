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
 * action_system_module_names class 
 *
 */
 
class action_system_module_names extends action
{
    /**
     * Assign module names
     *
     * @param array $data
     */
    function perform( $data )
    {            
        $m_list = $GLOBALS['module_list'];
            
        // sort handler array by name
        ksort($m_list);
        
        // assign module names array
        // this array is used to build the modul select form of the admin menu
        $result = & $this->B->$data['var']; 
        $result = array();

        foreach ($m_list as $key => $value)
        {
            // only module with an visible interface
            if( $value['menu_visibility'] == $data['menu_visibility'] )
            {
                $result[$key] =  $value;
            }
        }
        
        return TRUE;
    }    
}

?>
