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
 * common_upgrade class 
 *
 */
 
class earchive_upgrade
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function earchive_upgrade()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * upgarde the common module
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        // version prior to 0.4.1
        if(version_compare( $this->B->sys['module']['earchive']['version'], '0.4.2' , '<' ) == 1)
        {
            // Add autoincrement and primary key to uid. 
            $sql = "ALTER TABLE 
                        {$this->B->sys['db']['table_prefix']}earchive_lists 
                    CHANGE lid lid INT(11) NOT NULL auto_increment,
                    ADD PRIMARY KEY (lid)";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }   
            
            // drop sequence table. 
            $sql = "DROP TABLE IF EXISTS
                        {$this->B->sys['db']['table_prefix']}earchive_seq_add_list_seq";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }   
            
            // Add autoincrement and primary key to uid. 
            $sql = "ALTER TABLE 
                        {$this->B->sys['db']['table_prefix']}earchive_messages 
                    CHANGE mid mid INT(11) NOT NULL auto_increment,
                    ADD PRIMARY KEY (mid),
                    ADD INDEX       (mdate)";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }   
            
            // drop sequence table. 
            $sql = "DROP TABLE IF EXISTS
                        {$this->B->sys['db']['table_prefix']}earchive_seq_add_message_seq";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }    
            
            // Add autoincrement and primary key to uid. 
            $sql = "ALTER TABLE 
                        {$this->B->sys['db']['table_prefix']}earchive_attach 
                    CHANGE aid aid INT(11) NOT NULL auto_increment,
                    ADD PRIMARY KEY (aid)";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }   
            
            // drop sequence table. 
            $sql = "DROP TABLE IF EXISTS
                        {$this->B->sys['db']['table_prefix']}earchive_seq_add_attach_seq";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }              
        }
        // version prior to 0.4.3
        if(version_compare( $this->B->sys['module']['earchive']['version'], '0.4.3' , '<' ) == 1)
        {    
            // Add autoincrement and primary key to uid. 
            $sql = "ALTER TABLE 
                        {$this->B->sys['db']['table_prefix']}earchive_messages 
                    ADD enc_type   varchar(50) NOT NULL default '' AFTER body,
                    ADD message_id varchar(255) NOT NULL default '' AFTER lid,
                    ADD root_id    varchar(255) NOT NULL default '' AFTER lid,
                    ADD parent_id  varchar(255) NOT NULL default '' AFTER lid,
                    ADD header     mediumtext NOT NULL default '' AFTER folder,
                    ADD INDEX (message_id),
                    ADD INDEX (root_id),
                    ADD INDEX (parent_id)";      
        
            $result = $this->B->db->query($sql); 

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }           
        }
        return TRUE;
    } 
}

?>