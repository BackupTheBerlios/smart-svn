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
    /* Define the mysql table you wish to use with
       this class, this table MUST exist. */
    var $ses_table = "sessions";

    /* Change to 'Y' if you want to connect to a db in
       the _open function */
    var $db_con = "Y";

    function session()
    {
        $this->_B = &$GLOBALS['B'];
        
        // Get some informations
        //
        $this->global_scope = ini_get('register_globals');

        // Set session name
        if(!empty($session_name))
        {
            session_name($session_name); 
        }

        // Start session if not done now
        //
        if (!isset($_SESSION))
            session_start();

        // Test on HTTP_SESSION_VARS
        //
        if (array_key_exists('HTTP_SESSION_VARS', $GLOBALS)) {

            $this->http_scope = true;
            $this->http =& $GLOBALS['HTTP_SESSION_VARS'];
        }        
        
        /* Change the save_handler to use the class functions */
        session_set_save_handler (  array(&$this, '_open'),
                                    array(&$this, '_close'),
                                    array(&$this, '_read'),
                                    array(&$this, '_write'),
                                    array(&$this, '_destroy'),
                                    array(&$this, '_gc'));  
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
        if ($this->global_scope) {
            $GLOBALS[$name] = $value;
            session_register($name);
        }
        if ($this->http_scope)
            $this->http[$name] = $value;
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

        if ($this->global_scope) {
            unset($GLOBALS[$name]);
            session_unregister($name);
        }

        if ($this->http_scope)
            unset($this->http[$name]);

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
        session_unset();
        session_destroy();
    } 

    /* Open session, if you have your own db connection
       code, put it in here! */
    function _open($path, $name) {
        return TRUE;
    }

    /* Close session */
    function _close() {
        /* This is used for a manual call of the
           session gc function */
        $this->_gc(0);
        return TRUE;
    }

    /* Read session data from database */
    function _read($ses_id) {
        $session_sql = "SELECT * FROM " . $this->ses_table
                     . " WHERE ses_id = '$ses_id'";
        $session_res = $this->_B->dbsession->query($session_sql);
        if (!$session_res) {
            return '';
        }

        $session_num = $this->_B->dbsession->numRows($session_res);
        if ($session_num > 0) {
            $session_row = $this->_B->dbsession->getRow($session_res);
            $ses_data = $session_row["ses_value"];
            return $ses_data;
        } else {
            return '';
        }
    }

    /* Write new data to database */
    function _write($ses_id, $data) {
        $session_sql = "UPDATE " . $this->ses_table
                     . " SET ses_time='" . time()
                     . "', ses_value='$data' WHERE ses_id='$ses_id'";
        $session_res = $this->_B->dbsession->query($session_sql);
        if (!$session_res) {
            return FALSE;
        }
        if ($this->_B->dbsession->affectedRows()) {
            return TRUE;
        }

        $session_sql = "INSERT INTO " . $this->ses_table
                     . " (ses_id, ses_time, ses_start, ses_value)"
                     . " VALUES ('$ses_id', '" . time()
                     . "', '" . time() . "', '$data')";
        $session_res = $this->_B->dbsession->query($session_sql);
        if (!$session_res) {    
            return FALSE;
        }         else {
            return TRUE;
        }
    }

    /* Destroy session record in database */
    function _destroy($ses_id) {
        $session_sql = "DELETE FROM " . $this->ses_table
                     . " WHERE ses_id = '$ses_id'";
        $session_res = $this->_B->dbsession->query($session_sql);
        if (!$session_res) {
            return FALSE;
        }         else {
            return TRUE;
        }
    }

    /* Garbage collection, deletes old sessions */
    function _gc($life) {
        $ses_life = strtotime("-5 minutes");

        $session_sql = "DELETE FROM " . $this->ses_table
                     . " WHERE ses_time < $ses_life";
        $session_res = $this->_B->dbsession->query($session_sql);


        if (!$session_res) {
            return FALSE;
        }         else {
            return TRUE;
        }
    }
} 
?>
