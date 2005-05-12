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
        $this->db = $db;
        $this->dbTablePrefix = $tablePrefix;
        
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
        $result = $this->db->executeQuery("SELECT session_data 
                                           FROM {$this->dbTablePrefix}common_session 
                                           WHERE session_id = '$id'", 
                                           ResultSet::FETCHMODE_ASSOC);
        $result->first();
        //$result->get('session_data')
        $f = $result->getRow();
        //var_dump($f['session_data']);
        return $f['session_data'];
    }
  
    public function write($id, $sess_data) 
    {
        $stmt = $this->db->prepareStatement("REPLACE INTO {$this->dbTablePrefix}common_session
                                             (session_id, session_data, modtime) 
                                             VALUES(?,?,?)");
        $stmt->setString(1, $id);
        $stmt->setString(2, $sess_data);
        $stmt->setInt(3,    time());
        $stmt->executeUpdate();
    }
  
    public function destroy($id) 
    {
        $this->db->executeUpdate("DELETE FROM {$this->dbTablePrefix}common_session
                                  WHERE session_id = '$id'");
  
    }
  
    public function gc($maxlifetime) 
    {
        $ts = time() - $maxlifetime;
        $this->db->executeUpdate("DELETE FROM {$this->dbTablePrefix}common_session
                                  WHERE modtime < $ts");
    }
}
?>