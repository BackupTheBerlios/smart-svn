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
 * default view. Parent class of every view class
 *
 */
 
class view
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;

    /**
     * Error array
     * @var array $errors
     */
    var $errors = array();

     /**
     * Default error view
     * @var string $error_view
     */   
    var $error_view = 'error';

     /**
     * Template render flag
     * @var bool $render_template
     */    
    var $render_template = SF_TEMPLATE_RENDER; // or SF_TEMPLATE_RENDER_NONE

     /**
     * Template folder
     * @var bool $template_folder
     */
    var $template_folder = SF_TPL_FOLDER;
    
     /**
     * Template output buffer flag
     * @var bool $tpl_use_buffer
     */
    var $tpl_use_buffer = TRUE;    
    
    
    /**
     * constructor php4
     *
     */
    function view()
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
     * perform
     *
     */
    function perform()
    {
    }  

    /**
     * authentication
     *
     */
    function auth()
    {
    }
    
    /**
     * prepend filter chain
     *
     */
    function prependFilterChain()
    { 
    }   
    
    /**
     * append filter chain
     *
     */
    function appendFilterChain()
    {  
    }  

    /**
     * return errors as string
     *
     */
    function renderTemplate()
    {
        // we need the global container object in this function as $B
        // in order to access templates variables e.g. $B->tpl_test
        // May a Template is included in this function.    
        $B = & $this->B;
        
        // build the whole file path to the TEMPLATE file
        $tpl = SF_BASE_DIR . $this->template_folder . 'tpl.' . $this->template . '.php';
        if ( !@file_exists( $tpl ) )
        {
            die('Template dosent exists: ' . $tpl);
        }


        if( TRUE == $this->tpl_use_buffer )
        {
            $B->tpl_buffer_content = '';
            ob_start();
            
            include( $tpl );

            $B->tpl_buffer_content = ob_get_clean(); 
        }
        else
        {
            include( $tpl );
        }

    }
    
    /**
     * get errors as string
     *
     */
    function & getError()
    {   
        $error_str = "";
        foreach ($this->errors as $key => $val)
        { 
            $error_str .= $key . "\n" . $val . "\n\n";  
        }
        
        return $error_str;
    }    
}

?>
