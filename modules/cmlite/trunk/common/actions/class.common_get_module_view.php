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
 * common_get_module_view class 
 *
 * - this class validate the requested module name and template name.
 * - make an instance of the requested module view
 * - execute the perform function of this class
 * - return the requested module template
 *
 * - on error the error template is returned
 */
 
class common_get_module_view
{
    /**
     * Global system instance
     * @var object $B
     * @access privat
     */
    var $B;
    /**
     * Module name
     * @var string $module
     * @access privat
     */
    var $_module;
    /**
     * Template name
     * @var string $_view
     * @access privat
     */
    var $_view;   
    
    /**
     * constructor
     *
     */
    function common_get_module_view()
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
     * Validate request variables
     *
     * @param array $data
     * @return bool true on success or false on error
     */
    function validate( & $data )
    {  
        // check if the request is coming from the data array
        if( isset($data['m']) && isset($data['view']) )
        {
            $this->_module = $data['m'];
            $this->_view    = $data['view'];
        }
        else
        {
            // set default if no request
            if( !isset( $_REQUEST['m'] ) )
            {
                $this->_module = SF_DEFAULT_MODULE;   
            }
            // ckeck on allowed chars
            elseif(preg_match("/[a-z]+/", $_REQUEST['m']))
            {
                $this->_module = $_REQUEST['m'];   
            }
            // set error string
            else
            {
                $this->B->tpl_error = 'Wrong module name format: m=' . $_REQUEST['m'];
                return FALSE;
            }
            
            // set default if no request
            if( !isset( $_REQUEST['view'] ) )
            {
                $this->_view = 'index';   
            }    
            // ckeck on allowed chars
            elseif(preg_match("/[a-z_]+/", $_REQUEST['view']))
            {
                $this->_view = $_REQUEST['view'];
            }
            // set error string
            else
            {
                $this->B->tpl_error = 'Wrong view name format: view=' . $_REQUEST['view'];
                return FALSE;
            }        
        }  
        return TRUE;
    }
    
    /**
     * - proceed the requested module view
     * - return the requested module template
     * - on error return the error template 
     *
     * This function can receive the requested module name
     * and template name from the GPC arrays ($_REQUEST) OR
     * from the $data array e.g. $data['m'] $data['view']
     *
     * @param array $data
     * @return string whole path to the template
     */
    function perform( & $data )
    {
        // validate module name and template name requests
        if (FALSE == $this->validate( $data ))
        {
            return SF_BASE_DIR . 'error.tpl.php';
        }
        
        // path to the requested module template
        $template_file = SF_BASE_DIR . 'modules/' . $this->_module . '/templates/' . $this->_view . '.tpl.php';        
       
        // build the whole file path to the module view class file
        $view_class_file = SF_BASE_DIR . 'modules/' . $this->_module . '/view/class.'.$this->_module.'_view_' . $this->_view . '.php';
      
        // include module view class file
        if( @file_exists( $view_class_file ) )
        {  
            include_once( $view_class_file );
            
            $view_class = $this->_module . '_view_' . $this->_view;
            
            // instance of the module view class
            $view = & new $view_class();

            if( FALSE == $view->perform( $data ) )
            {
                $this->B->tpl_error = "Class function perform() in file: \n\n{$view_class_file}\n\nreturn FALSE !!!";

                // on error set the error template as default
                return SF_BASE_DIR . 'error.tpl.php';
            }
        }
          
        // check if the requested template exist
        if (!@file_exists( $template_file ))
        {
            $this->B->tpl_error = "The requested template \n\n{$template_file} \n\ndosent exists!";
              
            // on error set the error template as default
            return SF_BASE_DIR . 'error.tpl.php';
        }

        return $template_file;
    }    
}

?>
