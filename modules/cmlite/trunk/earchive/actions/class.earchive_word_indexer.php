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
 * Word indexer class
 * It extract words from text strings and store the crc32 checksum of 
 * the words in a table 
 *
 *
 */
class earchive_word_indexer
{
    /**
     * Default word delimiters
     * 
     */
    var $_delimiters = " \"'<>;.!`#+*~´\$\\?,:(){}[]%/";
    /**
     * Mimimal default word length
     * 
     */
    var $_word_length = 2;
    /**
     * Flag if bad words table is empty (stopwords)
     * 
     */    
    var $_rebuild       = FALSE;
    /**
     * Preg pattern to stript out strings
     * 
     */
    var $_pattern = array (
                "'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                "'<[\/\!]*?[^<>]*?>'si",           // Strip out HTML tags
                "'[\r\n]+'",                       // Strip out white space
                "'&(quot|#34);'i",                 // Replace HTML entities
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i");                  

    /**
     * chars to replace by space
     * 
     */    
     var $_convert_str = array('"','\'','<','>','(',')','{','}','[',']','.',',',';',':','?','%','\\','#','$','*','=','/','|','!','~','°','@','_','`','´','^','’','‘','¨','©','®','«','»','·','×');

    
    /**
     * Constructor
     * @param int $word_length Minimal word length
     * @param string $delimiters Word delimiters
     */
    function earchive_word_indexer()
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
     * Launch some word indexer features
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        $this->_mid = $data['mid'];
        $this->_lid = $data['lid'];

        // Delete words from the index of a message or a complete list
        if($data['delete_words'] == TRUE)
        {
            if($data['mid'])
            {
                $this->_delete_words( 'mid', $data['mid'] );
            }
            elseif($data['lid'])
            {
                $this->_delete_words( 'lid', $data['lid'] );
            }
            return TRUE;
        }

        // get bad words
        if( !isset($this->B->tmp_get_bad_words) )
        {         
            M( MOD_COMMON, 
               'bad_words',
               array('get_bad_words' => TRUE,
                     'var'           => 'bad_word_array'));
                               
            $this->B->tmp_get_bad_words = TRUE;
        }
        
        // Delete the whole index to rebuild it
        if( ($data['rebuild'] == TRUE) && !isset($this->B->tmp_rebuild_index))
        {              
            // Delete old content
            $this->_delete_table_index();
            $this->B->tmp_rebuild_index = TRUE;
        } 
        
        // index the content
        $this->_indexing_words( $data['content'] );
        
        return TRUE;  
    } 

    /**
     * Extract the words from a text string
     *
     * @param string $string Text string
     * @return array Words array
     */
    function & _split_words( & $string )
    {
        $string = preg_replace($this->_pattern, "", $string);
        $string = str_replace($this->_convert_str," ",$string);
        $content_array = explode(" ",$string);  
        $array_content = array_count_values($content_array);
        $_tmp = array();

        while ($word = each ($array_content))
        {     
            if(strlen($word[0]) > $this->_word_length)
            {
                $word[0] = strtolower($word[0]);
                if($this->B->bad_word_array[$word[0]] != TRUE)
                {       
                    $_tmp[] = $word[0];
                }
            }
        }
        return $_tmp;
    }
    
    /**
     * Make the words index of a text string and insert the content in the index table
     *
     * @param string $content Text string
     */
    function _indexing_words( & $content )
    {  
        // Split content text string in words
        $_content = $this->_split_words( $content );

        $_insert_string = '';
        $_comma = '';
    
        // Build the INSERT string
        foreach( $_content as $value )
        {
            $_insert_string .= $_comma.'('.crc32($value).','.$this->_mid.','.$this->_lid.') ';
            $_comma = ',';
        }
        
        // If insert string is not empty insert the words in the table
        //
        if(!empty($_insert_string))
        {
            // Insert new content
            $sql = "
                INSERT DELAYED INTO 
                    {$this->B->sys['db']['table_prefix']}earchive_words_crc32
                    (word, mid, lid)
                VALUES 
                    {$_insert_string}";

            $result = $this->B->db->query( $sql );
            
            if (DB::isError($result)) 
            {
                trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            }
        }
    }

    /**
     * Delete the whole word index
     *
     * @param string $db_table Table name
     */
    function _delete_table_index()
    {
        $sql = "
            DELETE FROM  
                {$this->B->sys['db']['table_prefix']}earchive_words_crc32";
                
        $result = $this->B->db->query($sql);  
            
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }        
    }

    /**
     * Delete a word from the index
     *
     * @param string $db_table Table name
     * @param string $row_name Row name
     * @param int $row_value Row value
     */
    function _delete_words( $row_name, $row_value )
    {
        $sql = "
            DELETE FROM  
                {$this->B->sys['db']['table_prefix']}earchive_words_crc32   
            WHERE 
                {$row_name}={$row_value}";
                
        $result = $this->B->db->query($sql); 
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }         
    }     
}


?>
