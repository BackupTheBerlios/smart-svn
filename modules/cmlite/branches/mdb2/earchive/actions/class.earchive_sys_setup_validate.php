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
 * earchive_sys_setup_validate class 
 *
 */
 
class earchive_sys_setup_validate
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function earchive_sys_setup_validate()
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
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
        //create data folder
        if(!is_dir(SF_BASE_DIR . 'data/earchive'))
        {
            if(!mkdir(SF_BASE_DIR . 'data/earchive', SF_DIR_MODE))
            {
                trigger_error("Cant make dir: ".SF_BASE_DIR."data/earchive\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . 'data/earchive';
                $success = FALSE;
            }
            elseif(!is_writeable( SF_BASE_DIR . 'data/earchive' ))
            {
                trigger_error("Cant make dir: ".SF_BASE_DIR."data/earchive\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/earchive';
                $success = FALSE;
            }  
        }

        return $success;
    } 
}

?>
