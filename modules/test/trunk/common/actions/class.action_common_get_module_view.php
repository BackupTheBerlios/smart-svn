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
 * action_common_get_module_view class 
 *
 * - this class validate the requested module name and template name.
 * - make an instance of the requested module view
 * - execute the perform function of this class
 * - return the requested module template
 *
 * - on error the error template is returned
 */
 
class action_common_get_module_view extends action
{
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
            $this->_view   = $data['view'];
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

        // build the whole file path to the module view class file
        $view_class_file = SF_BASE_DIR . 'modules/' . $this->_module . '/view/class.view_' .$this->_module.'_'. $this->_view . '.php';
      
        // build the whole file path to the view class file
        //$view_class_file = SF_BASE_DIR . $this->view_folder . 'class.view_' . $view . '.php';

        // check if view class file exists
        if( !@file_exists( $view_class_file ) )
        {
            die ('The requested view dosent exists: ' . $view_class_file);
            exit; 
        }
        
        // include the requested view class
        include_once( $view_class_file );
        
        // build the requested view class name and make instance
        $view_class = 'view_'.$this->_module.'_'.$this->_view;          
        $view_obj   = & new $view_class();

        // Launch view related authentication
        $view_obj->auth(); 

        // Launch view related logout
        $view_obj->logout(); 

        // Launch view related prepend filter chain
        $view_obj->prependFilterChain(); 

        // perform on the view
        if( FALSE == $view_obj->perform() )
        {
            // if error get the error view object
            $view_obj = $this->_error_view( $view_obj );
        }

        // render a template ???
        if ( SF_TEMPLATE_RENDER == $view_obj->render_template )
        {
            // get the public template
            include( $view_obj->getTemplate() );   
        }    
        
        // Launch view related append filter chain
        $view_obj->appendFilterChain();     

        return true;
    }    
}

?>