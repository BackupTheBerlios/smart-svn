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
 * option_view_index class of the template "index.tpl.php"
 *
 */
 
class option_view_index
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
    function option_view_index()
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
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // Init this variable
        $this->B->modul_options = FALSE;

        // init var - used if a config value has been modified
        $this->B->_modified = FALSE;

        if (isset($_POST['update url']))
        {
            $this->B->sys['option']['url'] = stripslashes($_POST['site_url']);
            $this->B->_modified = TRUE;
        }
        elseif ( $_POST['action'] == 'update email' )
        {
            $this->B->sys['option']['email']        = stripslashes($_POST['site_email']);
            $this->B->_modified = TRUE;
        } 
        elseif ( $_POST['action'] == 'update title' )
        {
            $this->B->sys['option']['site_title'] = stripslashes($_POST['site_title']);
            $this->B->sys['option']['site_desc']  = stripslashes($_POST['site_desc']);
            $this->B->_modified = TRUE;
        } 
        elseif ( $_POST['action'] == 'update charset' )
        {
            $this->B->sys['option']['charset']    = stripslashes($_POST['charset']);
            $this->B->_modified = TRUE;
        }  
        elseif ( $_POST['action'] == 'update group' )
        {
            $this->B->sys['option']['tpl']        = stripslashes($_POST['group']);
            $this->B->_modified = TRUE;
        }     
       
        // if some config are modified, write the config file and reload the page
        if($this->B->_modified == TRUE)
        {
            // include PEAR Config class
            include_once( SF_BASE_DIR . 'modules/common/PEAR/Config.php');
        
            $c = new Config();
            $root =& $c->parseConfig($this->B->sys, 'PHPArray');
                          
            // write config array
            $c->writeConfig(SF_BASE_DIR . 'modules/common/config/config.php', 'PHPArray', array('name' => 'this->B->sys'));
          
            unset($c);
            unset($root);
    
            // reload the option menu
            @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=option&tpl=index');
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
                    if(($tmp[1] != 'error') && !in_array($tmp[1], $this->B->templ))
                        $this->B->templ[] = $tmp[1];
                }
            }
        }

        $directory->close();
            
        return TRUE;
    }    
}

?>
