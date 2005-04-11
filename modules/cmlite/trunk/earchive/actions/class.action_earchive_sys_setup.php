<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * action_earchive_sys_setup class 
 *
 */
 
class action_earchive_sys_setup extends action
{
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
        //create data folder
        if(!is_dir(SF_BASE_DIR . 'data/earchive'))
        {
            if(!mkdir(SF_BASE_DIR . 'data/earchive', SF_DIR_MODE))
            {
                trigger_error("Cant make dir: ".SF_BASE_DIR."data/earchive\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = 'Cant make dir: ' . SF_BASE_DIR . 'data/earchive';
                $success = FALSE;
            }
            elseif(!is_writeable( SF_BASE_DIR . 'data/earchive' ))
            {
                trigger_error("Cant make dir: ".SF_BASE_DIR."data/earchive\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/earchive';
                $success = FALSE;
            }  
        }

        if(!isset($_POST['dbtype']))
            $db_type = $this->B->sys['db']['dbtype'];
        else
            $db_type = $_POST['dbtype'];
    
        if($success == TRUE)
        {
            // cretae mysql tables
            $success = $this->_create_mysql_tables();    
        }

        // create configs info for this module
        $this->B->conf_val['module']['earchive']['name']     = 'Earchive';
        $this->B->conf_val['module']['earchive']['version']  = MOD_EARCHIVE_VERSION;
        $this->B->conf_val['module']['earchive']['mod_type'] = 'lite';
        $this->B->conf_val['module']['earchive']['info']     = 'Email messages archive. Author: Armand Turpel <smart AT open-publisher.net>';     

        return $success;
    } 
    
    /**
     * Create mysql tables
     *
     * @access privat
     */    
    function _create_mysql_tables()
    {
        $success = TRUE;
        
        // create table if it dosent exist
        $sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}earchive_lists (
                lid         INT(11) NOT NULL auto_increment,
                status      TINYINT NOT NULL default 1,
                name        VARCHAR(255) NOT NULL default '',
                description TEXT NOT NULL  default '',
                email       TEXT NOT NULL default '',
                emailserver TEXT NOT NULL default '',
                folder      CHAR(32) NOT NULL,
                PRIMARY KEY     (lid),
                KEY status      (status))";

        $result = $this->B->db->query($sql);

        if (MDB2::isError($result))
        {
            trigger_error($result->getMessage()."\n".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            $success = FALSE;
        }
   
        // create table if it dosent exist
        $sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}earchive_messages (
                mid        INT(11) NOT NULL auto_increment,
                lid        INT(11) NOT NULL,
                message_id VARCHAR(32) NOT NULL default '',
                root_id    VARCHAR(32) NOT NULL default '',
                parent_id  VARCHAR(32) NOT NULL default '',
                subject    TEXT NOT NULL  default '',
                sender     TEXT NOT NULL  default '',
                mdate      DATETIME default '0000-00-00 00:00:00' NOT NULL,
                body       MEDIUMTEXT default '' NOT NULL,
                enc_type   VARCHAR(50) NOT NULL default '',                
                folder     VARCHAR(32) default '' NOT NULL,
                header     MEDIUMTEXT NOT NULL default '',                 
                PRIMARY KEY     (mid),
                KEY lid         (lid),
                KEY mdate       (mdate),
                KEY message_id  (message_id),
                KEY root_id     (root_id),
                KEY parent_id   (parent_id))";

        $result = $this->B->db->query($sql);

        if (MDB2::isError($result))
        {
            trigger_error($result->getMessage()."\n".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            $success = FALSE;
        }

        // create table if it dosent exist
        $sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}earchive_attach (
                aid      INT(11) NOT NULL auto_increment,
                mid      INT(11) NOT NULL,
                lid      INT(11) NOT NULL,
                file     TEXT NOT NULL  default '',
                size     INT(11) NOT NULL,
                type     VARCHAR(200) NOT NULL  default '',
                PRIMARY KEY     (aid),
                KEY mid         (mid),
                KEY lid         (lid))";

        $result = $this->B->db->query($sql);

        if (MDB2::isError($result))
        {
            trigger_error($result->getMessage()."\n".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            $success = FALSE;
        }

        $sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}earchive_words_crc32 (
                word int(11) NOT NULL default 0,
                mid  int(11) NOT NULL default 0,
                lid  int(11) NOT NULL default 0)";
    
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result))
        {
            trigger_error($result->getMessage()."\n".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            $success = FALSE;
        }
    
        return $success;  
    }
}

?>
