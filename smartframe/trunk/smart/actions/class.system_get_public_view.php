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
     * - validate the template request
     * - build the whole path to the requested template
     * - return the template path
     * - load a template view class if present
     *
     *
     * @param array $data
     * @return string
     */
    function perform( $data )
    {
        // Check if the reuested template is internal defined
        // else fetch the name from an external var (gpc)
        if( isset($data['view']) )
        {
            $view = $data['view'];
        }
        else
        {
            $view = $_REQUEST['view'];
        }

        // If no template request is done load the default template
        if (!isset($view))
        {
            $view = 'index';
        }

        // init
        $template_file = '';

        // build the whole file path to the view class file
        $view_class_file = SF_BASE_DIR . 'view/class.view_' . $view . '.php';

        // include view class file of the requested template
        if( @file_exists( $view_class_file ) )
        {
            include_once( $view_class_file );
            
            $view_class = 'view_'.$view;
            
            $_view = & new $view_class();
            if( FALSE === ($view_class_result = $_view->perform()) )
            {
                // on error set the error template as default
                $template_file = SF_BASE_DIR . 'error.tpl.php';
            }
            elseif( TRUE !== $view_class_result )
            {
                // get new view from the view class
                $view = & $view_class_result;
            }
        }
    
        // check if no error template file requested
        if( empty($template_file) )
        {
            if(preg_match("/[^a-z_]{1,30}/", $view))
            {
                $this->B->view_error = 'The requested template name "' . $view . '" has a wrong name format! Only chars a-z and underscores _ are accepted.';
                
                $view = 'error';  
                
                // build the whole requested template file path
                $template_file = SF_BASE_DIR . 'error.tpl.php';
            } 
            else
            {
                // build the whole requested template file path
                $template_file = SF_BASE_DIR . $this->B->sys['option']['tpl'] . '_' . $view . '.tpl.php';
            }
        }
         
        // check if the requested template exist
        if (!@file_exists( $template_file ))
        {
            $this->B->view_error = 'The requested template "' .$template_file. '" dosent exists!';
            $template_file = SF_BASE_DIR . 'error.tpl.php';
            
            if (!@file_exists( $template_file ))
            {            
                // on error
                die ("The requested template file '{$template_file}' dosent exist! Please contact the administrator {$this->B->sys['option']['email']}");
            }
        }

        return $template_file;
    }    
}

?>
