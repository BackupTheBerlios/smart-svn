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
 
class common_upgrade
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
    function common_upgrade()
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
        // version prior to 0.5
        if(version_compare( (string)$this->B->sys['module']['common']['version'], '0.5' , '<') == 1)
        {
            // The PEAR cache db table. 
            $sql = "CREATE TABLE {$this->B->sys['db']['table_prefix']}cache (
                      id          char(32) NOT NULL DEFAULT '',
                      cachegroup  varchar(127) NOT NULL DEFAULT '',
                      cachedata   blob NOT NULL DEFAULT '',
                      userdata    varchar(255) NOT NULL DEFAULT '',
                      expires     int(9) NOT NULL DEFAULT 0,
                      changed     timestamp(14) NOT NULL,
                      index (expires),
                      primary key (id, cachegroup))";      
        
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                return FALSE;
            }     

        }
               
        return TRUE;
    } 
}

?>