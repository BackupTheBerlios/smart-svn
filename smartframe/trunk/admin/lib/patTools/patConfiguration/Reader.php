<?PHP
/**
 * patConfiguration reader base class
 * 
 * $Id: Reader.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @version		2.0
 * @link		http://www.php-tools.net
 * @copyright	PHP Application Tools
 */

/**
 * patConfiguration reader base class
 * 
 * $Id: Reader.php,v 1.1 2004/02/29 16:56:46 schst Exp $
 *
 * @package		patConfiguration
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @version		2.0
 */
class	patConfiguration_Reader
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
	* load configuration from a file
	*
	* @access	public
	* @abstract
	* @param	string	$configFile		full path of the config file
	* @param	array	$options		various options, depending on the reader
	* @return	array	$config			complete configuration
	*/
	function loadConfigFile( $configFile, $options = array() )
	{
	}
}
?>