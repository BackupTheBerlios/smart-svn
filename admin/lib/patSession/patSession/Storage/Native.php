<?php
/**
 * patSession_Storage_Native
 * 
 * Implements the Native-Driver for patSession. Native uses 
 * PHP-Session usually stored in $_SESSION
 * 
 * $Id: Native.php,v 1.5 2004/06/08 20:10:06 gerd Exp $
 *
 * @version	1.0.0
 * @package patSession
 * 
 * @author gERD Schaufelberger <gerd@php-tools.net>
 * @copyright 2004 http://www.php-tools.net
 * @license LGPL
 */
 
/**
 * patSession_Storage_Native
 * 
 * Implements the Native-Driver for patSession. Native uses 
 * PHP-Session usually stored in $_SESSION
 * 
 * @package patSession
 */
class patSession_Storage_Native extends patSession_Storage
{
   /**
	* session variables
	* @var	array $_sess reference to the session-storage
	*/
	var	$_sess	=	null;
	
   /**
	* id string
	* @var	string $_id 
	*/
	var	$_id	=	null;
	
   /**
	* constructor for PHP4
	* 
	* @param string $id name-prefix used for internal storage of session-data
	* @see __construct
	*/
	function	patSession_Storage_Native( $id = 'session', $options = array() )
	{
		$this->__construct( $id, $options );
	}
	
   /**
	* constructor
	* 
	* @param string $id name-prefix used for internal storage of session-data
	*/
	function	__construct( $id = 'session', $options = array() )
	{
		$this->_id	=	$id;
		$this->_setOptions( $options );
		
		//  start session if not startet
		if( !defined( 'SID' ) )
		{
			session_start();
		}

		// init session-array		
		if( !isset( $_SESSION[$this->_id] ) )
		{
			$_SESSION[$this->_id]	=	array();
		}
		
		$this->_sess	=&	$_SESSION[$this->_id];
		$this->_state	=	'active';
		
		// perform security checks
		if( !$this->_checkSecurity() )
		{
			$this->sess	=	array();
		}
	}

   /**
	* recieve name of this session
	*
	* @access private
	* @return string $name session name
	*/
    function getName()
    {
		return session_name();
    }

   /**
	* recieve id of this session
	*
	* @access private
	* @return string $id session id
	*/
    function getId()
    {
		if( $this->_state === 'destroyed' )
		{
			patErrorManager::raiseNotice( 'patSession_' . PATSESSION_ERROR_NOT_ACTIVE,
						'Session is not active - nothing to clear',
						'The session was destroyed before.'
					 );
			return null;
		}
		
		return session_id();
    }
	
   /**
	* save data into session
	* 
	* @access public
	* @param string $name name of variable
	* @param mixed $value any value to be stored into session
	* @return boolean $result true on success
	*/ 
	function	set( $name, $value )
	{
		if( $this->_state !== 'active' )
		{
			return patErrorManager::raiseError( 'patSession_' . PATSESSION_ERROR_NOT_ACTIVE,
						'Cannot set value because session not active.',
						'Either the session was destroyed, has expired or locked by security'
					 );
		}
		
		$this->_sess[$name]	=	$value;
		return true;
	}
	
   /**
	* get data from session
	* 
	* @access public
	* @param string $name name of variable
	* @return mixed $value the value from session or NULL if not set
	*/ 
	function	get( $name )
	{
		if( $this->_state !== 'active' )
		{
				return patErrorManager::raiseWarning( 'patSession_' . PATSESSION_ERROR_NOT_ACTIVE,
						'Cannot recieve value because session not active.',
						'Either the session was destroyed, has expired or locked because of security reasons.'
					 );
		}
		
		if( isset( $this->_sess[$name] ) )
		{
			return $this->_sess[$name];
		}
		
		return null;
	}

   /**
	* unset data from session
	* 
	* @access public
	* @param string $name name of variable
	* @return mixed $value the value from session or NULL if not set
	*/ 
	function	clear( $name )
	{
		if( $this->_state !== 'active' )
		{
			patErrorManager::raiseNotice( 'patSession_' . PATSESSION_ERROR_NOT_ACTIVE,
						'Session is not active - nothing to clear',
						'Either the session was destroyed, has expired or locked because of security reasons.'
					 );
			return null;
		}
		
		$value	=	null;
		if( isset( $this->_sess[$name] ) )
		{
			$value	=	$this->_sess[$name];
			unset( $this->_sess[$name] );
		}
	
		return $value;
	}

   /**
	* unset session variables and destroy session
	* 
	* @access public
	* @return boolean $result true on success
	*/ 
	function	destroy()
	{
		if( $this->_state === 'destroyed' )
		{
			patErrorManager::raiseNotice( 'patSession_' . PATSESSION_ERROR_NOT_ACTIVE,
						'Session is not active.',
						'The session was destroyed before.'
					 );
			return false;
		}
	
		session_unset();
		session_destroy();
		
		$this->_sess	=	null;
		$this->_state	=	'destroyed';
		return true;
	}
	
   /**
	* create a new session and copy variables from the old one
	*
	* @access public
	* @return boolean $result true on success
	* @todo think about storage of session values - what happens to $_SESSION
	*/
    function fork()
    {
		if( $this->_state !== 'active' )
		{
			if( !$this->_checkSecurity( true ) )
			{
				return patErrorManager::raiseNotice( 'patSession_' . PATSESSION_ERROR_NOT_ACTIVE,
						'Session is not active.',
						'Either the session was destroyed, has expired or locked because of security reasons: '. $this->_state.'.'
					 );
			}
		}
		
		// save values
		$values			=	$_SESSION;

		// keep session config		
		$trans	=	ini_get( 'session.use_trans_sid' );
		if( $trans )
		{
			ini_set( 'session.use_trans_sid', 0 );
		}
		$cookie	=	session_get_cookie_params();
		
		$id	=	$this->_createId( strlen( session_id() ) );
		
		// kill session and restart it with new id
		session_destroy();
		session_id( $id );
		session_start();
		
		// restore values	
		$_SESSION		=	$values;
		$this->_sess	=&	$_SESSION[$this->_id];

		// restore config		
		ini_set( 'session.use_trans_sid', $trans );
		session_set_cookie_params( $cookie['lifetime'], $cookie['path'], $cookie['domain'], $cookie['secure'] );
		
		return true;
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
		// set name
		if( isset( $options['name'] ) )
		{
			session_name( $options['name'] );
		}
		
		// set id
		if( isset( $options['id'] ) )
		{
			session_id( $options['id'] );
		}
		
		return parent::_setOptions( $options );
    }
}
?>