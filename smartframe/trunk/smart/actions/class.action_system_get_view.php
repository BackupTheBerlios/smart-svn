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
 * action_system_get_view class 
 *
 * Load the demanded view
 *
 */
 
class action_system_get_view extends action
{
    /**
     * - validate the view request
     * - build the whole name of the requested view classe
     * - build the whole path to the requested view class
     *
     *
     * @param array $data
     * @return bool
     */
    function validate( & $data )
    {
        /*
         * Set public view folder.
         */
        if(!defined( SF_VIEW_FOLDER ))
        {
           define('SF_VIEW_FOLDER', $this->B->sys['option']['view']); 
        }

       /**
        * Set public template folder
        */
        if(!defined( SF_TPL_FOLDER ))
        {
           define('SF_TPL_FOLDER', $this->B->sys['option']['tpl']); 
        }
        
        // Check if the requested view is passed through the $data array
        // else fetch the name from an external var (gpc)
        if ( isset($data['view']) )
        {
            $this->_view = $data['view'];
        }
        elseif ( isset($_REQUEST['view']) )
        {
            $this->_view = $_REQUEST['view'];
        }

        // If no view request is done load the default view
        if (!isset($this->_view))
        {
            $this->_view = 'index';
        }

        // Check view request string
        if(preg_match("/[^a-zA-Z0-9_]/", $this->_view))
        {
            // set error
            $this->B->view_error = 'The requested view name "' . $this->_view . '" has a wrong name format! Only chars a-zA-Z0-9_ are accepted.';
        
            // set error view
            $this->_view = 'error';
            // build the whole file path to the error view class file
            $this->_view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $this->_view . '.php';
            
            return TRUE;
        } 

        // check if an admin view request was done
        if (SF_SECTION == 'admin')
        {
            // if no view is defines call first the common module view
            if( empty($data['view']) )
            {
                $module = SF_BASE_MODULE;
            }
            // get requested module view
            elseif ( isset($data['m']) )
            {
                $module = $data['m'];
            }
            elseif( isset( $_REQUEST['m'] ) )
            {
                $module = $_REQUEST['m'];   
            }  
            
            // Check module view request string
            if(preg_match("/[^a-zA-Z0-9_]/", $module))
            {
                // set error view
                $this->_view = 'error';
                // set error
                $this->B->view_error = 'The requested module name "' . $module . '" has a wrong name format! Only chars a-zA-Z0-9_ are accepted.';
                // build the whole file path to the error view class file
                $this->_view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $this->_view . '.php';
                
                return TRUE;            
            }
            
            // build the requested module view file path
            $this->view_folder = 'modules/' .$module. '/view/';
            // build the name of the module view class 
            $this->_view = $module . '_' . $this->_view;        
            
            // build the whole file path to the module view class file
            $this->_view_class_file = SF_BASE_DIR . 'modules/' . $module . '/views/class.view_' . $this->_view . '.php';   
        }
        else
        {
            // build the whole file path to the view class file
            $this->_view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $this->_view . '.php';
        }
        
        // check if view class file exists
        if( !@file_exists( $this->_view_class_file ) )
        {
            // set error view
            $this->_view = 'error';
            // set error
            $this->B->view_error = 'The requested view dosent exists: ' . $this->_view_class_file; 
            // build the whole file path to the error view class file
            $this->_view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $this->_view . '.php';
            return TRUE;
        }    
        
        return TRUE;
    }
    
    /**
     * - make instance of the view class
     * - launch view related auth()
     * - execute the view related prepend filter chain
     * - preform on the view class
     * - execute the view related append filter chain
     *
     * @param array $data
     * @return bool
     */
    function perform( $data )
    {
        // we need the global container object in this function as $B
        // in order to access templates variables e.g. $B->tpl_test
        // May a Template is included in this function.
        $B = & $this->B;

        // include the requested view class
        include_once( $this->_view_class_file );
        
        // build the requested view class name and make instance
        $view_class = 'view_'.$this->_view;          
        $view_obj   = & new $view_class();

        // Launch view related authentication
        $view_obj->auth(); 

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
            // get the template
            include( $view_obj->getTemplate() );   
        }    
        
        // Launch view related append filter chain
        $view_obj->appendFilterChain();     

        return TRUE;
    } 
    
    /**
     * Build the error view
     * - make instance of the view class
     * - execute the view related prepend filter chain
     * - preform on the view class
     * - return the view object
     *
     *
     * @param object $view_obj original view object
     * @return object error view object
     */    
    function & _error_view( & $view_obj )
    {
        // build the whole file path to the view class file
        $view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $view_obj->error_view . '.php';

        // include view class file of the requested template
        if( !@file_exists( $view_class_file ) )
        {
            die( "Error view class dosent exists: " . $view_class_file );  
        } 
            
        // include the requested view class
        include_once( $view_class_file );
        
        // build the class name and make instance
        $error_view_class = 'view_'.$view_obj->error_view;          
        $error_view_obj   = & new $error_view_class();            
       
        $error_view_obj->perform( $view_obj );  
        
        return $error_view_obj;
    }
}

?>
