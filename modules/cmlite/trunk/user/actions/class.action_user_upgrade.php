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
 * action_user_upgrade class 
 *
 */
 
class action_user_upgrade extends action
{
    /**
     * upgarde the common module
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        // version prior to 0.4.1
        if(version_compare( (string)$this->B->sys['module']['user']['version'], '0.4.2' , '<') == 1)
        {
            // Add autoincrement and primary key to uid. 
            $sql = "ALTER TABLE 
                        {$this->B->sys['db']['table_prefix']}user_users 
                    CHANGE uid uid INT(11) NOT NULL auto_increment,
                    ADD PRIMARY KEY (uid),
                    ADD INDEX (login),
                    ADD INDEX (passwd)";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }   
            
            // drop sequence table. 
            $sql = "DROP TABLE IF EXISTS
                        {$this->B->sys['db']['table_prefix']}user_seq_add_user_seq";      
        
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
