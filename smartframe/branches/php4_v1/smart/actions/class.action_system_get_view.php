<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
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
     * - check if an update was done and if so reload the page
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
        if(!defined( 'SF_VIEW_FOLDER' ))
        {
           define('SF_VIEW_FOLDER', $this->B->sys['option']['view']); 
        }

       /**
        * Set public template folder
        */
        if(!defined( 'SF_TPL_FOLDER' ))
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
            // set error;
            $this->_error( array(array('View' => 'The requested view name "' . $this->_view . '" has a wrong name format! Only chars a-zA-Z0-9_ are accepted.')) );

            return SF_IS_VALID_ACTION;
        } 

        // check if an admin view request was done
        if ( (SF_SECTION == 'admin') && !isset($data['internal']) )
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
                $this->_error( array(array('Module' => 'The requested module name "' . $module . '" has a wrong name format! Only chars a-zA-Z0-9_ are accepted.')) );

                return SF_IS_VALID_ACTION;            
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
            $this->_error( array(array('View' => 'The requested view dosent exists: ' . $this->_view_class_file)) );
        }    
        
        return SF_IS_VALID_ACTION;
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
    function perform( & $data )
    {
        // include the requested view class
        include_once( $this->_view_class_file );
        
        // build the requested view class name and make instance
        $view_class = 'view_'.$this->_view;          
        $view_obj   = & new $view_class( $data );

        // Launch view related authentication
        $view_obj->auth(); 

        // Launch view related prepend filter chain
        $view_obj->prependFilterChain(); 
        
        // perform on the view
        if( FALSE == $view_obj->perform() )
        {
            // if error get the error view object
            $view_obj->error();
        }

        // render a template ???
        if ( SF_TEMPLATE_RENDER == $view_obj->render_template )
        {
            // render the template
            if( FALSE == $view_obj->renderTemplate() ) 
            {
                $view_obj->error();
            }
        }    
       
        // Launch view related append filter chain
        $view_obj->appendFilterChain();   
        
        // Output template buffer if present
        if( TRUE == $view_obj->tpl_use_buffer )
        {
            echo $this->B->tpl_buffer_content;
        }

        return SF_IS_VALID_ACTION;
    } 
    /**
     * Launch error view if an error in the validate function of this class occurs
     *
     * @param array $data
     * @return bool
     */    
    function _error( $data )
    {
        // get error view
        // This happen when a module or view dosent exists
        // 'internal' = Flag to prevent the reload of the admin section view again 
        //
        M( MOD_SYSTEM, 
          'get_view',
          array( 'view'     => 'error',
                 'internal' => TRUE, 
                 'error'    => $data ) );
                  
        exit;      
    }
}

?>