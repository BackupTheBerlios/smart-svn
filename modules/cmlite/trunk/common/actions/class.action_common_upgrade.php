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
 * action_common_upgrade class 
 *
 */
 
class action_common_upgrade extends action
{
    /**
     * upgarde the common module
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        // version prior to 0.5.1
        if(version_compare( (string)$this->B->sys['module']['common']['version'], '0.5.1' , '<') == 1)
        {
            // The PEAR cache db table. 
            $sql = "DROP TABLE IF EXISTS {$this->B->sys['db']['table_prefix']}cache";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                return FALSE;
            }  
            
            if(!is_writeable( SF_BASE_DIR . 'modules/common/tmp/cache' ))
            {
                trigger_error('Must be writeable: ' . SF_BASE_DIR . 'modules/common/tmp/cache', E_USER_ERROR);
            }            
        }

        // version prior to 0.5.2
        if(version_compare( (string)$this->B->sys['module']['common']['version'], '0.5.2' , '<') == 1)
        {
            // check if session folder is writeable
            if(!is_writeable( SF_BASE_DIR . 'modules/common/tmp/session_data' ))
            {
                 trigger_error('Must be writeable: ' . SF_BASE_DIR . 'modules/common/tmp/session_data', E_USER_ERROR);
            }             
        }            
        return TRUE;
    } 
}

?>