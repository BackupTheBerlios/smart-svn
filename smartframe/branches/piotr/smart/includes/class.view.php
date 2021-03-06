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
    var $error = array();

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
     * Data container passed to the view
     * @var mixed $view_data
     */
    var $view_data = FALSE;   
    
    /**
     * constructor php4
     *
     */
    function view( & $data )
    {
        $this->__construct( $data );
    }

    /**
     * constructor php5
     *
     */
    function __construct(  & $data  )
    {
        $this->B = & $GLOBALS['B'];
        $this->view_data = $data;
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
     * render the template
     * it build the wole path to the template and include it.
     * and store on demande the template output in a container variable
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
            $this->error[] = array('renderTemplate' => 'Template dosent exists: ' . $tpl);
            return FALSE;
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
        
        return TRUE;
    }

    /**
     * Call default error view
     */    
    function error()
    {
        M( MOD_SYSTEM, 
           'get_view',
           array( 'view'  => $this->error_view,
                  'error' => $this->error) );
                  
        exit;
    }   
}

?>
