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
 * action_system_get_public_view class 
 *
 * Load the demanded view and optionaly the related template
 *
 */
 
class action_system_get_view extends action
{
    /**
     * Default view folder
     * @var string $view_folder
     */    
    var $view_folder = SF_VIEW_FOLDER;
    
    /**
     * - validate the view request
     * - make instance of the view class
     * - execute the view related prepend filter chain
     * - preform on the view class
     * - return the view object
     *
     *
     * @param array $data
     * @return object the requested view object
     */
    function perform( $data )
    {
       /**
        * Set template folder
        */
        define('SF_TPL_FOLDER', $this->B->sys['option']['tpl']); 
        
        // we need the global container object in this function as $B
        // in order to access templates variables e.g. $B->tpl_test
        // A Template is included in this function.
        $B = & $this->B;
        
        // Check if the requested view is passed through the $data array
        // else fetch the name from an external var (gpc)
        if ( isset($data['view']) )
        {
            $view = $data['view'];
        }
        elseif ( isset($_REQUEST['view']) )
        {
            $view = $_REQUEST['view'];
        }

        // If no view request is done load the default view
        if (!isset($view))
        {
            $view = 'index';
        }

        // Check view request string
        if(!preg_match("/[a-zA-Z0-9_]{1,30}/", $view))
        {
            die ('The requested view name "' . $view . '" has a wrong name format! Only max. 30 chars a-zA-Z0-9_ are accepted.');
            exit;
        } 

        if ($_REQUEST['admin'] == '1')
        {
            // if no view is defines call first the common module view
            if( empty($data['view']) )
            {
                $module = SF_BASE_MODULE;
            }
            elseif ( isset($data['m']) )
            {
                $module = $data['m'];
            }
            elseif( isset( $_REQUEST['m'] ) )
            {
                $module = $_REQUEST['m'];   
            }  
            
            // Check view request string
            if(!preg_match("/[a-zA-Z0-9_]{1,30}/", $module))
            {
                die ('The requested module name "' . $module . '" has a wrong name format! Only max. 30 chars a-zA-Z0-9_ are accepted.');
                exit;
            }
            
            $this->view_folder = 'modules/' .$module. '/view/';
            // build the whole file path to the module view class file
            $view_class_file = SF_BASE_DIR . 'modules/' . $module . '/views/class.view_' .$module.'_'. $view . '.php';

            $view = $module . '_' . $view;
        }
        else
        {
            // build the whole file path to the view class file
            $view_class_file = SF_BASE_DIR . $this->view_folder . 'class.view_' . $view . '.php';
        }
        
        // check if view class file exists
        if( !@file_exists( $view_class_file ) )
        {
            die ('The requested view dosent exists: ' . $view_class_file);
            exit; 
        }
        
        // include the requested view class
        include_once( $view_class_file );
        
        // build the requested view class name and make instance
        $view_class = 'view_'.$view;          
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
