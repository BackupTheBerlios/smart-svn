<?php
/**
 * patSession_Storage
 * 
 * $Id: Storage.php,v 1.6 2004/06/08 20:10:06 gerd Exp $
 *
 * @version	1.0.0
 * @package patSession
 * 
 * @author gERD Schaufelberger <gerd@php-tools.net>
 * @copyright 2004 http://www.php-tools.net
 * @license LGPL
 */
 
/**
 * patSession_Storage
 * 
 * @abstract
 * @package patSession
 */
class patSession_Storage
{
   /**
	* internal state
	* @var	string $_state one of 'new'|'active'|'destroyed'
	*/
	var	$_state	=	'new';
	
   /**
	* maximum age of unused session 
	* @var	string $_expire minutes 
	*/
	var	$_expire	=	null;
	
   /**
	* security policy
	* Default values:
	*  - fix_ip
	*  - fix_browser
	*  - fix_referer
	* 
	* @var array $_security list of checks that will be done.
	*/
	var $_security = array( 'fix_referer', 'fix_ip', 'fix_browser' );

   /**
	* list of allowed referers
	* @var	array $_allowedReferer 
	*/
	var	$_allowedReferer	=	array();
	
   /**
	* allow empty referer 
	* @var	string $_emptyReferer 'deny' is default
	*/
	var	$_emptyReferer	=	null;
	
   /**
	* get current state of sessoin
	*
	* @final
	* @access public
	* @return string $state named state
	*/
    function getState()
    {
		return $this->_state;
    }

   /**
	* recieve url-encoded string
	* The query string contains the session name and id
	* and can be used the same way as the constant SID
	*
	* @final
	* @access public
	* @return string $querystring 
	*/
    function getQueryString()
    {
		return urlencode( $this->getName() ) . '=' . urlencode( $this->getId() );
    }

   /**
	* recieve name of this session
	*
	* @abstract
	* @access private
	* @return string $name session name
	*/
    function getName()
    {
		return null;
    }

   /**
	* recieve id of this session
	*
	* @abstract
	* @access private
	* @return string $id session id
	*/
    function getId()
    {
		return null;
    }
	
   /**
	* save data into session
	* 
	* @abstract
	* @access public
	* @param string $name name of variable
	* @param mixed $value any value to be stored into session
	* @return boolean $result true on success
	*/ 
	function	set( $name, $value )
	{
		return true;
	}
	
   /**
	* get data from session
	* 
	* @abstract
	* @access public
	* @param string $name name of variable
	* @return mixed $value the value from session or NULL if not set
	*/ 
	function	get( $name )
	{
		return null;
	}

   /**
	* unset data from session
	* 
	* @abstract
	* @access public
	* @param string $name name of variable
	* @return mixed $value the value from session or NULL if not set
	*/ 
	function	clear( $name )
	{
		return null;
	}

   /**
	* unset session variables and destroy session
	* 
	* @abstract
	* @access public
	* @return boolean $result true on success
	*/ 
	function	destroy()
	{
		return true;
	}
	
   /**
	* create a new session and copy variables from the old one
	*
	* @abstract
	* @access public
	* @return boolean $result true on success
	*/
    function fork()
    {
		return true;
    }

   /**
	* do some checks for security reason
	* - timeout check (expire)
	* - ip-fixiation
	* - referer-fixiation
	* 
	* If one check failed, session data has to be cleaned.
	*
	* @access private
	* @return boolean $result true on success
	* @todo add network-mask feature for ip-check
	* @todo add allowed referer for session-transfers
	*/
	function _checkSecurity( $rewoke = false )
	{
		// allow to rewoke a session
		if( $rewoke )
		{
			$this->_state	=	'active';
			$this->set( '_patSession_atime', null );
			$this->set( '_patSession_clientAddr', null );
			$this->set( '_patSession_clientBrowser', null );
		}
		
		$atime	=	$this->get( '_patSession_atime' );
		
		// check if session has expired
		if( $this->_expire && $atime )
		{
			$minAtime	=	time() - 60 * $this->_expire;
			$atime		=	strtotime( $atime );
			
			// empty session variables
			if( $atime < $minAtime )
			{
				$this->_state	=	'expired';
				return false;
			}
		}
		$this->set( '_patSession_atime', date( 'Y-m-d H:i:s' ) );

		// empty referer?
		if( !isset( $_SERVER['HTTP_REFERER'] ) )
		{
			// deny empty referer			
			if( $this->_emptyReferer !== 'allow' )
			{
				$this->_state	=	'empty_referer';
				return false;
			} 
		}
		// else check referer
		else if( !empty( $this->_allowedReferer ) && in_array( 'fix_referer', $this->_security ) )
		{
			// referer must match server
			preg_match( '°https?://(.*)/°U', $_SERVER['HTTP_REFERER'], $match );
			$ref	=	$match[1];
			
			// empty session varaibles for security purpose			
			if( $ref !== $_SERVER['SERVER_NAME'] )
			{
				// check allowed referers
				$found	=	false;
				foreach( $this->_allowedReferer as $allowed )
				{
					if( preg_match( $allowed, $ref ) )
					{
						$found	=	true;
						break;
					}
				}
				
				if( !$found )
				{
					$this->_state	=	'referer_not_allowed';
					return false;
				}
			}
		}
		
		// check for client-ip
		if( in_array( 'fix_ip', $this->_security ) )
		{
			$ip	=	$this->get( '_patSession_clientAddr' );
			
			if( $ip === null )
			{
				$this->set( '_patSession_clientAddr', $_SERVER['REMOTE_ADDR'] );
			}
			else if( $_SERVER['REMOTE_ADDR'] !== $ip )
			{
				$this->_state	=	'fix_ip_failed';
				return false;
			}
		}
		
		// check for clients browser
		if( in_array( 'fix_browser', $this->_security ) )
		{
			$browser	=	$this->get( '_patSession_clientBrowser' );
			
			if( $browser === null )
			{
				$this->set( '_patSession_clientBrowser', $_SERVER['HTTP_USER_AGENT'] );
			}
			else if( $_SERVER['HTTP_USER_AGENT'] !== $browser )
			{
				$this->_state	=	'fix_browser_failed';
				return false;
			}
		}
		
		return true;
	}
	
   /**
	* create an id-string
	*
	* @access private
	* @param int $length lenght of string
	* @return string $id generated id
	*/
	function _createId( $length = 32 )
	{
		// create a new id
		static $chars	=	'0123456789abcdef';
		$max			=	strlen( $chars ) - 1;
		$id				=	'';
		for( $i = 0; $i < $length; ++$i )
		{
			$id .=	$chars[ (rand( 0, $max )) ];
		}

		return $id;		
	}
	
   /**
	* set additional session options
	*
	* @access private
	* @param array $options list of parameter
	* @return boolean $result true on success
	*/
    function _setOptions( &$options )
    {
		// set expire time
		if( isset( $options['expire'] ) )
		{
			$this->_expire	=	$options['expire'];
		}
		
		// get security options
		if( isset( $options['security'] ) )
		{
			$this->_security	=	explode( ',', $options['security'] );
		}
		
		// some referers are allowed
		if( isset( $options['allow-referer'] ) )
		{
			$referer	=	explode( ',', $options['allow-referer'] );
			foreach( $referer as $ref )
			{
				$ref	=	strtr( $ref, array( '.' => '\.' ));
				$preg	=	$ref;
				
				if( $ref[0] === '*' )
				{
					$ref	=	substr( $ref, 1 );
					$preg	=	'/'. $ref . '$/';
				}
				else if( $ref[ strlen( $ref ) - 1 ] === '*' )
				{
					$ref	=	substr( $ref, 0, -1 );
					$preg	=	'/^'. $ref . '/';
				}
				
				array_push( $this->_allowedReferer, $preg ); 
			}
		}
		
		// allow empty referer
		if( isset( $options['empty-referer'] ) && $options['empty-referer'] === 'allow' )
		{
			$this->_emptyReferer	=	'allow';
		}
		
		return true;
    }
}
?>