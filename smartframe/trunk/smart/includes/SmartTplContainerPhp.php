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
 * Php engine Smart template container class
 *
 * Here we use php as template language
 */
 
class SmartTplContainerPhp extends SmartTplContainer
{
    /**
     * Allowed tokens
     */
    private $allowedConstructs = array('if','else','elseif','else if','endif',
                                       'foreach','endforeach','while','do','for','continue','break','switch','case',
                                       'echo','print','print_r','var_dump','exit',
                                       'defined','define',
                                       'isset','empty');

    /**
     * Tokens found
     */                                    
    private $disallowedItems = array();
                                    
    /**
     * render the template
     *
     */
    function renderTemplate()
    {
        // get reference of the template variables
        $tpl = & $this->vars;
        // get reference of the view loader methode to include
        // nested views in templates
        $viewLoader = & $this->viewLoader;
         
        // build the whole file path to the TEMPLATE file
        $template = SMART_BASE_DIR . $this->templateFolder . '/tpl.' . $this->template . '.php';

        $this->disallowedItems = array();

        if(FALSE == $this->analyze($template))
        {
            throw new SmartTplException("Template php function(s) not allowed: <pre>".var_export($this->disallowedItems,true)."<pre>");
        }

        if ( !@file_exists( $template ) )
        {
            throw new SmartTplException("Template dosent exists: ".$template);
        }

        ob_start();
        include( $template );
        $this->tplBufferContent = ob_get_clean();
    } 
    
    /**
     * analyze allowed php tokens in template 
     *
     */
    function analyze( &$template )
    {
        include_once(SMART_BASE_DIR . "smart/includes/phpca/PHPCodeAnalyzer.php");
                
        $analyzer = new PHPCodeAnalyzer();
        $analyzer->source = file_get_contents( $template );
        $analyzer->analyze();

        foreach($analyzer->calledConstructs as $key => $val)
        {
            if(!in_array($key, $this->allowedConstructs))
            {
                $this->disallowedItems[] = $key;
            }
        }  
        
        if(count($this->disallowedItems) > 0)
        {
            return FALSE;
        }
        
        return TRUE;
    }     
}

?>