<?PHP
/**
 * patTemplate function that calculates the current time
 * or any other time and returns it in the specified format.
 *
 * $Id: Time.php,v 1.2 2004/04/06 18:50:25 schst Exp $
 *
 * @package     patTemplate
 * @subpackage  Functions
 * @author      Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate function that calculates the current time
 * or any other time and returns it in the specified format.
 *
 * $Id: Time.php,v 1.2 2004/04/06 18:50:25 schst Exp $
 *
 * @package     patTemplate
 * @subpackage  Functions
 * @author      Stephan Schmidt <schst@php.net>
 */
class patTemplate_Function_Custom extends patTemplate_Function
{
    var $_name  =   'Custom';
    var $_tmpl;
    
    function setTemplateReference( &$tmpl )
    {
        $this->_tmpl        =   &$tmpl;
    }

    function call( $params, $content )
    {
        //$this->_tmpl->prepareTemplate( $params['tpl_name'] );  
        //$this->_tmpl->addVar( $params['tpl_name'], 'hi', 'hello' );
        return 'gffgfgfgfgfggff';
    }
}
?>