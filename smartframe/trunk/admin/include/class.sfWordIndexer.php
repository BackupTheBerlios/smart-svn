<?php
// File: $Id: word_indexer.php,v 1.1.2.6 2004/03/24 13:18:24 atur Exp $
// ----------------------------------------------------------------------
// Open Publisher
// Copyright (c) 2002-2003
// by Armand Turpel
// http://open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * Word indexer class
 * It extract words from text strings and store the crc32 checksum of 
 * the words in a table 
 *
 *
 * @link http://open-publisher.net/
 * @author Armand Turpel <armand@a-tu.net>
 * @version $Revision: 1.1.2.6 $
 * @since 2004-02-26
 * @package default
 */
class word_indexer
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
    var $_word_length = 3;
    /**
     * Words to ignore list (stopwords)
     * 
     */
    var $bad_word_array = array();
    /**
     * Flag if bad words array is loaded (stopwords)
     * 
     */    
    var $_get_bad_words = FALSE;
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
    function word_indexer( $word_length = 2 )
    {   
        $this->_word_length = $word_length;
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
            if(strlen($word[0])>$this->_word_length)
            {
                $word[0] = strtolower($word[0]);
                if(!isset($this->bad_word_array[$word[0]]))
                {       
                    $_tmp[] = $word[0];
                }
            }
        }
        return $_tmp;
    }
    
    /**
     * Make the words index of a text string and insert the content in a table
     *
     * @param string $content Text string
     * @param string $db_table Table name
     * @param string $row_name Row name of the module item to index eg. 'id_doc'
     * @param int    $row_value Row value of the module item to index
     * @param bool   $rebuild If TRUE it delete the whole index
     * @return bool  TRUE on success else FALSE
     */
    function indexing_words( & $content, $db_table, $row, $rebuild = FALSE )
    {
        // Check if content string is empty
        if(empty($content))
        {
            trigger_error("No content to index!!\nFILE:".__FILE__."/nLINE:".__LINE__,E_USER_WARNING); 
            return FALSE;
        }
        
        // Check if table name is defined
        if(empty($db_table))
        {
            trigger_error("Indexing table name not defined!!\nFILE:".__FILE__."/nLINE:".__LINE__,E_USER_WARNING); 
            return FALSE;
        }

        // Check if table row name is defined
        if(!is_array($row) || (count($row) == 0))
        {
            trigger_error("Indexing row name not defined!!\nFILE:".__FILE__."/nLINE:".__LINE__,E_USER_WARNING); 
            return FALSE;
        }

        if(($rebuild == TRUE) && $this->_rebuild == FALSE)
        {
            $this->_rebuild = TRUE;    
            // Delete old content
            $this->delete_table_index( $db_table );
        }

        if($this->_get_bad_words == FALSE)
        {
            $this->_get_bad_words = TRUE;
            // Load bad words (stopwords) array
            $this->get_bad_words();
        }
        
        // Split content text string in words
        $_content = $this->_split_words( $content );
        
        $_insert_string = '';
        $_comma = '';
    
        $_ins = '';
        $row_name = '';
        foreach($row as $key => $val)
        {
            $_ins .= ','.$val;
            $row_name .= ','.$key;
        }
    
        // Build the INSERT string
        foreach( $_content as $value )
        {
            $_insert_string .= $_comma.'('.crc32(htmlentities($value)).$_ins.') ';
            $_comma = ',';
        }
        
        // If insert string is not empty insert the words in the table
        //
        if(!empty($_insert_string))
        {
            if($rebuild == FALSE)
            {
                list($row_n, $row_v) = each($row);
                // Delete old content
                $this->delete_words( $db_table, $row_n, $row_v );
            }
            
            // Insert new content
            $sql = "
                INSERT INTO 
                    {$GLOBALS['B']->sys['db']['table_prefix']}{$db_table} 
                    (word {$row_name})
                VALUES 
                    {$_insert_string}";
                
            $res = $GLOBALS['B']->db->query( $sql ); 
            
            if (DB::isError($res)) 
            {
                trigger_error($res->getMessage()."\n".$res->getCode()."\nFILE:".__FILE__."/nLINE:".__LINE__, E_USER_ERROR);
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }

    /**
     * Delete the whole word index
     *
     * @param string $db_table Table name
     */
    function delete_table_index( $db_table )
    {
        $sql = "
            DELETE FROM  
                {$GLOBALS['B']->sys['db']['table_prefix']}{$db_table}";
                
        $GLOBALS['B']->db->query($sql);         
    }

    /**
     * Delete a word from the index
     *
     * @param string $db_table Table name
     * @param string $row_name Row name
     * @param int $row_value Row value
     */
    function delete_words( $db_table, $row_name, $row_value )
    {
        $sql = "
            DELETE FROM  
                {$GLOBALS['B']->sys['db']['table_prefix']}{$db_table} 
            WHERE 
                {$row_name}={$row_value}";
                
        $GLOBALS['B']->db->query($sql);         
    }

    /**
     * Get bad word list
     *
     * The word list is stored in $this->bad_word_array
     */
    function get_bad_words()
    {
        // Init bad word array and _lang var
        $this->bad_word_array = array();
        
        $sql = "
            SELECT 
                word 
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}bad_words";

        $res = $GLOBALS['B']->db->query($sql);                

        while($result = &$res->FetchRow( DB_FETCHMODE_ASSOC ))
        {
            $this->bad_word_array[stripslashes($result['word'])] = TRUE;
        }
    }
    
    /**
     * Get actif bad word list languages
     *
     * @return array
     */
    function & get_bad_words_lang()
    { 
        $selected_lang = array();
        
        $sql = "SELECT DISTINCT 
                    lang 
                FROM 
                    {$GLOBALS['B']->sys['db']['table_prefix']}bad_words";

        $res = $GLOBALS['B']->db->query($sql);                

        while($result = &$res->FetchRow( DB_FETCHMODE_ASSOC ))
        {
            $selected_lang[] = $result['lang'];
        }
        return $selected_lang;
    }    
    
    /**
     * Delete actif bad word list languages
     *
     */
    function delete_bad_words_lang( $lang )
    { 
        $selected_lang = array();
        
        $sql = "DELETE FROM 
                    {$GLOBALS['B']->sys['db']['table_prefix']}bad_words
                WHERE
                    lang='{$lang}'";

        $GLOBALS['B']->db->query($sql);                
    }        
}


?>
