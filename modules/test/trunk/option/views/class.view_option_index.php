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
 * view_option_index class 
 *
 */

class view_option_index extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'option_index';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/option/templates/';
    
    /**
     * Evaluate the option requests of the option template tpl.option_index.php
     *
     * @param array $data
     */
    function perform(  $data = FALSE  )
    {    
        // Init this variable
        $this->B->modul_options = FALSE;

        // update the main options on demande
        $this->_update_main_options();
        
        // update options of other modules
        B( 'set_options' );
        
        // if some config are modified, write the config file and reload the page
        if($this->B->_modified == TRUE)
        {
            // see modules/SF_BASE_MODULE/actions/class.action_SF_BASE_MODULE_sys_update_config.php
            M( SF_BASE_MODULE, 
               'sys_update_config', 
               array( 'data'     => $this->B->sys,
                      'file'     => SF_BASE_DIR . 'modules/'.SF_BASE_MODULE.'/config/config.php',
                      'var_name' => 'this->B->sys',
                      'type'     => 'PHPArray') );
        
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=option');
            exit;
        }
        
        // assign tpl array with available public template groups
        $this->_load_public_tpl_groups();

        // assign tpl array with available public template groups
        $this->_load_public_view_groups();

        return TRUE;     
    } 

    /**
     * Update main options
     *
     * @access privat
     */    
    function _update_main_options()
    {
        // init var - used if a config value has been modified
        $this->B->_modified = FALSE;
        
        if (isset($_POST['update_main_options_email']))
        {
            $this->B->sys['option']['email'] = $_POST['site_email'];
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_title']))
        {
            $search_array = array('\'','"');
            $replace_array = array('&#039;','&quot;');
        
            $this->B->sys['option']['site_title'] = str_replace ( $search_array, $replace_array, commonUtil::stripSlashes_special($_POST['site_title']));
            $this->B->sys['option']['site_desc']  = str_replace ( $search_array, $replace_array, commonUtil::stripSlashes_special($_POST['site_desc']));
            $this->B->_modified = TRUE;
        } 
        elseif (isset($_POST['update_main_options_tpl']))
        {
            $this->B->sys['option']['tpl'] = $_POST['tplgroup'];
            $this->B->_modified = TRUE;
        }  
        elseif (isset($_POST['update_main_options_view']))
        {
            $this->B->sys['option']['view'] = $_POST['viewgroup'];
            $this->B->_modified = TRUE;
        }         
    }

    /**
     * Assign template array with all available public template groups
     *
     * @access privat
     */     
    function _load_public_tpl_groups()
    {
         // Load the available public templates sets from the main folder 
         $this->B->templatefolder = array();
         
         // the root directory
         $this->B->templatefolder[] = "";
         
         $directory =& dir(SF_BASE_DIR);

         while (false != ($itemname = $directory->read()))
         {
             if (TRUE == is_dir(SF_BASE_DIR . '/' . $itemname))
             {
                if(preg_match("/^templates_/", $itemname))
                {
                    $this->B->templatefolder[] = $itemname . "/";
                }
             }
         }

         $directory->close();    
    }
    /**
     * Assign view array with all available public view folders
     *
     * @access privat
     */     
    function _load_public_view_groups()
    {
         // Load the available public view folders
         $this->B->viewfolder = array();
         
         $directory =& dir(SF_BASE_DIR);

         while (false != ($itemname = $directory->read()))
         {
             if (TRUE == is_dir(SF_BASE_DIR . '/' . $itemname))
             {
                if(preg_match("/^views_/", $itemname))
                {
                    $this->B->viewfolder[] = $itemname . "/";
                }
             }
         }

         $directory->close();    
    }    
}

?>