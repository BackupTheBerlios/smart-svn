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
 * OPTION_SYS_SETUP class 
 *
 */
 
class OPTION_SYS_SETUP
{
    /**
     * Global system instance
     * @var object $this->B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function OPTION_SYS_SETUP()
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
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
        // The module name and version
        $this->B->conf_val['module']['option']['name']     = 'option';
        $this->B->conf_val['module']['option']['version']  = MOD_OPTION_VERSION;
        $this->B->conf_val['module']['option']['mod_type'] = 'lite';
        $this->B->conf_val['module']['option']['info']     = '';
        // Set some options
        $this->B->conf_val['option']['tpl'] = SF_DEFAULT_TEMPLATE_GROUP;
        $this->B->conf_val['option']['url'] = SF_BASE_LOCATION;
        $this->B->conf_val['option']['site_title'] = 'Site title';
        $this->B->conf_val['option']['site_desc'] = 'My first site';
        $this->B->conf_val['option']['email'] = 'admin@foo.com';
        $this->B->conf_val['option']['charset'] = $_POST['charset'];
    
        // create bad_words table for mysql or sqlite
        if($_POST['dbtype'] == 'mysql')
        {
            // The bad words table. Words ignored by word indexer
            $sql = "CREATE TABLE IF NOT EXISTS {$this->B->conf_val['db']['table_prefix']}bad_words (
                    word varchar(255) NOT NULL default '',
                    lang varchar(4) NOT NULL default '')"; 
            
        }
        elseif($_POST['dbtype'] == 'sqlite')
        {
            // delete the bad_words table if it exist
            $sql = "SELECT tbl_name FROM sqlite_master where tbl_name='bad_words'";
            $result = $this->B->db->query($sql);

            if (DB::isError($result))
            {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
                $success = FALSE;
            }

            if($result->numRows() == 1)
            {
                $sql = "DROP TABLE bad_words";
                $result = $this->B->db->query($sql);

                if (DB::isError($result))
                {
                trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
                $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
                $success = FALSE;
                }
            }        
            // The bad words table. Words ignored by word indexer
            $sql = "CREATE TABLE bad_words (
                    word varchar(255) NOT NULL default '',
                    lang varchar(4) NOT NULL default '')"; 
        
        }
        
        $result = $this->B->db->query($sql);

        if (DB::isError($result))
        {
            trigger_error($result->getMessage()."\n".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            $this->B->setup_error[] = $result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__;
            $success = FALSE;
        }     
        return $success;    
    } 
}

?>
