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
 */
 
class common_get_module_view
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
     * - check if the main admin template exists
     * - return the template path
     *
     *
     * @param array $data
     * @return string
     */
    function perform( $data )
    {
        if(preg_match("/[a-z]+/", $_REQUEST['m']))
        {
            $module = $_REQUEST['m'];
        }
        else
        {
            $module = FALSE;
        }
        
        if(preg_match("/[a-z_]+/", $_REQUEST['tpl']))
        {
            $tpl = $_REQUEST['tpl'];
        }
        else
        {
            $tpl = FALSE;
        }        
    
        // get module view
        if( ( FALSE != $module ) && ( FALSE != $tpl ) )
        {
            // path to the main admin template
            $template_file = SF_BASE_DIR . 'modules/' . $module . '/templates/' . $tpl . '.tpl.php';        
            // build the whole file path to the view class file
            $view_class_file = SF_BASE_DIR . 'modules/' . $module . '/view/class.mod_view_' . $tpl . '.php';

        }
        // get default module view
        else
        {
            // path to the main admin template
            $template_file = SF_BASE_DIR . 'modules/' . SF_DEFAULT_MODULE . '/templates/index.tpl.php';        
            // build the whole file path to the view class file
            $view_class_file = SF_BASE_DIR . 'modules/' . SF_DEFAULT_MODULE . '/view/class.mod_view_index.php';        
        }
        
        // include view class file of the requested template
        if( @file_exists( $view_class_file ) )
        {
            include_once( $view_class_file );
            
            $view_class = 'mod_view_'.$data['tpl'];
            
            $view = & new $view_class();
            if( FALSE == $view->perform() )
            {
                // on error set the error template as default
                $template_file = SF_BASE_DIR . 'error.tpl.php';
            }
        }
            
        // check if the requested template exist
        if (!@file_exists( $template_file ))
        {
            $this->B->tpl_error = 'The requested template "' .$template_file. '" dosent exists!';
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
