<?PHP
/**
 * patConfiguration Writer
 *
 * abstract base class for config writers
 *
 * $Id: Writer.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @abstract
 * @package		patConfiguration
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patConfiguration Writer
 *
 * abstract base class for config writers
 *
 * $Id: Writer.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @author		Stephan Schmidt <schst@php-tools.net>
 */
class patConfiguration_Writer
{
   /**
	* reference to patConfig object
	* @var	object	$configObj
	*/
	var	$configObj	=	NULL;
	
   /**
	* set reference to the patConfiguration object
	*
	* @access	public
	* @param	object	&$config
	*/
	function setConfigReference( &$config )
	{
		$this->configObj	=	&$config;
	}
	
   /**
	* serialize the config
	*
	* @abstract
	* @access	public
	* @param	array	$config		config to serialize
	* @param	array	$options	options for the serialization
	* @return	string	$content	xml representation
	*/
	function	serializeConfig( $config, $options )
	{
	}
}
?>