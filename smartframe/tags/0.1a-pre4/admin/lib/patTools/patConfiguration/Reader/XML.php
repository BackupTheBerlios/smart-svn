<?PHP
/**
 * patConfiguration XML Reader
 *
 * used by the patConfiguration object to read XML config files
 *
 * $Id: XML.php,v 1.10 2004/07/20 21:16:10 schst Exp $
 * 
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 */

/**
 * patConfiguration XML Reader
 *
 * used by the patConfiguration object to read XML config files
 *
 * $Id: XML.php,v 1.10 2004/07/20 21:16:10 schst Exp $
 * 
 * @package		patConfiguration
 * @subpackage	Reader
 * @author		Stephan Schmidt <schst@php-tools.net>
 * @todo		This class needs a lot of cleanup and refactoring, as there's a huge load of code duplication
 */
class patConfiguration_Reader_XML extends patConfiguration_Reader
{
   /**
	* store path information
	* @var	array
	*/
	var	$path		=	array();
	
   /**
	* array that stores configuration from the current file
	* @var	array
	*/
	var	$conf		=	array();

   /**
	* array that stores extensions
	* @var	array
	*/
	var	$extensions	=	array();

   /**
	* array that stores all xml parsers
	* @var	array
	*/
	var	$parsers	=	array();

   /**
	* stack of the namespaces
	* @var	array
	*/
	var	$nsStack	=	array();

   /**
	* stack for tags that have been found
	* @var	array
	*/
	var	$tagStack	=	array();

   /**
	* stack of values
	* @var	array
	*/
	var	$valStack	=	array();

   /**
	* current depth of the stored values, i.e. array depth
	* @var	int
	*/
	var	$valDepth	=	1;

   /**
	* current CDATA found
	* @var	string
	*/
	var	$data		=	array();
	
   /**
	* all open files
	* @var	array
	*/
	var	$xmlFiles	=	array();

   /**
	* treatment of whitespace
	* @var	string
	*/
	var	$whitespace	=	array( 'default' );

   /**
	* current namespace for define
	* @var	string
	*/	
	var	$currentNamespace	=	'_none';

   /**
	* current tag for define
	* @var	string
	*/	
	var	$currentTag	=	false;

   /**
 	* stack for define tags
	* @var	array
	*/
	var	$defineStack		=	array();

   /**
	* files that have been included
	* @var	array	$includedFiles
	*/
	var	$includedFiles		=	array();

   /**
	* list of all files that were needed
	* @var	array	$externalFiles
	*/
	var	$externalFiles		=	array();

   /**
	* load configuration from a file
	*
	* @access	public
	* @param	string	$configFile		full path of the config file
	* @param	array	$options		various options, depending on the reader
	* @return	array	$config			complete configuration
	*/
	function loadConfigFile( $configFile, $options = array() )
	{
		$this->path				=	array();
		$this->conf				=	array();

/*
		//	check, whether extension have been supplied
		if( isset( $options['extensions'] ) && is_array( $options['extensions'] ) )
		{
			foreach( $options['extension'] as $ns => $extension )
				$this->addExtension( $extension, $ns );
		}
		
		//	check, whether defined tags have been supplied
		if( isset( $options['definedTags'] ) && is_array( $options['definedTags'] ) )
		{
			foreach( $options['extension'] as $ns => $extension )
		}
*/
			
		$result = $this->parseXMLFile( $configFile );

		if( patErrorManager::isError( $result ) )
			return $result;
		
		return	array(
						'config'			=>	$this->conf,
						'externalFiles'		=>	$this->externalFiles,
						'cacheAble'			=>	true
					);
						
	}
	
   /**
	* set defined tags
	*
	* @access	public
	* @param	array
	*/
	function setDefinedTags( $tags, $ns = NULL )
	{
		if( $ns == NULL )
			$this->definedTags		=	$tags;
		else
			$this->definedTags[$ns]	=	$tags;
		return true;
	}

   /**
	* add an extension
	*
	* @access	public
	* @param	object patConfigExtension	extension that should be added
	* @param	string						namespace for this extension (if differs from default ns)
	* @todo		This does not work in version 2.0.0, needs to be fixed
	*/	
	function addExtension( &$ext, $ns = '' )
	{
		if( $ns == '' )
			$ns	=	$ext->getDefaultNS();

		$ext->setConfigReference( $this );
		$this->extensions[$ns]	=	&$ext;
		return true;
	}

   /**
	* handle start element
	* if the start element contains a namespace calls the eppropriate handler
	* 
	* @param	resource	resource id of the current parser
	* @param	string		name of the element
	* @param	array		array containg all attributes of the element
	* @todo		check for namespace before switch/case on built-in tags
	* @todo		allow type="object" in configValue tags
	*/
	function startElement( $parser, $name, $attributes )
	{
		//	separate namespace and local name
		$tag	=	explode( ':', $name, 2 );

		if( count( $tag ) == 2 )
		{
			list( $ns, $name )	=	$tag;
		}
		else
			$ns					=	false;
		
		array_push( $this->tagStack, $name );
		array_push( $this->nsStack, $ns );
		
		$tagDepth				=	count( $this->tagStack );
		$this->data[$tagDepth]	=	NULL;
		
		//	inherit whitespace treatment
		if( !isset( $attributes['xml:space'] ) )
			$attributes['xml:space']	=	$this->whitespace[( $tagDepth - 1 )];

		//	store whitespace treatment
		array_push( $this->whitespace, $attributes['xml:space'] );
		
		//	check if namespace exists and an extension for the ns exists
		if( $ns !== false && isset( $this->extensions[$ns] ) )
		{
			$this->extensions[$ns]->startElement( $parser, $name, $attributes );
		}
		//	No namespace, or no extension set => handle internally
		else
		{
			switch( strtolower( $name ) )
			{
				//	configuration
				case 'configuration':
					break;

				//	define
				case 'define':
					//	type = string is default
					if( !isset( $attributes['type'] ) )
						$attributes['type']		=	'string';

					//	use 'ns' or 'namespace'
					if( isset( $attributes['ns'] ) )
						$this->_defineNamespace( $attributes['ns'] );
					elseif( isset( $attributes['namespace'] ) )
						$this->_defineNamespace( $attributes['namespace'] );
						
					//	define a new tag
					elseif( isset( $attributes['tag'] ) )
						$this->_defineTag( $attributes );
					elseif( isset( $attributes['attribute'] ) )
						$this->_defineAttribute( $attributes );					
					break;

				//	get a configValue that has been defined
				case 'getconfigvalue':
					$this->appendData( $this->getConfigValue( $attributes['path'] ) );
					break;
					
				//	load an extension
				/*
				case	'extension':
					if( isset( $attributes['file'] ) )
					{
						$includeDir		=	$this->configObj->getIncludeDir();
						$fpath			=	( $includeDir ) ? $includeDir.'/'.$attributes['file'] : $attributes['file'];
						if( !file_exists( $fpath ) )
						{

							$this->configObj->_raiseError( 'XML_Reader::startElement', "Could not include PHP file $fpath." );
						}
						else
							include_once( $fpath );
					}
					if( isset( $attributes['name'] ) )
					{
						if( class_exists( $attributes['name'] ) )
						{
							//	create new extension
							$ext	=	new	$attributes['name'];
	
							//	get namespace
							if( isset( $attributes['ns'] ) )
								$ns	=	$attributes['ns'];
							else
								$ns	=	$ext->getDefaultNS();
					
							//	add extension
							$ext->setConfigReference( $this );
							$this->extensions[$ns]	=	$ext;
						}
						else
							$this->configObj->_raiseError( 'XML_Reader::startElement', 'Could not instantiate extension class for '.$attributes['name'].'.' );
					}
					break;
					*/

				case 'xinc':
					$this->_xInclude( $attributes );					
					break;

				//	path
				case 'path':
					$this->addToPath( $attributes['name'] );
					break;

				//	Config Value Tag found
				case 'configvalue':
					//	store name and type of value
					$val	=	@array(	'type'		=>	$attributes['type'],
										'name'		=>	$attributes['name'] );
										
					$this->valDepth	=	array_push( $this->valStack, $val );
					break;

				//	any other tag found
				default:
					//	use default namespace, if no namespace is given
					if( $ns === false )
						$ns	=	'_none';
					
					//	check, whether the namespace has been defined.						
					if( !isset( $this->definedTags[$ns] ) )
					{
						$this->addToPath( $name );
						break;
					}

					//	check whether the tag has been defined 					
					if( !isset( $this->definedTags[$ns][$name] ) )
					{
						$this->addToPath( $name );
						break;
					}
	
					$def		=	$this->definedTags[$ns][$name];
					$type		=	$def['type'];
					$tagName	=	$def['name'];
					
					if( $tagName == '_attribute' )
					{
						$tagName	=	$attributes[$def['nameAttribute']];
					}
						
					//	store name and type of value
					$val	=	array(	'type'		=>	$type,
										'name'		=>	$tagName
									);

					/**
					 * set the classname if creating an object
					 */
					if( $type == 'object' )
					{
						if( isset( $def['instanceof'] ) )
							$val['instanceof'] = $def['instanceof'];
						else
							$val['instanceof'] = 'stdClass';
					}

					if( isset( $def['value'] ) )
					{
						if( isset( $attributes[$def['value']] ) )
							$val['value'] = $attributes[$def['value']];
					}
					//	check for attributes
					elseif( isset( $def['attributes'] ) && is_array( $def['attributes'] ) )
					{
						//	value must be an array
						$value	=	array();
						//	check, which attributes exist
						foreach( $def['attributes'] as $name => $attDef )
						{
							if( isset( $attributes[$name] ) )
								$value[$name]	=	$this->configObj->convertValue( $attributes[$name], $attDef['type'] );
							elseif( isset( $attDef['default'] ) )
								$value[$name]	=	$this->configObj->convertValue( $attDef['default'], $attDef['type'] );
						}
						$val['value']	=	$value;
					}
					
					$this->valDepth	=	array_push( $this->valStack, $val );

					if( isset( $def['content'] ) )
					{
						$val = array(
										'type' => 'auto',
										'name' => $def['content'],
										'_isAuto' => true
									);

						$this->valDepth	=	array_push( $this->valStack, $val );
					}
					break;
			}
		}
	}
		
   /**
	* handle end element
	*
	* if the end element contains a namespace calls the eppropriate handler
	* 
	* @param	resource	resource id of the current parser
	* @param	string		name of the element
	* @todo		check for namespace, before switch/case on built-in tags
	*/
	function endElement( $parser, $name )
	{
		//	remove whitespace treatment from stack
		$whitespace = array_pop( $this->whitespace );

		//	get the data of the current tag
		$tagDepth = count( $this->tagStack );

		$this->currentData	=	$this->data[$tagDepth];
		
		switch( $whitespace )
		{
			case	'preserve':
				break;
			case	'strip':
				$this->currentData	=	trim( preg_replace( '/\s\s*/', ' ', $this->currentData ) );
				break;
			case	'default':
			default:
				$this->currentData	=	trim( $this->currentData );
				break;
		}
		
		//	delete the data before returning it
		$this->data[$tagDepth]	=	'';

		//	remove namespace from stack
		$ns				=	array_pop( $this->nsStack );
		//	remove tag from stack
		$name			=	array_pop( $this->tagStack );
		
		//	check if namespace exists and an extension for the ns exists
		if( $ns !== false && isset( $this->extensions[$ns] ) )
		{
			$this->extensions[$ns]->endElement( $parser, $name );
		}
		//	No namespace => handle internally
		else
		{
			//	use default namespace, if no namespace is given
			if( $ns === false )
				$ns	=	'_none';

			switch( strtolower( $name ) )
			{
				//	configuration / extension
				case 'configuration':
				case 'getconfigvalue':
				case 'extension':
				case 'xinc':
					break;

				case 'define':
					$mode	=	array_pop( $this->defineStack );
					switch( $mode )
					{
						case	'ns':
							$this->currentNamespace	=	'_none';
							break;
						case	'tag':
							$this->currentTag		=	false;
							break;	
					}
					break;

				//	path
				case 'path':
					$this->removeLastFromPath();
					break;

				//	config value
				case 'configvalue':
					//	get last name and type
					$val	=	array_pop( $this->valStack );
									
					//	decrement depth, as one tag was removed from
					//	stack
					$this->valDepth--;

					//	if no value was found (e.g. other tags inside)
					//	use CDATA that was found between the tags
					if( !isset( $val['value'] ) )
						$val['value']	=	$this->getData();
						
					$this->setTypeValue( $val['value'], $val['type'], $val['name'] );
					break;

				/**
				 * predefined tags
				 */
				default:
					//	check, whether the namespace has been defined.						
					if( !isset( $this->definedTags[$ns] ) )
					{
						//	if data was found => store it
						if( $data = $this->getData() )
							$this->setTypeValue( $data );

						//	shorten path
						$this->removeLastFromPath();
						break;
					}

					//	check whether the tag has been defined 					
					if( !isset( $this->definedTags[$ns][$name] ) )
					{
						//	if data was found => store it
						if( $data = $this->getData() )
							$this->setTypeValue( $data );

						//	shorten path
						$this->removeLastFromPath();
						break;
					}
	
					//	get last name and type
					$val	=	array_pop( $this->valStack );

					//	decrement depth, as one tag was removed from
					//	stack
					$this->valDepth--;
	
					//	if no value was found (e.g. other tags inside)
					//	use CDATA that was found between the tags
					if( !isset( $val['value'] ) )
						$val['value']	=	$this->getData();

					if( isset( $this->definedTags[$ns][$name]['useconstants'] ) && $this->definedTags[$ns][$name]['useconstants'] === true )
					{
						if( defined( $val['value'] ) )
							$val['value'] = constant( $val['value'] );
					}
						
					$options = array();
					if( isset( $val['instanceof'] ) )
						$options['instanceof'] = $val['instanceof'];

					$this->setTypeValue( $val['value'], $val['type'], $val['name'], $options );

					if( isset( $val['_isAuto'] ) )
					{
						//	get last name and type
						$val	=	array_pop( $this->valStack );
	
						//	decrement depth, as one tag was removed from
						//	stack
						$this->valDepth--;
		
						//	if no value was found (e.g. other tags inside)
						//	use CDATA that was found between the tags
						if( !isset( $val['value'] ) )
							$val['value']	=	$this->getData();
								
						$options = array();
						if( isset( $val['instanceof'] ) )
							$options['instanceof'] = $val['instanceof'];

						$this->setTypeValue( $val['value'], $val['type'], $val['name'], $options );
					}
					break;
			}
		}
	}
	
   /**
	* handle character data
	* if the character data was found between tags using namespaces, the appropriate namesapce handler will be called
	* 
	* @param	int		$parser		resource id of the current parser
	* @param	string	$data		character data, that was found		
	*/
	function characterData( $parser, $data )
	{
		$tagDepth	=	count( $this->tagStack ); 	

		if( !isset( $this->data[$tagDepth] ) )
			$this->data[$tagDepth]	=	'';
			
		$this->data[$tagDepth]	.=	$data;
	}

   /**
	* add element to path
	*
	* @access	private
	* @param	string	$key	element that should be appended to path
	*/
	function	addToPath( $key )
	{
		array_push( $this->path, $key );
	}
	
   /**
	* remove last element from path
	*
	* @access	private
	*/
	function	removeLastFromPath()
	{
		array_pop( $this->path );
	}

   /**
	* set value for the current path
	*
	* @access	private
	* @param	mixed	$value	value that should be set
	*/
	function	setValue( $value )
	{
		$string	=	implode( '.', $this->path );
		$this->conf[$string]			=	$value;
	}

   /**
	* returns the current data between the open tags
	* data can be anything, from strings, to arrays or objects
	*
	* @access	private
	* @return	mixed	$value	data between text
	*/
	function	getData( $whitespace = 'preserve' )
	{
		return	$this->currentData;
	}

   /**
	* append Data to the current data
	*
	* @param	mixed	$data	data to be appended
	*/
	function	appendData( $data ) 
	{
		$tagDepth	=	count( $this->tagStack ) - 1;
		if( !isset( $this->data[$tagDepth] ) )
			$this->data[$tagDepth]	=	'';
		if( is_string( $this->data[$tagDepth] ) )
		{
			//	append string
			if( is_string( $data ) )
				$this->data[$tagDepth]		.=		$data;
			else
				$this->data[$tagDepth]		=		array( $this->data[$tagDepth], $data );
		}
		elseif( is_array( $this->data[$tagDepth] ) )
		{
			//	append string
			if( is_array( $data ) )
				$this->data[$tagDepth]	=	array_merge( $this->data[$tagDepth], $data );
			else
				array_push( $this->data[$tagDepth], $data );
		}
		else
			$this->data[$tagDepth]			=		$data;	
	}
	
   /**
	* convert a value to a certain type ans set it for the current path
	*
	* @access	private
	* @param	mixed		value that should be set
	* @param	string		type of the value (string, bool, integer, double)
	* @param	array		optional options for the conversion
	*/
	function setTypeValue( $value, $type = 'leave', $name = '', $options = array() )
	{
		//	convert value
		$value	=	$this->configObj->convertValue( $value, $type, $options );

		/**
		* check, if there are parent values
		* insert current value into parent array
		*/
		if( count( $this->valStack ) > 0 )
		{
			if( $name )
				$this->valStack[($this->valDepth-1)]['value'][$name]	=	$value;
			else
				$this->valStack[($this->valDepth-1)]['value'][]			=	$value;
		}

		//	No valuestack
		else
		{
			if( isset( $this->nsStack[( count( $this->nsStack )-1 )] ) && $this->nsStack[( count( $this->nsStack )-1 )] )
				$this->appendData( $value );
			else
			{
				if( $name )
					$this->addToPath( $name );
	
				$this->setValue( $value );
	
				if( $name )
					$this->removeLastFromPath();
			}
		}
	}

   /**
	* define a new namespace
	*
	* @access	private
	* @param	string	$namespace
	*/
	function _defineNamespace( $namespace )
	{
		array_push( $this->defineStack, 'ns' );
		if( isset( $this->definedTags[$namespace] ) )
		{
			$line	=	$this->_getCurrentLine();
			$file	=	$this->_getCurrentFile();

			$this->currentNamespace		=	false;

			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CONFIG_INVALID,
										'Cannot redefine namespace '.$namespace.' on line '.$line.' in '.$file
										 );
		}
		$this->definedTags[$namespace]	=	array();
		$this->currentNamespace			=	$namespace;
		return	true;
	}
	
   /**
	* define a new tag
	*
	* @access	private
	* @param	array	$attributes
	*/
	function _defineTag( $attributes )
	{
		array_push( $this->defineStack, 'tag' );
		if( $this->currentNamespace === false )
			return	false;

		$ns		=	$this->currentNamespace;

		$tag	=	$attributes['tag'];
		if( !isset( $attributes['name'] ) )
		{
			$tagName		=	$attributes['tag'];
			$nameAttribute	=	NULL;
		}
		else
		{
			switch( $attributes['name'] )
			{
				case	'_none':
					$tagName		=	NULL;
					$nameAttribute	=	NULL;
					break;
				case	'_attribute':
					$tagName		=	'_attribute';
					$nameAttribute	=	$attributes['attribute'];
					break;
				default:
					$tagName		=	$attributes['name'];
					$nameAttribute	=	NULL;
					break;
			}
		}

		$this->definedTags[$ns][$tag]		=	array(
																		'type'	=>	$attributes['type'],
																		'name'	=>	$tagName
																	);
		if( isset( $attributes['value'] ) )
			$this->definedTags[$ns][$tag]['value'] = $attributes['value'];

		if( isset( $attributes['content'] ) )
			$this->definedTags[$ns][$tag]['content'] = $attributes['content'];

		if( isset( $attributes['instanceof'] ) )
			$this->definedTags[$ns][$tag]['instanceof'] = $attributes['instanceof'];

		if( isset( $attributes['useconstants'] ) && $attributes['useconstants'] == 'true' )
			$this->definedTags[$ns][$tag]['useconstants'] = true;

		if( $nameAttribute != NULL )
			$this->definedTags[$ns][$tag]['nameAttribute']	=	$nameAttribute;

		$this->currentTag	=	$tag;
		return	true;
	}

   /**
	* define a new attribute
	*
	* @access	private
	* @param	array	$attributes
	*/
	function _defineAttribute( $attributes )
	{
		array_push( $this->defineStack, 'attribute' );
		if( $this->currentNamespace === false )
			return	false;
		if( $this->currentTag === false )
		{
			$line	=	$this->_getCurrentLine();
			$file	=	$this->_getCurrentFile();

			$this->currentNamespace		=	false;
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_CONFIG_INVALID,
										'Cannot define attribute outside a tag on line '.$line.' in '.$file
										 );
		}

		$ns		=	$this->currentNamespace;
		$tag	=	$this->currentTag;
		
		if( !isset( $this->definedTags[$ns][$tag]['attributes'] ) )
			$this->definedTags[$ns][$tag]['attributes']	=	array();
						
		$this->definedTags[$ns][$tag]['attributes'][$attributes['attribute']]	=	array(
																							'type'	=>	$attributes['type']
																						);

		if( isset( $attributes['default'] ) )
			$this->definedTags[$ns][$tag]['attributes'][$attributes['attribute']]['default']		=	$attributes['default'];

		return	true;
	}

   /**
	* parse an external entity
	*
	* @param	int		$parser				resource id of the current parser
	* @param	string	$openEntityNames	space-separated list of the names of the entities that are open for the parse of this entity (including the name of the referenced entity)
	* @param	string	$base				currently empty string
	* @param	string	$systemId			system identifier as specified in the entity declaration
	* @param	string	$publicId			publicId, is the public identifier as specified in the entity declaration, or an empty string if none was specified; the whitespace in the public identifier will have been normalized as required by the XML spec
	*/
	function	externalEntity( $parser, $openEntityNames, $base, $systemId, $publicId )
	{
		if( $systemId )
		{
			$file	=	$this->configObj->getFullPath( $systemId, $this->_getCurrentFile() );
			array_push( $this->externalFiles, $file );
			$this->parseXMLFile( $file );
		}
		return	true;
	}

   /**
	* get all files in a directory
	*
	* @access	private
	* @param	string	$dir
	* @param	string	$ext	file extension
	*/
	function	getFilesInDir( $dir, $ext )
	{
		$files	=	array();
		if( !$dh = dir( $dir ) )
			return	$files;
			
		while( $entry = $dh->read() )
		{
			if( $entry == '.' || $entry == '..' )
				continue;
			if( is_dir( $dir . '/' . $entry ) )
				continue;
			if( strtolower( strrchr( $entry, '.' ) ) != '.'.$ext )
				continue;
			array_push( $files, $dir. '/' . $entry );
		}

		return	$files;
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
			$path		=	str_replace( '.', '\.', $path ).'$';
			$path		=	'^'.str_replace( '*', '.*', $path ).'$';
			$values		=	array();
			reset( $this->conf );
			while( list( $key, $value ) = each( $this->conf ) )
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
	* parse an external xml file
	*
	* @param	string		filename, without dirname
	* @return	boolean		true on success, patError on failure
	*/
	function parseXMLFile( $file )
	{
		//	add it to included files
		array_push( $this->includedFiles, $file );

		$parserCount					=	count( $this->parsers );
		$this->parsers[$parserCount]	=	$this->createParser();
		
		if( !( $fp = @fopen( $file, 'r' ) ) )
		{
			return patErrorManager::raiseError(
										PATCONFIGURATION_ERROR_FILE_NOT_FOUND,
										'Could not open XML file '.$file
										 );
		}

		array_push( $this->xmlFiles, $file );

		flock( $fp, LOCK_SH );

		while( $data = fread( $fp, 4096 ) )
		{
		    if ( !xml_parse( $this->parsers[$parserCount], $data, feof( $fp ) ) )
			{
				$message	=	sprintf(	'XML error: %s at line %d in file %s',
											xml_error_string( xml_get_error_code( $this->parsers[$parserCount] ) ),
											xml_get_current_line_number( $this->parsers[$parserCount] ),
											$file );

				array_pop( $this->xmlFiles );
		
				flock( $fp, LOCK_UN );
				fclose( $fp );
				xml_parser_free( $this->parsers[$parserCount] );

				return patErrorManager::raiseError(
											PATCONFIGURATION_ERROR_CONFIG_INVALID,
											$message
											 );
    		}
		}

		array_pop( $this->xmlFiles );
		
		flock( $fp, LOCK_UN );
		fclose( $fp );
		xml_parser_free( $this->parsers[$parserCount] );

		return	true;
	}

   /**
	* get the current xml parser object
	*
	* @access	private
	* @return	resource	$parser
	*/
	function	&_getCurrentParser()
	{
		$parserCount					=	count( $this->parsers ) - 1;
		return	$this->parsers[$parserCount];
	}
	
   /**
	* get the current line number
	*
	* @access	private
	* @return	int	$line
	*/
	function	_getCurrentLine()
	{
		$parser	=	&$this->_getCurrentParser();
		$line	=	xml_get_current_line_number( $parser );
		return	$line;
	}

   /**
	* get the current file
	*
	* @access	private
	* @return	string $file
	*/
	function	_getCurrentFile()
	{
		$file	=	$this->xmlFiles[( count( $this->xmlFiles )-1 )];
		return	$file;
	}

   /**
	* create a parser
	*
	* @return	object	$parser
	*/
	function	createParser()
	{
		//	init XML Parser
		$parser	=	xml_parser_create( $this->configObj->encoding );
		xml_set_object( $parser, $this );

		if( version_compare( phpversion(), '5.0.0b' ) == -1 )
		{
			xml_set_element_handler( $parser, 'startElement', 'endElement' );
			xml_set_character_data_handler( $parser, 'characterData' );
			xml_set_external_entity_ref_handler( $parser, 'externalEntity' );
		}
		else
		{
			xml_set_element_handler( $parser, array( $this, 'startElement' ), array( $this, 'endElement' ) );
			xml_set_character_data_handler( $parser, array( $this, 'characterData' ) );
			xml_set_external_entity_ref_handler( $parser, array( $this, 'externalEntity' ) );
		}

		xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, false );

		return	$parser;
	}

   /**
	* include an xml file or directory
	*
	* @access	private
	* @param	array	options (=attributes of the tag)
	* @todo		would be nice to have this in an extension
	*/
	function _xInclude( $options )
	{
		if( !isset( $options['once'] ) )
			$options['once']	=	'no';

		if( !isset( $options['relativeTo'] ) )
			$options['relativeTo'] = 'file';
		
		switch( $options['relativeTo'] )
		{
			case 'defines':
				$relativeTo = $this->configObj->getOption( 'definesDir' ).'/.';
				break;
			default:
				$relativeTo = $this->_getCurrentFile();
				break;
		}

		//	include a single file
		if( isset( $options['href'] ) )
		{
			$file		=	$this->configObj->getFullPath( $options['href'], $relativeTo );

			if( $file === false )
				return	false;
			if( $options['once'] == 'yes' && in_array( $file, $this->includedFiles ) )
				return	true;

			array_push( $this->externalFiles, $file );

			$this->parseXMLFile( $file );
		}
		//	include a directory
		elseif( isset( $options['dir'] ) )
		{
			if( !isset( $options['extension'] ) )
				$options['extension']	=	'xml';
				
			$dir		=	$this->configObj->getFullPath( $options['dir'], $relativeTo );
			if( $dir === false )
				return 	false;
			$files		=	$this->getFilesInDir( $dir, $options['extension'] );
			reset( $files );
			foreach( $files as $file )
			{
				array_push( $this->externalFiles, $file );
				$this->parseXMLFile( $file );
			}
		}
		return	true;
	}
}
?>