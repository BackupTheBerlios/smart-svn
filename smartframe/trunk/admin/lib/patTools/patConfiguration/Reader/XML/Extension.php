<?PHP
/**
 * Base class for extensions for patConfiguration
 *
 * $Id: Extension.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * Base class for extensions for patConfiguration
 *
 * $Id: Extension.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader_XML_Extension
{
   /**
	* default namespace for this extension
	*
	* @var	string	$defaultNS
	*/
	var	$defaultNS	=	"ext";

   /**
	* reference to the main XML reader object
	*
	* @var	object
	*/
	var	$reader;

   /**
	* get the default namespace for this extension
	*
	* @access	public
	* @return	string	$defaultNS	default	namespace
	*/
	function getDefaultNS()
	{
		return	$this->defaultNS;
	}

   /**
	* store reference to reader object
	*
	* @access	public
	* @param	object patConfiguration_Reader_XML	&$reader	reader object
	*/
	function setConfigReference( &$reader )
	{
		$this->reader	=	&$reader;
	}
	
   /**
	* handle start element
	*
	* @abstract	
	* @param	int		$parser		resource id of the current parser
	* @param	string	$name		name of the element
	* @param	array	$attributes	array containg all attributes of the element
	*/
	function startElement( $parser, $name, $attributes )
	{
	}

   /**
	* handle end element
	*
	* @abstract
	* @param	int		$parser		resource id of the current parser
	* @param	string	$name		name of the element
	*/
	function endElement( $parser, $name )
	{
	}
}
?>