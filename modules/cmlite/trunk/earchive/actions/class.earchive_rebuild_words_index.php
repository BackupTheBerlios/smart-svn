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
 * earchive_rebuild_words_index class 
 *
 */
 
class earchive_rebuild_words_index
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
    function earchive_rebuild_words_index()
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
     * Set options for this module
     *
     * @param array $data
     */
    function perform( $data )
    {                    
        $fields = array('mid','lid','subject','body','sender');
        $result = $this->_get_all_messages( $fields );
                
        if( FALSE !== $result )
        {
            while($row = $result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $content  = '';
                $content .= commonUtil::stripslashes($row['sender']);
                $content .= commonUtil::stripslashes($row['subject']);
                $content .= commonUtil::stripslashes($row['body']);
                
                $this->B->M( MOD_EARCHIVE, 
                             'word_indexer', 
                             array( 'content' => $content,
                                    'mid'     => $row['mid'], 
                                    'lid'     => $row['lid'], 
                                    'rebuild' => true));      
            }
            return TRUE;
        } 
        
        return FALSE;
    } 
    /**
     * get all messages
     *
     * @param array $fields Field names of the list db table
     * @return array Lists data 
     */ 
    function & _get_all_messages( $fields )
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
            return FALSE;
        }

        return $result;
    }       
}

?>
