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
 * action_earchive_sys_init class 
 *
 */
 
class action_earchive_sys_init extends action
{
    /**
     * Check if version number has changed and perfom additional upgarde code
     * Furthermore assign array with module menu names for the top right
     * module html seletor
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // check for install or upgrade
        if (MOD_EARCHIVE_VERSION != (string)$this->B->sys['module']['earchive']['version'])
        {
            M( MOD_EARCHIVE, 'upgrade');     
            
            // set the new version num of this module
            $this->B->sys['module']['earchive']['version']  = MOD_EARCHIVE_VERSION;
            $this->B->system_update_flag = TRUE;
        }
    }    
}

?>