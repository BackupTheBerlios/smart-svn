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
     * default perform
     *
     */
    function perform()
    {
    }  
    
    /**
     * default prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // Directed intercepting filter event (auto_prepend)
        // see smart/actions/class.system_sys_prepend.php
        $this->B->M( MOD_SYSTEM, 'sys_prepend' );    
    }   
    
    /**
     * default append filter chain
     *
     */
    function appendFilterChain()
    {
        // Directed intercepting filter event (auto_append)
        // see smart/actions/class.system_sys_append.php
        $this->B->M( MOD_SYSTEM, 'sys_append' );   
    }  

    /**
     * return errors as string
     *
     */
    function & getTemplate()
    {   
        // build the whole file path to the TEMPLATE file
        $tpl = SF_BASE_DIR . SF_TPL_FOLDER . $this->B->sys['option']['tpl'] . '_' . $this->template . '.tpl.php';
        if ( !@file_exists( $tpl ) )
        {
            die('Template dosent exists: ' . $tpl);
        }
        return $tpl; 
    }
    
    /**
     * get errors as string
     *
     */
    function & getError( & $data )
    {   
        $error_str = "";
        foreach ($data->errors as $error)
        {
            list($key, $val) = each($error);   
            $error_str .= $key . "\n" . $val . "\n\n";                                
        }
        
        return $error_str;
    }    
}

?>
