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
 * EARCHIVE_SET_OPTIONS class 
 *
 */
 
class EARCHIVE_SET_OPTIONS
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
    function EARCHIVE_SET_OPTIONS()
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
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // set user options 
        // this event comes from the option module (module_loader.php)
        if(isset($_POST['update_earchive_options_wordindex']) && !empty($_POST['earchive_rebuild_index']))
        {              
            include_once(SF_BASE_DIR.'/admin/modules/common/class.sfWordIndexer.php');
            $word_indexer = & new word_indexer();
                
            $fields = array('mid','lid','subject','body','sender');
            $result = $this->get_all_messages( $fields );
                
            if(is_object($result))
            {
                while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
                {
                    $content  = '';
                    $content .= commonUtil::stripslashes($row['sender']);
                    $content .= commonUtil::stripslashes($row['subject']);
                    $content .= commonUtil::stripslashes($row['body']);
                        
                    $word_indexer->indexing_words( $content, 'earchive_words_crc32', array('mid' => $row['mid'], 'lid' => $row['lid']), TRUE);      
                }
            }                
         }
         // set user options 
         // this event comes from the option module (module_loader.php)
         if(isset($_POST['update_earchive_options_fetchemails']) && !empty($_POST['earchive_fetch_emails']))
         {
             // the earchive class
             include_once SF_BASE_DIR . '/admin/modules/earchive/fetch_emails.php';             
         }
    } 
    /**
     * get all messages
     *
     * @param array $fields Field names of the list db table
     * @return array Lists data 
     */ 
    function & get_all_messages( $fields )
    {
        $comma = '';
        foreach ($fields as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages";
        
        $result = $this->B->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        return $result;
    }    
}

?>
