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
 * Smarty engine template container class
 *
 * Here we use Smarty as template language
 */

define('SMARTY_DIR', SMART_BASE_DIR . 'smart/includes/smarty/');
include_once(SMARTY_DIR . 'Smarty.class.php');

class SmartSmarty extends Smarty
{
    // check if the template needs to be compiled by Smarty
    public function smartCompileTemplate( $resource_name )
    {
        $_smarty_compile_path = $this->_get_compile_path($resource_name);
        
        if( $this->_is_compiled($resource_name, $_smarty_compile_path) )
        { 
            return FALSE;    
        }
        return TRUE;  
    }
}
 
class SmartTplContainerSmarty extends SmartTplContainer
{
    /**
     * Tokens found
     */                                    
    private $disallowedItems = array();
                                    
    /**
     * render the template
     *
     */
    public function renderTemplate()
    {
        $smarty = new SmartSmarty;
        $smarty->compile_dir  = SMART_BASE_DIR . 'cache/smartyCompiled/';
        $smarty->template_dir = SMART_BASE_DIR . $this->templateFolder;
        $smarty->config_dir   = SMART_BASE_DIR . 'config/';
        
        $smarty->php_handling = SMARTY_PHP_QUOTE;
    
        // get reference of the template variables
        $smarty->assign_by_ref("tpl", $this->vars);

        // get reference of the view loader methode to include
        // nested views in templates
        $viewLoader = & $this->viewLoader;

        // build the whole file path to the TEMPLATE file
        $template = SMART_BASE_DIR . $this->templateFolder . 'tpl.' . $this->template . '.php';
        
        if($this->config['useCodeAnalyzer'] == TRUE)
        {
            $this->disallowedItems = array();
            
            if(FALSE == $this->analyze($template))
            {
                throw new SmartTplException("Template php function(s) not allowed: <pre>".var_export($this->disallowedItems,true)."<pre>");
            }
        }
        
        if ( !@file_exists( $template ) )
        {
            throw new SmartTplException("Template dosent exists: ".$template);
        }
        
        // check if the template needs to be compiled by Smarty
        if($smarty->smartCompileTemplate('tpl.' . $this->template . '.php'))
        {
            $content = $smarty->fetch( 'tpl.' . $this->template . '.php' );
            $this->write($content, $smarty->compile_dir.$this->template.'.php');
        }
        
        ob_start();
        include($smarty->compile_dir.$this->template.'.php');
        $this->tplBufferContent = ob_get_clean();      
    } 

    /**
     * Write smarty compiled template
     *
     * @param array $data
     */
    public function write( &$content , $d)
    {
        if(!$fp = @fopen($d, 'w'))
        {
           throw new SmartModelException("Cant open file to write: ". $d);
        }
         
        
        if( !@fwrite($fp, $content) )
        {
            throw new SmartModelException("Cant write file: ". $d);      
        }
        
        @fclose($fp);
        
        return TRUE;
    }
    
    /**
     * analyze allowed php tokens in template 
     *
     */
    private function analyze( & $template )
    {
        $code_is_valide = TRUE;

        include_once(SMART_BASE_DIR . "smart/includes/phpca/PHPCodeAnalyzer.php");
        $analyzer = new PHPCodeAnalyzer();
        $analyzer->source = file_get_contents( $template );
        $analyzer->analyze();
        
        foreach($analyzer->calledConstructs as $key => $val)
        {
            if(!in_array($key, $this->config['allowedConstructs']))
            {
                $this->disallowedItems[] = $key;
                $code_is_valide = FALSE;
            }
        }  

        return $code_is_valide;
    } 
}

?>