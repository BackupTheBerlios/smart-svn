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
 * common_bad_words class
 *
 *
 */
class common_bad_words
{  
    /**
     * Constructor
     * @param int $word_length Minimal word length
     * @param string $delimiters Word delimiters
     */
    function common_bad_words()
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
     * add list data in db table
     *
     * @param array $data
     */
    function perform( & $data )
    {
        // get var name to store the result
        $this->B->$data['var'] = array();
        $this->result          = & $this->B->$data['var'];       
        
        if($data['get_bad_words'])
        { 
            $this->_get_bad_words(); 
        }
        elseif($data['get_bad_words_lang'])
        { 
            $this->_get_bad_words_lang(); 
        }
        elseif($data['delete_bad_words_lang'])
        { 
            $this->_delete_bad_words_lang( $data['delete_bad_words_lang'] ); 
        }        
        
        return TRUE;  
    } 

    /**
     * Get bad word list
     *
     * The word list is stored in $this->bad_word_array
     */
    function _get_bad_words()
    {
        // Init bad word array and _lang var
        $this->B->bad_word_array = array();
        
        $sql = "
            SELECT 
                word 
            FROM
                {$this->B->sys['db']['table_prefix']}bad_words";

        $res = $this->B->db->query($sql);                

        while($result = $res->FetchRow( DB_FETCHMODE_ASSOC ))
        {
            $this->result[trim(stripslashes($result['word']))] = TRUE;
        }
    }
    
    /**
     * Get actif bad word list languages
     *
     * @return array
     */
    function & _get_bad_words_lang()
    { 
        $selected_lang = array();
        
        $sql = "SELECT DISTINCT 
                    lang 
                FROM 
                    {$this->B->sys['db']['table_prefix']}bad_words";

        $res = $this->B->db->query($sql);                

        while($result = &$res->FetchRow( DB_FETCHMODE_ASSOC ))
        {
            $this->result[] = $result['lang'];
        }
    }    
    
    /**
     * Delete actif bad word list languages
     *
     */
    function _delete_bad_words_lang( $lang )
    { 
        $selected_lang = array();
        
        $sql = "DELETE FROM 
                    {$this->B->sys['db']['table_prefix']}bad_words
                WHERE
                    lang='{$lang}'";

        $this->B->db->query($sql);                
    }        
}


?>
