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
 * OPTION_SYS_LOAD_MODULE class 
 *
 */
 
class OPTION_SYS_LOAD_MODULE
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
    function OPTION_SYS_LOAD_MODULE()
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
     * Load the option module to perform on admin requests
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Init this variable
        $this->B->modul_options = FALSE;

        // init var - used if a config value has been modified
        $this->B->_modified = FALSE;
    
        if (isset($_POST['update_main_options_url']))
        {
            $this->B->sys['option']['url'] = stripslashes($_POST['site_url']);
            $this->B->_modified = TRUE;
        }
        elseif (isset($_POST['update_main_options_email']))
        {
            $this->B->sys['option']['email']        = stripslashes($_POST['site_email']);
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_title']))
        {
            $this->B->sys['option']['site_title'] = stripslashes($_POST['site_title']);
            $this->B->sys['option']['site_desc']  = stripslashes($_POST['site_desc']);
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_charset']))
        {
            $this->B->sys['option']['charset']    = stripslashes($_POST['charset']);
            $this->B->_modified = TRUE;
        }  
        elseif (isset($_POST['update_main_options_tpl']))
        {
            $this->B->sys['option']['tpl']        = stripslashes($_POST['tpl']);
            $this->B->_modified = TRUE;
        }     
       
        // if some config are modified, write the config file and reload the page
        if($this->B->_modified == TRUE)
        {
            // include PEAR Config class
            include_once( SF_BASE_DIR . '/admin/modules/common/PEAR/Config.php');
        
            $c = new Config();
            $root =& $c->parseConfig($this->B->sys, 'PHPArray');
                          
            // write config array
            $c->writeConfig(SF_BASE_DIR . '/admin/modules/common/config/config.php', 'PHPArray', array('name' => 'B->sys'));
          
            unset($c);
            unset($root);
    
            @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=OPTION');
            exit;
        }      
    
        // Load the available public templates groups from the main folder 
        $this->B->templ = array();
        $directory =& dir(SF_BASE_DIR);
      
        while (false != ($dirname = $directory->read()))
        {
            if (FALSE == is_dir(SF_BASE_DIR . '/' . $dirname))
            {
                if(preg_match("/(^[^_]+).*\.tpl\.php$/", $dirname, $tmp))
                {
                    if(!in_array($tmp[1], $this->B->templ))
                        $this->B->templ[] = $tmp[1];
                }
            }
        }

        $directory->close();

        // set the base admin template for this module
        $this->B->module = SF_BASE_DIR . '/admin/modules/option/templates/index.tpl.php';    
    }    
}

?>
