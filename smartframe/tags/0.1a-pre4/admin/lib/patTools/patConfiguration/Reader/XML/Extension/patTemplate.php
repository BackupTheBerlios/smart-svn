<?PHP
/**
 * patTemplate Extension for XML Reader
 *
 * $Id: patTemplate.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patTemplate Extension for XML Reader
 *
 * $Id: patTemplate.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader_XML_Extension_patTemplate extends patConfiguration_Reader_XML_Extension
{
   /**
	* default namespace for this extension
	* @var	string	$defaultNS
	* @access	private
	*/
	var	$defaultNS	=	"tmpl";

   /**
	* array containing all templates
	* @var	array	$templates
	* @access	private
	*/
	var	$templates	=	array();

   /**
	* stack for all attributes
	* @var	array	$attStack
	* @access	private
	*/
	var	$attStack	=	array();

   /**
	* handle start element
	*	
	* @param	int		$parser		resource id of the current parser
	* @param	string	$name		name of the element
	* @param	array	$attributes	array containg all attributes of the element
	*/
	function startElement( $parser, $name, $attributes )
	{
		//	store all attributes as they are needed by endElement
		array_push( $this->attStack, $attributes );
		
		switch( strtolower( $name ) )
		{
			case	"template":
				$this->currentName	=	$attributes["name"];
				$this->templates[$this->currentName]	=	new	patTemplate();
				break;
		}
	}

   /**
	* handle end element
	*	
	* @param	int		$parser		resource id of the current parser
	* @param	string	$name		name of the element
	*/
	function endElement( $parser, $name )
	{
		//	get Attributes from stack
		$attributes	=	array_pop( $this->attStack );
		
		$name	=	strtolower( $name );
		switch( $name )
		{
			case	"template":
				$this->config->setTypeValue( $this->templates[$this->currentName], "leave", $this->currentName );
				break;

			//	set basedir
			case	"basedir":
				$this->templates[$this->currentName]->setBaseDir( $this->config->getData() );
				break;

			//	add template
			case	"addtemplate":
				$this->templates[$this->currentName]->addTemplate( $attributes["name"], $this->config->getData() );
				break;

			//	add local variable
			case	"addvar":
				$this->templates[$this->currentName]->addVar( $attributes["template"], $attributes["name"], $this->config->getData() );
				break;

			//	add global variable
			case	"addglobal":
				$this->templates[$this->currentName]->addGlobalVar( $attributes["name"], $this->config->getData() );
				break;

			//	read from file
			case	"readfromfile":
				$this->templates[$this->currentName]->readTemplatesFromFile( $this->config->getData() );
				break;
		}
	}
}
?>