<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
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
        $m_list = $GLOBALS['sf_module_list'];
            
        // sort handler array by name
        ksort($m_list);
        
        // assign module names array
        // this array is used to build the modul select form of the admin menu
        $result = & $this->B->$data['var']; 
        $result = array();

        foreach ($m_list as $key => $value)
        {
            // only module with a visible interface
            if( isset($value['menu_visibility']) && ($value['menu_visibility'] == $data['menu_visibility']) )
            {
                $result[$key] =  $value;
            }
        }
        
        return SF_IS_VALID_ACTION;
    }    
}

?>
