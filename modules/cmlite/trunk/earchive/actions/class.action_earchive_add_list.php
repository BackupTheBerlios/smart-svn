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
 * action_earchive_add_list class 
 *
 */
 
class action_earchive_add_list extends action
{
    /**
     * add list data in db table and create data folder
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        // get list messages attachment folder string
        $list_folder = commonUtil::unique_md5_str();

        if(!@mkdir(SF_BASE_DIR . '/data/earchive/' . $list_folder, SF_DIR_MODE))
        {
            trigger_error("Cannot create list messages attachment folder! Contact the administrator.\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        $sql = '
            INSERT INTO 
                '.$this->B->sys['db']['table_prefix'].'earchive_lists
                (name,email,emailserver,description,folder,status)
            VALUES
                ('.$data['name'].',
                 '.$data['email'].',
                 '.$data['emailserver'].',
                 '.$data['description'].',
                 "'.$list_folder.'",
                 '.$data['status'].')';

        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
                    
        return TRUE;  
    }    
}

?>
