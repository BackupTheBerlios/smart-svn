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
 * system_get_public_view class 
 *
 */
 
class system_get_public_view
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
    function system_get_public_view()
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
        // Check if the requested template is passed through the $data array
        // else fetch the name from an external var (gpc)
        if ( isset($data['view']) )
        {
            $view = $data['view'];
        }
        elseif ( isset($_REQUEST['view']) )
        {
            $view = $_REQUEST['view'];
        }

        // If no view request is done load the default template
        if (!isset($view))
        {
            $view = 'index';
        }

        // Check view request string
        if(!preg_match("/[a-zA-Z0-9_]{1,30}/", $view))
        {
            die ('The requested template name "' . $view . '" has a wrong name format! Only max. 30 chars a-zA-Z0-9_ are accepted.');
            exit;
        } 

        // build the whole file path to the view class file
        $view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $view . '.php';

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
        
        // Launch view related prepend filter chain
        $view_obj->prependFilterChain(); 
        
        // perform on the view
        if( FALSE == $view_obj->perform() )
        {
            // if error
            return $this->_error_view( $view_obj );
        }

        return $view_obj;
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
    function _error_view( & $view_obj )
    {
        // build the whole file path to the view class file
        $view_class_file = SF_BASE_DIR . SF_VIEW_FOLDER . 'class.view_' . $view_obj->error_view . '.php';

        // include view class file of the requested template
        if( @file_exists( $view_class_file ) )
        {
            die( "Error view class dosent exists: " . $view_class_file );  
        } 
            
        // include the requested view class
        include_once( $view_class_file );
        
        // build the class name and make instance
        $error_view_class = 'view_'.$view_obj->error_view;          
        $error_view_obj   = & new $error_view_class();            
       
        return $error_view_obj->perform( $view_obj );      
    }
}

?>
