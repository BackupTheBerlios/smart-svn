<?php
/**
 * patSession 
 * 
 * $Id: patSession.php,v 1.4 2004/05/04 22:06:44 argh Exp $
 *
 * @version	1.0.0
 * @package patSession
 * 
 * @author gERD Schaufelberger <gerd@php-tools.net>
 * @copyright 2004 http://www.php-tools.net
 * @license LGPL
 */
 
/**
 * error definition: session not active
 */									
define( 'PATSESSION_ERROR_NOT_ACTIVE', 1 );

/**
 * global values used by the factory-methods
 */ 
$GLOBALS['_patSession']	=	array( 
									'static'	=>	null, 
									'singleton'	=>	null,
									'factory'	=>	array()
								);

/**
 * patSession allows easy access to session values
 * - usable in static or object mode
 * - support for singleton
 *
 * @version	1.0.0
 * @package patSession
 * 
 * @author gERD Schaufelberger <gerd@php-tools.net>
 * @copyright 2004 http://www.php-tools.net
 * @license LGPL
 */
class patSession
{
   /**
    * Singleton
	* create a single instance of patSession driver
	*
	* @static
	* @access public
	* @param string $id name-prefix used for internal storage of session-data
	* @param string $storage named storage driver
	* @param array $options optional parameter passed to the storage driver
	* @return object $session patSession object
	*/
    function &singleton( $id = 'session', $storage = 'Native', $options = array() )
    {
		// check if object was already created
		if( $GLOBALS['_patSession']['singleton'] )
		{
			return $GLOBALS['_patSession']['singleton'];
		}
		
		$driver	=	strtolower(  $storage );
		$GLOBALS['_patSession']['singleton']	=&	patSession::createDriver( $id, $storage, $options );
		
        return $GLOBALS['_patSession']['singleton'];
    }

   /**
	* factory
	* Allows to create multiple instances of patSession
	* Each instance must differ in name of prefix, otherwise this function return a previously created object.
	*
	* @static
	* @access private
	* @param string $id name-prefix used for internal storage of session-data
	* @param string $storage named storage driver
	* @param array $options optional parameter passed to the storage driver
	* @return object $session patSession object
	*/
    function &create( $id = 'session', $storage = 'native', $options = array() )
    {
		if( isset( $GLOBALS['_patSession']['factory'][$id] ) )
		{
			return $GLOBALS['_patSession']['factory'][$id];
		}
		
		$driver	=	strtolower(  $storage );
		$GLOBALS['_patSession']['factory'][$storage . '_' . $id]	=&	patSession::createDriver( $id, $storage, $options );
		
		return $GLOBALS['_patSession']['factory'][$storage . '_' . $id];
    }

   /**
	* load driver and create a instance of it
	*
	* @static
	* @access private
	* @param string $id session-id
	* @param string $storage named storage-driver
	* @param array $options optional parameter passed to the storage driver
	* @return boolean $result true on success
	*/
    function &createDriver( $id, $storage, &$options )
    {
		$driver	=	ucfirst(  $storage );
		$name	=	'patSession_Storage_' . $storage;
		
		if( !class_exists( 'patSession_Storage' ) )
		{
			include_once dirname( __FILE__ ) . '/patSession/Storage.php';
		}
		
		if( !class_exists( 'patSession_Storage_' . $storage ) )
		{
			include_once dirname( __FILE__ ) . '/patSession/Storage/' . $storage . '.php';
		}
		
		return	new $name( $id, $options );
    }
} 
?>