<?PHP
/**
 * patConfiguration
 *
 * $Id: patConfiguration.php,v 1.3 2004/07/15 19:32:02 schst Exp $
 *
 * Abstracts access to configuration files.
 * Currently XML, WDDX, INI and PHP files are
 * supported.
 *
 * WARNING:
 * patConfiguration 2.0 is not backwards compatible
 * to older versions. It supports patError and is now driverbased.
 * Furthermore, the extensions have been dropped.
 * They could possibly be integrated in the final release.
 *
 * @version		2.0
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @link		http://www.php-tools.net
 * @package		patConfiguration
 * @todo		implement extensions and adjust folder structure
 *				Reader/XML/Extension/DB.php
 * @todo		extensive testing
 */

/**
 * File not found
 */
define( 'PATCONFIGURATION_ERROR_FILE_NOT_FOUND', 20001 );

/**
 * File not readable
 */
define( 'PATCONFIGURATION_ERROR_FILE_NOT_READABLE', 20002 );

/**
 * Driver file not found
 */
define( 'PATCONFIGURATION_ERROR_UNKNOWN_DRIVER', 20003 );

/**
 * Driver file does not contain driver class
 */
define( 'PATCONFIGURATION_ERROR_CORRUPT_DRIVER', 20004 );

/**
 * driver is not working
 */
define( 'PATCONFIGURATION_ERROR_DRIVER_NOT_WORKING', 20005 );

/**
 * cache file could not be written
 */
define( 'PATCONFIGURATION_ERROR_CACHEDIR_NOT_WRITEABLE', 20006 );

/**
 * config file has invalid syntax
 */
define( 'PATCONFIGURATION_ERROR_CONFIG_INVALID', 20010 );

/**
 * config value without a name
 */
define( 'PATCONFIGURATION_WARNING_CONFIGVALUE_WITHOUT_NAME', 20020 );

/**
 * patConfiguration
 *
 * Main class, aggregates readers and writers
 * to access config files.
 *
 * @access		public
 * @version		2.0
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @package		patConfiguration
 */
class patConfiguration
{
   /**
	* information about the project
	* @var	array	$systemVars
	*/
	var	$systemVars		=	array(
									'appName'		=>	'patConfiguration',
									'appVersion'	=>	'2.0.0',
									'author'		=>	array(
															'Stephan Schmidt <schst@php-tools.net>'
															)
								);
   /**
	* array that stores configuration
	* @var	array	$conf
	*/
	var	$conf		=	array();

   /**
	* directory, where the configuration files are stored
	* @var	string
	*/
	var	$configDir;

   /**
	* directory where cache files are located
	* @var	string	$cacheDir
	*/
	var	$cacheDir		=	'cache';

   /**
	* directory where drivers are stored
	* @var	string	$driverDir
	*/
	var	$driverDir		=	NULL;

   /**
 	* drivers that have already been loaded
	* @var	array	$drivers
	*/
	var	$drivers		=	array();

   /**
 	* default encoding
	* @var	string	encoding
	*/
	var	$encoding		=	'ISO-8859-1';

   /**
	* options that can be used by the drivers
	*
	* @access	private
	* @var		array
	*/
	var $options = array();
	
   /**
	* constructor
	*
	* @access	public
	* @param	array	$options
	*/
	function patConfiguration( $options = array() )
	{
		$this->__construct( $options );
	}

   /**
	* constructor for PHP 5
	*
	* @access	public
	* @param	array	$options
	*/
	function __construct( $options = array() )
	{
		foreach( $options as $name => $value )
		{
			$method = 'set'.ucfirst( $name );
			if( is_callable( array( &$this, $method ) ) )
				$this->$method( $value );
			else
				$this->options[$name] = $value;
		}
	}

   /**
	* destructor
	*
	* @access	private
	*/
	function __destruct()
	{
		//	there's nothing to do here
	}

   /**
	* set the XML encoding
	*
	* @access	public
	* @param	string	$encoding
	*/
	function setEncoding( $encoding )
	{
		$this->encoding	=	$encoding;
	}

   /**
	* set the directory, where all config files are stored
	*
	* @access	public
	* @param	string	$configDir	name of the directory
	*/
	function setConfigDir( $configDir )
	{

		$this->configDir = $configDir;
		return true;
	}

   /**
	* get the directory, where all config files are stored
	*
	* @access	public
	* @return	string	$configDir	name of the directory
	*/
	function getConfigDir()
	{
		return	$this->configDir;
	}

   /**
	* set the directory, where all drivers are stored
	*
	* @access	public
	* @param	string	$driverDir	name of the directory
	*/
	function setDriverDir( $driverDir )
	{
		$this->driverDir = $driverDir;
	}

   /**
	* set the directory, where all cache files are stored
	*
	* @access	public
	* @param	string	$cacheDir	name of the directory
	*/
	function setCacheDir( $cacheDir )
	{
		$this->cacheDir	=	$cacheDir;
	}

   /**
	* get an option
	*
	* This is only used by the drivers.
	*
	* @access	public
	* @param	string	name of the option
	* @return	mixed
	*/
	function getOption( $name )
	{
		if( !isset( $this->options[$name] ) )
			return null;
		return $this->options[$name];
	}
	
   /**
	* load a configuration
	*
	* this loads any config file, alias for parseConfigFile
	*
	* Possible options are:
	* - mode (a|w)
	* - filetype
	*
	* @access	public
	* @param	string	$file	name of config file
	* @param	string	$mode	options for loading the config
	* @see	loadCachedConfig(), parseConfigFile()
	*/
	function loadConfig( $file, $options = array( 'mode' => 'w' ) )
	{
		//	older version accpeted only a string as mode parameter
		if( !is_array( $options ) )
			$options	=	array(
								'mode'	=>	$mode
							);

		//	do not append => clear old config
		if( $options['mode'] == 'w' )
			$this->conf			=	array();

		//	no filetype given, extract from filename
		if( isset( $options['filetype'] ) && !empty( $options['filetype'] )  )
			$filetype	=	$options['filetype'];
		else
			$filetype	=	$this->_getFiletype( $file );

		$path			=	$this->getFullPath( $file );

		if( patErrorManager::isError( $path ) )
			return	$path;

		$reader		=	&$this->_getDriver( $filetype, 'Reader' );

		if( patErrorManager::isError( $reader ) )
			return	$reader;

		$result		=	$reader->loadConfigFile( $path, $options );

		if( !is_array( $result ) )
			return	$result;

		if( empty( $this->conf ) )
			$this->conf	=	$result['config'];
		else
			$this->conf	=	array_merge( $this->conf, $result['config'] );

		return	true;
	}

   /**
	* load a configuration from a cache
	*
	* if cache is not valid, it will be updated automatically
	*
	* @access	public
	* @param	string	$file		name of config file
	* @param	string	$mode		options for loading the config
	* @return	mixed	$success	True on success, patError object otherwise.
	* @see	loadConfig()
	*/
	function loadCachedConfig( $file, $options = array( 'mode' => 'w' ) )
	{
		//	older version accepted only a string as mode parameter
		if( !is_array( $options ) )
			$options	=	array(
								'mode'	=>	$mode
							);

		//	do not append => clear old config
		if( $options['mode'] == 'w' )
			$this->conf			=	array();

		//	no filetype given, extract from filename
		if( isset( $options['filetype'] ) && !empty( $options['filetype'] )  )
			$filetype	=	$options['filetype'];
		else
			$filetype	=	$this->_getFiletype( $file );

		$path	=	$this->getFullPath( $file );
		if( patErrorManager::isError( $path ) )
			return	$path;

		if( $result	=	$this->loadFromCache( $path ) )
		{
			if( empty( $this->conf ) )
				$this->conf		=	$result;
			else
				$this->conf		=	array_merge( $this->conf, $result );

			return	true;
		}

		//	load the reader
		$reader		=	&$this->_getDriver( $filetype, 'Reader' );
		if( patErrorManager::isError( $reader ) )
			return $reader;

		$result		=	$reader->loadConfigFile( $path );

		if( !is_array( $result ) )
			return	$result;

		if( empty( $this->conf ) )
			$this->conf	=	$result['config'];
		else
			$this->conf	=	array_merge( $this->conf, $result['config'] );

		if( $result['cacheAble'] === true )
			$this->writeCache( $path, $result['config'], $result['externalFiles'] );

		return	true;
	}

   /**
	* parse a configuration file
	*
	* please use loadConfig instead
	*
	* @access	public
	* @param	string	$file	name of the configuration file
	* @param	string	$mode	options for loading the config
	* @see	loadConfig(), loadCachedConfig()
	* @deprecated	since v2.0
	*/
	function parseConfigFile( $file, $options = array( 'mode' => 'w' ) )
	{
		return	$this->loadConfig( $file, $options );
	}

   /**
	* get the full path of a file
	*
	* @access	public
	* @param	string	$file		filename
	* @param	string	$relative	get path relative to this file
	*/
	function getFullPath( $file, $relative = NULL, $check = true )
	{
		$inConfigDir	=	false;
		//	is it a relative path
		if( strncmp( $file, '/', 1 ) != 0 )
		{
			if( $relative !== NULL )
			{
				$path		=	dirname( $relative ) . '/' . $file;
			}
			//	no other file and no absolute path => the file should be located in config Dir
			else
			{
				$path			=	$file;
				$inConfigDir	=	true;
			}
		}
		//	absolute path
		else
		{
			$path			=	substr( $file, 1 );
			$inConfigDir	=	true;
		}
		if( $inConfigDir )
		{
			if( !empty( $this->configDir ) )
				$fullPath	=	$this->configDir.'/'. $path;
			else
				$fullPath	=	$path;
		}
		else
			$fullPath	=	$path;

		if( $check )
		{
			$realPath	=	realpath( $fullPath );
			if( empty( $realPath ) )
			{
				return patErrorManager::raiseError(
											PATCONFIGURATION_ERROR_FILE_NOT_FOUND,
											'Could not resolve full path for path: \''.$file.'\' (relative to \''.$relative.'\') - please check the path syntax.'
											 );
			}

			if( !is_readable( $realPath ) )
			{
				return patErrorManager::raiseError(
											PATCONFIGURATION_ERROR_FILE_NOT_READABLE,
											$file.' is not readable'
											 );
			}
			return	$realPath;
		}

		return	$fullPath;
	}

   /**
	* get a driver
	*
	* will load driver, if not already loaded
	*
	* @access	private
	* @param	string	$filetype	type of file that should be read/written
	* @param	string	$mode		mode of the driver (reader|writer)
	*/
	function &_getDriver( $filetype, $mode = 'Reader' )
	{
		if( !isset( $this->drivers[$filetype] ) )
			$this->drivers[$filetype]	=	array();

		if( !isset( $this->drivers[$filetype][$mode] ) )
			$this->drivers[$filetype][$mode]	=	&$this->_loadDriver( $filetype, $mode );

		return	$this->drivers[$filetype][$mode];
	}

   /**
	* load a driver
	*
	* @access	private
	* @param	string	$filetype	type of file that should be read/written
	* @param	string	$mode		mode of the driver (reader|writer)
	*/
	function &_loadDriver( $filetype, $mode = 'Reader' )
	{
		// include the base class
		$baseFile		=	sprintf( '%s/%s.php', $this->getDriverDir(), $mode );
		include_once $baseFile;


		$driverFile		=	sprintf( '%s/%s/%s.php', $this->getDriverDir(), $mode, strtoupper( $filetype ) );
		$driverClass	=	sprintf( 'patConfiguration_%s_%s', $mode, strtoupper( $filetype ) );

		//	check whether writer file exists
		if( !file_exists( $driverFile ) )
		{
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_UNKNOWN_DRIVER,
										'Could not load $mode for \''.$filetype.'\'.'
										 );
		}
		//	include writer
		@include_once( $driverFile );

		//	check whether writer class exists
		if( !class_exists( $driverClass ) )
		{
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CORRUPT_DRIVER,
										'$mode for \''.$filetype.'\' seems to be corrupt.'
										 );
		}

		//	create Writer object
		$driver		=	&new	$driverClass;

		if( method_exists( $driver, 'setConfigReference' ) )
			$driver->setConfigReference( $this );

		return	$driver;
	}

   /**
	* load cache
	*
	* @access	private
	* @param	string	$file	filename
	* @return	mixed	$result	config on success, false otherwise
	*/
	function loadFromCache( $file )
	{
		$cacheFile	=	$this->cacheDir . '/' . md5( $file ) . '.cache';

		if( !file_exists( $cacheFile ) )
			return	false;

		$cacheTime		=	filemtime( $cacheFile );

		if( filemtime( $file ) > $cacheTime )
			return	false;

		if( !$fp = @fopen( $cacheFile, 'r' ) )
			return	false;

		$result		=	false;
		flock( $fp, LOCK_SH );
		while( !feof( $fp ) )
		{
			$line	=	trim( fgets( $fp, 4096 ) );
			list( $action, $param )	=	explode( '=', $line, 2 );

			switch( $action )
			{
				case	'checkFile':
					if( filemtime( $param ) > $cacheTime )
					{
						flock( $fp, LOCK_UN );
						fclose( $fp );
						return	false;
					}
					break;
				case	'startCache':
					$result		=	unserialize( fread( $fp, filesize( $cacheFile ) ) );
					break 2;
				default:
					flock( $fp, LOCK_UN );
					fclose( $fp );
					return	false;
					break;
			}
		}
		flock( $fp, LOCK_UN );
		fclose( $fp );
		return	$result;
	}

   /**
	* write cache
	*
	* @access	private
	* @param	string	$file	filename
	* @param	array	$config	configuration
	* @param	array	$externalFiles	list of files used
	*/
	function writeCache( $file, $config, $externalFiles )
	{
		$cacheData	=	serialize( $config );
		$cacheFile	=	$this->cacheDir . '/' . md5( $file ) . '.cache';

		$fp			=	@fopen( $cacheFile, 'w' );
		if( !$fp )
		{
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CACHEDIR_NOT_WRITEABLE,
										'Could not write cache file in cache directory \''.$this->cacheDir.'\'.'
										 );
		}
		flock( $fp, LOCK_EX );

		$cntFiles	=	count( $externalFiles );
		for( $i = 0; $i < $cntFiles; $i++ )
			fputs( $fp, 'checkFile='.$externalFiles[$i].'\n' );

		fputs( $fp, 'startCache=yes'.'\n' );
		fwrite( $fp, $cacheData );
		flock( $fp, LOCK_UN );
		fclose( $fp );

		$oldMask	=	umask( 0000 );
		chmod( $cacheFile, 0666 );
		umask( $oldMask );

		return	true;
	}

   /**
	* write a configfile
	*
	*
	* @access	public
	* @param	string	$filename	name of the configfile
	* @param	string	$format		format of the config file
	* @param	array	$options	options, see the writer driver for details
	*/
	function writeConfigFile( $filename, $options = NULL, $oldOptions = NULL )
	{
		//	older versions needed the filetype as secand parameter
		if( !is_array( $options ) )
		{
			$options	=	array(
									'filetype'	=>	$options
								);
		}
		//	options had to be specified as third param prior to version 2.0
		if( is_array( $oldOptions ) )
		{
			$options	=	array_merge( $oldOptions, $options );
		}

		//	no filetype given, extract from filename
		if( isset( $options['filetype'] ) && !empty( $options['filetype'] )  )
			$filetype	=	$options['filetype'];
		else
			$filetype	=	$this->_getFiletype( $filename );

		$writer		=	&$this->_getDriver( $filetype, 'Writer' );

		if( !is_object( $writer ) )
			return	$writer;

		//	seriaize the content
		$content	=	$writer->serializeConfig( $this->conf, $options );

		if( $content )
		{
			$file = $this->getFullPath( $filename, NULL, false );
			if( patErrorManager::isError( $file ) )
				return $file;
				
			$fp		=	@fopen( $file, 'w' );
			if( $fp )
			{
				flock( $fp, LOCK_EX );
				fputs( $fp, $content );
				flock( $fp, LOCK_UN );
				fclose( $fp );
				$oldMask	=	umask( 0000 );
				chmod( $file, 0666 );
				umask( $oldMask );
			}
		}
	}

   /**
	* get the filetype based on a filename
	*
	* @access	private
	* @param	string	$filename
	* @return	string	$filetype
	*/
	function _getFileType( $filename )
	{
		//	no filetype given, extract from filename
		return	substr( strrchr( $filename, '.' ), 1 );
	}

   /**
	* convert a string variable to any type
	*
	* @access	private
	* @param	string	$value	value that should be converted
	* @param	string	$type	type of the value (string, bool, integer, double)
	* @return	mixed	$value
	*/
	function convertValue( $value, $type = 'string' )
	{
		switch( $type )
		{
			//	string
			case	'string':
				settype( $value, 'string' );
				break;

			//	boolean
			case	'boolean':
			case	'bool':
				if( $value == 'true' || $value == 'yes' || $value == 'on' )
					$value	=	true;
				else
					$value	=	false;
				break;

			//	integer
			case	'integer':
			case	'int':
				settype( $value, 'integer' );
				break;

			//	double
			case	'float':
			case	'double':
				settype( $value, 'double' );
				break;

			//	array
			case	'array':
				if( !is_array( $value ) )
				{
					if( trim( $value ) )
						$value	=	array( $value );
					else
						$value	=	array();
				}
				break;
		}
		return	$value;
	}

   /**
	* returns a configuration value
	*
	* if no path is given, all config values will be returnded in an array
	*
	* @access	public
	* @param	string	$path	path, where the value is stored
	* @return	mixed	$value	value
	*/
	function getConfigValue( $path = '' )
	{
		if( $path == '' )
			return	$this->conf;

		if( strstr( $path, '*' ) )
		{
			$path		=	str_replace( '.', '\.', $path );
			$path		=	'^'.str_replace( '*', '.*', $path ).'$';
			$values		=	array();
			foreach( $this->conf as $key => $value )
			{
				if( eregi( $path, $key ) )
					$values[$key]	=	$value;
			}
			return	$values;
		}

		//	check wether a value of an array was requested
		if( $index	= strrchr( $path, '[' ) )
		{
			$path		=	substr( $path, 0, strrpos( $path, '[' ) );
			$index		=	substr( $index, 1, ( strlen( $index ) - 2 ) );
			$tmp		=	$this->getConfigValue( $path );

			return	$tmp[$index];
		}

		if( isset( $this->conf[$path] ) )
			return	$this->conf[$path];

		return	false;
	}

   /**
	* set a config value
	*
	* @access	public
	* @param	string	$path	path, where the value will be stored
	* @param	mixed	$value	value to store
	*/
	function setConfigValue( $path, $value, $type = 'leave' )
	{
		$this->conf[$path]			=	$this->convertValue( $value, $type );
		$this->currentConf[$path]	=	$this->convertValue( $value, $type );
	}

   /**
	* sets several config values
	*
	* @access	public
	* @param	array	$values		assoc array containg paths and values
	*/
	function setConfigValues( $values )
	{
		if( !is_array( $values ) )
			return	false;
		foreach( $values as $path => $value )
			$this->setConfigValue( $path, $value );
	}

   /**
	* clears a config value
	*
	* if no path is given, the complete config will be cleared
	*
	* @access	public
	* @param	string	$path	path, where the value is stored
	*/
	function	clearConfigValue( $path = '' )
	{
		if( $path == '' )
		{
			$this->conf		=	array();
			return	true;
		}

		if( strstr( $path, '*' ) )
		{
			$path		=	str_replace( '.', '\.', $path );
			$path		=	'^'.str_replace( '*', '.*', $path ).'$';
			$values		=	array();
			reset( $this->conf );
			while( list( $key, $value ) = each( $this->conf ) )
			{
				if( eregi( $path, $key ) )
					unset( $this->conf[$key] );
			}
			return	true;
		}

		if( !isset( $this->conf[$path] ) )
			return	false;

		unset( $this->conf[$path] );
		return	true;
	}


	/**
	* get driver directory
	*
	* @access	public
	* @return	string	$driverDir	driver directory
	*/
	function getDriverDir()
	{
		if( $this->driverDir == NULL )
		{
			$currentDir			=	dirname( __FILE__ );
			$this->driverDir	=	$currentDir . '/patConfiguration';
		}
		return	$this->driverDir;
	}
}
?>