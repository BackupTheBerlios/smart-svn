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
 * COMMON_SYS_INIT class 
 *
 */
 
class COMMON_SYS_INIT
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function COMMON_SYS_INIT()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Check if version number has changed and perfom additional upgarde code
     * Furthermore assign array with module menu names for the top right
     * module html seletor
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Check for upgrade  
        if(MOD_COMMON_VERSION != (string)$this->B->sys['module']['common']['version'])
        {
            // set the new version num of this module
            $this->B->sys['module']['common']['version']  = MOD_COMMON_VERSION;
            $this->B->system_update_flag = TRUE;  

            if(($success == TRUE) && !is_writeable( SF_BASE_DIR . '/admin/modules/common/tmp/session_data' ))
            {
                die('Must be writeable: ' . SF_BASE_DIR . '/admin/modules/common/tmp/session_data');
                $success = FALSE;
            } 
            // include here additional upgrade code
        }
            
        // Assign registered module handlers template var
        $this->B->tpl_mod_list = array();
            
        // sort handler list by name
        ksort($this->B->handler_list);
        // assign template handler list array
        foreach ($this->B->handler_list as $key => $value)
        {
            if( $value['menu_visibility'] == TRUE )
            {
                $this->B->tpl_mod_list[$key] =  $value;
            }
        }
    }    
}

?>
