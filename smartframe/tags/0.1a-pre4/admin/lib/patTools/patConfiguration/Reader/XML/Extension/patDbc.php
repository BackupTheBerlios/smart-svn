<?PHP
/**
 * patDbc Extension for XML Reader
 *
 * $Id: patDbc.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patDbc Extension for XML Reader
 *
 * $Id: patDbc.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @todo		change API => endElement must return value that should be set
 */
class patConfiguration_Reader_XML_Extension_patDbc extends patConfiguration_Reader_XML_Extension
{
   /**
	* default namespace for this extension
	*
	* @var	string	$defaultNS
	*/
	var	$defaultNS	=	"dbc";

	/**
	* array containing all connections
	* @var		array	$dbcs
	* @access	private
	*/
	var	$dbcs	=	array();

	/**
	* array containing all attributes of the current tag
	* @var	array	$currentAttributes
	* @access	private
	*/
	var	$currentAttributes	=	array();

	
   /**
	*	handle start element
	*	
	*	@param	int		$parser		resource id of the current parser
	*	@param	string	$name		name of the element
	*	@param	array	$attributes	array containg all attributes of the element
	*/
	function startElement( $parser, $name, $attributes )
	{
		switch( strtolower( $name ) )
		{
			case	"connection":
				$this->currentName	=	$attributes["name"];
				break;
			case	"result":
				$this->currentAttributes	=	$attributes;
				break;
		}
	}

   /**
	* handle end element
	*	
	* @param	int		$parser		resource id of the current parser
	* @param	string	$name		name of the element
	*/
	function	endElement( $parser, $name )
	{
		$name	=	strtolower( $name );
		switch( $name )
		{
			case	"connection":
				switch( $this->dbcs[$this->currentName]["type"] )
				{
					case	"mysql":
						$this->dbcs[$this->currentName][dbc]	=	new	patMySqlDbc( $this->dbcs[$this->currentName][host], $this->dbcs[$this->currentName][db], $this->dbcs[$this->currentName][user], $this->dbcs[$this->currentName][pass] );

						$this->config->setTypeValue( $this->dbcs[$this->currentName][dbc], "leave", $this->currentName );
						break;
				}
				break;
			case	"result":
				if( !$this->dbcs[$this->currentAttributes["dbc"]][dbc] )
					return	false;

				$result		=	$this->dbcs[$this->currentAttributes["dbc"]][dbc]->query( $this->config->getData() );
				switch( $this->currentAttributes["mode"] )
				{
					case	"array":
						$data	=	$result->get_result();
						$this->config->setTypeValue( $data, "leave", $this->currentAttributes["name"] );

						$result->free();
						break;
				}
				break;

			default:
				$this->dbcs[$this->currentName][$name]	=	$this->config->getData();
				unset( $this->data );
				break;
		}
	}
}
?>