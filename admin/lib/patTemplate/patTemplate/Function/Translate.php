<?PHP
/**
 * patTemplate function that emulates gettext's behaviour
 * 
 * This can be used to create multi-lingual websites.
 * When the template is read, all texts inside the
 * Translation tags are extracted and written to a file
 * called '$tmplname-default.ini'. 
 *
 * You should copy this file and translate all sentences.
 * When the template is used the next time, the sentences
 * will be replaced with their respective translations,
 * according to the langanuge you set with:
 * <code>
 * $tmpl->setOption( 'lang', 'de' );
 * </code>
 *
 * $Id: Translate.php,v 1.3 2004/05/25 20:18:55 schst Exp $
 *
 * @package		patTemplate
 * @subpackage	Functions
 * @author		Stephan Schmidt <schst@php.net>
 */

/**
 * patTemplate function that emulates gettext's behaviour
 * 
 * This can be used to create multi-lingual websites.
 * When the template is read, all texts inside the
 * Translation tags are extracted and written to a file
 * called '$tmplname-default.ini'. 
 *
 * You should copy this file and translate all sentences.
 * When the template is used the next time, the sentences
 * will be replaced with their respective translations,
 * according to the langanuge you set with:
 * <code>
 * $tmpl->setOption( 'lang', 'de' );
 * </code>
 *
 * $Id: Translate.php,v 1.3 2004/05/25 20:18:55 schst Exp $
 *
 * @package		patTemplate
 * @subpackage	Functions
 * @author		Stephan Schmidt <schst@php.net>
 * @todo		add error management
 */
class patTemplate_Function_Translate extends patTemplate_Function
{
   /**
	* name of the function
	* @access	private
	* @var		string
	*/
	var $_name	=	'Translate';

   /**
	* configuration of the function
	*
	* @access	private
	* @var		array
	*/
	var $_config	=	array();

   /**
	* list of all sentences
	*
	* @access	private
	* @var		array
	*/
	var $_sentences	=	array();

   /**
    * reference to the patTemplate object that instantiated the module
	*
	* @access	protected
	* @var	object
	*/
	var	$_tmpl;

   /**
    * set a reference to the patTemplate object that instantiated the reader
	*
	* @access	public
	* @param	object		patTemplate object
	*/
	function setTemplateReference( &$tmpl )
	{
		$this->_tmpl		=	&$tmpl;
	}

   /**
	* call the function
	*
	* @access	public
	* @param	array	parameters of the function (= attributes of the tag)
	* @param	string	content of the tag
	* @return	string	content to insert into the template
	*/ 
	function call( $params, $content )
	{
		/**
		 * nothing to translate
		 */
		if( empty( $content ) )
		{
			return;
		}

		/**
		 * get config
		 */
		if( empty( $this->_config ) )
		{
			$this->_retrieveConfig();
			$this->_loadTranslationFile();
		}

		/**
		 * unique key for the sentence to translate
		 */
		if( isset( $params['key'] ) )
			$key	=	$params['key'];
		else
			$key	=	md5( $content );
		
		/**
		 * does this already exists?
		 */
		if( !isset( $this->_sentences[$key] ) )
		{
			$this->_sentences[$key]	=	$content;
			$this->_addToTranslationFile( $key, $content );
		}
		
		/**
		 * has it been translated?
		 */
		if( isset( $this->_translation[$key] ) )
		{
			return $this->_translation[$key];
		}

		/**
		 * use original sentence
		 */
		return $this->_sentences[$key];
	}

   /**
	* retrieve configuration
	*
	* @access	private
	* @return	boolean		true on success
	*/
	function _retrieveConfig()
	{
		$this->_config	=	array();

		$input = $this->_reader->getCurrentInput();
		
		/**
		 * get config values from patTemplate
		 */
		$this->_config['lang']				=	$this->_tmpl->getOption( 'lang' );
		if( !is_array( $this->_config['lang'] ) )
		{
			if( $this->_config['lang'] == 'auto' )
				$this->_config['lang'] = $this->_guessLanguage();
			else
				$this->_config['lang'] = array( $this->_config['lang'] );
		}
		
		$this->_config['translationFolder']	=	$this->_tmpl->getOption( 'translationFolder' );
		$this->_config['inputId']			=	$input;
		$this->_config['sentenceFile']		=	$this->_tmpl->getOption( 'translationFolder' ) . '/'.$input.'-default.ini';
		$this->_config['langFile']			=	$this->_tmpl->getOption( 'translationFolder' ) . '/'.$input.'-%s.ini';

		/**
		 * get the 'gettext' source file
		 */
		$this->_sentences	=	@parse_ini_file( $this->_config['sentenceFile'] );
		
		return true;
	}

   /**
	* load the translation file
	*
	* @access	private
	* @return	boolean		true on success
	*/
	function _loadTranslationFile()
	{
		foreach( $this->_config['lang'] as $lang )
		{
			$translationFile	=	sprintf( $this->_config['langFile'], $lang );
			if( !file_exists( $translationFile ) )
				continue;
			$tmp	=	@parse_ini_file( $translationFile );
			if( is_array( $tmp ) )
			{
				$this->_translation	=	$tmp;
				return true;
			}
		}
		return false;
	}

   /**
  	* add a new sentence to the translation file
	*
	* @access	private
	* @param	string	unique key
	* @param	string	sentence to translate
	* @return	boolean
	*/
	function _addToTranslationFile( $key, $content )
	{
		$fp	=	@fopen( $this->_config['sentenceFile'], 'a' );
		if( !$fp )
			return false;
		fputs( $fp, sprintf( '%s = "%s"'."\n", $key, addslashes( $content ) ) );
		fclose( $fp );
		return true;
	}

   /**
	* guess the language
	*
	* @access	private
	* @return	array		array containing all accepted languages
	*/
	function _guessLanguage()
	{
		if( !preg_match_all( '/([a-z\-]*)?[,;]/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches) )
		{
			return array();
		}
		$langs = array();
		foreach( $matches[1] as $lang )
		{
			if( empty( $lang ) )
				continue;
			array_push( $langs, $lang );
		}
		return $langs;
	}
}
?>