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
 * earchive_sys_setup class 
 *
 */
 
class earchive_sys_setup
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
    function earchive_sys_setup()
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

        if(!file_exists(SF_BASE_DIR.'modules/common/includes/class.sfWordIndexer.php'))
        {
            trigger_error("File missing: Earchive depends on the following class: ".SF_BASE_DIR."/admin/modules/common/class.sfWordIndexer.php\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = "File missing: Earchive depends on the following class: ".SF_BASE_DIR."/admin/modules/common/class.sfWordIndexer.php";
            $success = FALSE;
        }

        if(!isset($_POST['dbtype']))
            $db_type = $this->B->sys['db']['dbtype'];
        else
            $db_type = $_POST['dbtype'];
    
        if($success == TRUE)
        {
            // create db tables
            if(file_exists(SF_BASE_DIR . 'modules/earchive/includes/_setup_'.$_POST['dbtype'].'.php'))
            {
                // include sqlite setup
                include_once( SF_BASE_DIR . 'modules/earchive/includes/_setup_'.$_POST['dbtype'].'.php' );    
            }
            else
            {
                $this->B->setup_error[] = 'EARCHIVE module: This db type isnt supported: ' . $_POST['dbtype'];
                $success = FALSE;
            }
        }

        // create configs info for this module
        $this->B->conf_val['module']['earchive']['name']     = 'Earchive';
        $this->B->conf_val['module']['earchive']['version']  = MOD_EARCHIVE_VERSION;
        $this->B->conf_val['module']['earchive']['mod_type'] = 'lite';
        $this->B->conf_val['module']['earchive']['info']     = 'Email messages archive. Author: Armand Turpel <smart AT open-publisher.net>';     

        return $success;
    } 
}

?>
