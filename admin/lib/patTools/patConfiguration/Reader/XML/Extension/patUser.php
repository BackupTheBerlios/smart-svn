<?PHP
/**
 * patUser Extension for XML Reader
 *
 * $Id: patUser.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patUser Extension for XML Reader
 *
 * $Id: patUser.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Reader_XML_Extension_patUser extends patConfiguration_Reader_XML_Extension
{
   /**
	* default namespace for this extension
	* @var	string	$defaultNS
	* @access	private
	*/
	var	$defaultNS	=	"user";

   /**
	* array containing all connections
	* @var	array	$dbcs
	* @access	private
	*/
	var	$users	=	array();

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
		switch( strtolower( $name ) )
		{
			case	"user":
				$this->currentName	=	$attributes["name"];
				break;

			case	"timestamp":
				$this->users[$this->currentName]["lifetime"]		=	$attributes["lifetime"];
		}
	}

   /**
	*	handle end element
	*	
	*	@param	int		$parser		resource id of the current parser
	*	@param	string	$name		name of the element
	*/
	function endElement( $parser, $name )
	{
		$name	=	strtolower( $name );
		switch( $name )
		{
			case	"user":
				$this->users[$this->currentName][user]			=	new	patUser( $this->users[$this->currentName][dbc] );

				if( $this->users[$this->currentName][tablename] )
					$this->users[$this->currentName][user]->setTable( $this->users[$this->currentName][tablename] );
				
				if( $this->users[$this->currentName][primary] )
					$this->users[$this->currentName][user]->setPrimary( $this->users[$this->currentName][primary] );
				
				if( $this->users[$this->currentName][timestamp] )
					$this->users[$this->currentName][user]->setTimestamp( $this->users[$this->currentName][timestamp], $this->users[$this->currentName]["lifetime"] );

				$this->config->setTypeValue( $this->users[$this->currentName][user], "leave", $this->currentName );
				break;

			default:
				$this->users[$this->currentName][$name]	=	$this->config->getData();
				break;
		}
	}
}
?>