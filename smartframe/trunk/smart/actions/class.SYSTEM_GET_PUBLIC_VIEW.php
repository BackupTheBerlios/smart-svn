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
 * SYSTEM_GET_PUBLIC_VIEW class 
 *
 */
 
class SYSTEM_GET_PUBLIC_VIEW
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
    function SYSTEM_GET_PUBLIC_VIEW()
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
        if( isset($data['tpl']) )
        {
            $tpl = $data['tpl'];
        }
        else
        {
            $tpl = $_REQUEST['tpl'];
        }
    
        // If no template request is done load the default template
        if (!isset($tpl))
        {
            $tpl = 'index';
            // build the whole requested template file path
            $template_file = SF_BASE_DIR . $this->B->sys['option']['tpl'] . '_' . $tpl . '.tpl.php';           
        }
        // error if the template name has a wrong format
        elseif(preg_match("/[^a-z_]{1,30}/", $tpl))
        {
            $this->B->tpl_error = 'The requested template name "' . $tpl . '" has a wrong name format! Only chars a-z and underscores _ are accepted.';
            
            $tpl = 'error';  
            
            // build the whole requested template file path
            $template_file = SF_BASE_DIR . 'error.tpl.php';
        } 
        else
        {
            // build the whole requested template file path
            $template_file = SF_BASE_DIR . $this->B->sys['option']['tpl'] . '_' . $tpl . '.tpl.php';
        }

        // build the whole file path to the view class file
        $view_class_file = SF_BASE_DIR . 'view/class.view_' . $tpl . '.php';

        // include view class file of the requested template
        if( @file_exists( $view_class_file ) )
        {
            include_once( $view_class_file );
            
            $view_class = 'view_'.$tpl;
            
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
