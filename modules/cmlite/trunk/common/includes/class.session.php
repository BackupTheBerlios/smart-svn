<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/*
 * The Session Class
 *
 */

class session
{
    /**
     * Constructor
     *
     * @param string $session_name Name of the Session Cookie
     */    
    function session( $session_name = FALSE )
    {
        // Set session name
        if(!empty($session_name))
        {
            session_name($session_name); 
        }
        
        session_start();
    }

    /**
     * Tests if a session var is present
     *
     * @param string $name The nam of the session var
     * @return bool
     */
    function exists($name)
    {
        if (empty($name))
            return false;

        return (array_key_exists($name, $_SESSION));
    }

    /**
     * Return a copy of a session var
     *
     * @param string $name The nam of the session var
     * @return mixed The session var if any or NULL
     * @see opSession::getRef()
     */
    function get($name)
    {
        if ($this->exists($name))
            return $_SESSION[$name];
        else
            return NULL;
    }

    /**
     * Set a session var
     *
     * @param string $name The nam of the session var
     * @param mixed $value The var
     * @return bool
     */
    function set($name, $value)
    {
        if (empty($name))
            return false;

        $_SESSION[$name] = $value;
        
        session_register($name);

        return true;
    }

    /**
     * Removes a var from the session. Take also care on the global
     * scope.
     *
     * @param string $name The name of the var
     */
    function del($name)
    {
        if (empty($name))
            return false;

        session_unregister($name);

        $_SESSION[$name] = NULL;

        return true;
    }

    /**
     * Returns the reference to a session var
     *
     * @param string $name The name of the session var
     * @return reference to the session var
     * @see opSession::get()
     */
    function &getReference($name)
    {
        if ($this->exists($name))
            return $_SESSION[$name];
    }
    
    /**
     * Delete all session vars
     *
     */
    function del_all()
    {
        session_unset();
    } 
    
    /**
     * Destroy the session
     *
     */
    function destroy()
    {
        $CookieInfo = session_get_cookie_params();
        
        if ( (empty($CookieInfo['domain'])) && (empty($CookieInfo['secure'])) ) 
        {
            setcookie(session_name(), '', time()-36000, $CookieInfo['path']);
        } 
        elseif (empty($CookieInfo['secure'])) 
        {
            setcookie(session_name(), '', time()-36000, $CookieInfo['path'], $CookieInfo['domain']);
        } 
        else 
        {
            setcookie(session_name(), '', time()-36000, $CookieInfo['path'], $CookieInfo['domain'], $CookieInfo['secure']);
        }    
        session_unset();
        session_destroy();
    } 
} 
?>