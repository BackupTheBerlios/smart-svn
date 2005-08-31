<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * Smart DB Session Handler
 *
 */
 
final class SmartSessionHandler 
{
    private $db;
    private $dbTablePrefix;

    function __construct( & $db, & $tablePrefix )
    {
        $this->db = & $db;
        $this->dbTablePrefix = & $tablePrefix;
        
        session_set_save_handler(
            array(& $this, 'open'),
            array(& $this, 'close'),
            array(& $this, 'read'),
            array(& $this, 'write'),
            array(& $this, 'destroy'),
            array(& $this, 'gc'));
    }
  
    public function open($save_path, $session_name) 
    {
        return TRUE;
    }
  
    public function close() 
    {
        return TRUE;
    }
  
    public function read($id) 
    {
        $result = $this->db->query("SELECT session_data 
                                           FROM {$this->dbTablePrefix}common_session 
                                           WHERE session_id = '$id'");

        $f = $result->fetchAssoc();
        return $f['session_data'];
    }
  
    public function write($id, $sess_data) 
    {
        $stmt = $this->db->prepare("REPLACE INTO {$this->dbTablePrefix}common_session
                                       (session_id, session_data, modtime) 
                                    VALUES(?,?,?)");
        $stmt->setString($id);
        $stmt->setString($sess_data);
        $stmt->setInt(time());
        $stmt->execute();
    }
  
    public function destroy($id) 
    {
        $this->db->query("DELETE FROM {$this->dbTablePrefix}common_session
                            WHERE session_id = '$id'");
  
    }
  
    public function gc($maxlifetime) 
    {
        $ts = time() - $maxlifetime;
        $this->db->query("DELETE FROM {$this->dbTablePrefix}common_session
                                  WHERE modtime < $ts");
    }
}
?>