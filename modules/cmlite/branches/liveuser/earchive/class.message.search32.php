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
 * Message search class
 * based on crc32 word indexing. It support boolean AND and OR searching
 *
 *
 * @link http://open-publisher.net/
 * @author Armand Turpel <armand@a-tu.net>
 * @version $Revision: 1.1.2.4 $
 * @since 2003-02-26
 */
 
class message_search extends earchive
{
    var $url_add = '';
    /**
     * chars to replace by space
     * 
     */    
     var $_convert_str = array('"','\'','<','>','(',')','{','}','[',']','.',',',';',':','?','%','\\','#','$','*','=','/','|','!','~','°','@','_','`','´','^','’','‘','¨','©','®','«','»','·','×');
    /**
     * Preg pattern to stript out strings
     * 
     */
    var $_pattern = array (
                "'<[\/\!]*?[^<>]*?>'si",           // Strip out HTML tags
                "'[\r\n]+'");     
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
     * Search in document titles and texts with pagination.
     * Stripslashes is done on all results.
     *
     * 
     *  
     * @param string $fields Table fields to get (comma separated)
     * @return array Associative array
     */  
    function message_search( &$data )
    {
        // Clear/init array
        // Load bad word (stopwords) list
        $this->get_bad_words();
        // put words to search in an array
        $_words = $this->_split_words( stripslashes($data['search']) );
            
        // Default boolean is 'OR';
        if(empty($data['bool']))
        {
            $data['bool'] = 'OR';
        }

        $comma   = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.'m.'.$f;
            $comma = ',';
        }
        
        if(!empty($data['order']))
        {
            $order = ' ORDER BY m.'.ltrim($data['order']).' ';
        }
        else
        {
            $order = " ORDER BY m.mdate DESC ";
        }

        if(empty($data['limit']))
        {
            $data['limit'] = 200;
        }
        
        // Get also list name and archive (list) id
        $_l_table = '';
        $_l_where = '';
        if( $data['get_list'] == TRUE )
        {
            $_fields .= " ,l.name AS list_name, l.lid AS list_id";
            $_l_table = "{$GLOBALS['B']->sys['db']['table_prefix']}earchive_lists AS l,";
            $_l_where = " AND l.lid=m.lid";
        }
        
        // get messages only from specific archives
        $this->_l_id = '';
        if( !empty($data['list_id']) )
        {
            $this->_l_id = ' AND l.lid IN(' . $data['list_id'] . ') ';
        }
        
        // Build searching sql string if boolean between words = OR
        //
        if((strtolower($data['bool']) == 'or'))
        {
            $_bool = "OR";

            // Init var
            $_or = "";            
            $text_sql = '(';
            // Build sql search 
            foreach($_words as $str)
            {
                $text_sql  .= $_or.' w.word='.crc32($str).' ';
                $_or = $_bool;
            }
            $text_sql .= ')';
                
            if($GLOBALS['B']->auth->is_user)
            {
                $w_status = 'l.status>1';
            }
            else
            {
                $w_status = 'l.status=2';
            }
            
            // The entire sql string to get documents     
            $sql = "
                SELECT DISTINCT 
                    {$_fields}
                FROM
                    {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages AS m,
                    {$GLOBALS['B']->sys['db']['table_prefix']}earchive_lists AS l,
                    {$GLOBALS['B']->sys['db']['table_prefix']}earchive_words_crc32 AS w
                WHERE
                    m.mid=w.mid 
                AND
                    m.lid=l.lid
                AND
                    {$w_status} 
                    {$this->_l_id}
                AND 
                    {$text_sql}{$order} 
                LIMIT {$data['limit']}";           
        }
        // Build searching sql string if boolean between words = AND
        //
        elseif((strtolower($data['bool']) == 'and'))
        {
            $_compare = array();
            $x = 0;
            
            foreach($_words as $_w)
            {
                $word_sql  = ' word='.crc32($_w).' ';
                // Get the result for this word
                $_compare[$x] = $this->_get_result( $word_sql );
                    
                if($x > 0)
                {
                    // Get words which occure in both tables (AND simulation)
                    $_compare[$x] = array_intersect($_compare[$x-1], $_compare[$x]);
                    // Stop if the AND word relation has no results
                    // $result['num'] contains the numbers of results 
                    // needed for the pagination class
                    //
                    if(count($_compare[$x]) == 0)
                    {
                        return FALSE;
                    }
                }
                $x++;
            }

            // check if there are results
            if(!empty($_compare[$x-1]))
            {
                $this->_create_tmp_table();
                $this->_insert_msg_ids( $_compare[$x-1] );
            }
            else
                return FALSE;
                
            // The entire sql string to get documents     
            $sql = "
                SELECT DISTINCT 
                    {$_fields}
                FROM
                    {$GLOBALS['B']->sys['db']['table_prefix']}earchive_messages AS m,
                    {$_l_table}
                    {$this->tmp_search_table} AS tmp
                WHERE
                    m.mid=tmp.mid {$_l_where} {$order}  
                LIMIT {$data['limit']}";
        }

        $result = $GLOBALS['B']->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }

        // get var name to store the result
        $GLOBALS['B']->$data['var'] = array();
        $_result                    = & $GLOBALS['B']->$data['var'];

        if(is_object($result))
        {
            while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                if($data['get_list'] == TRUE)
                {
                    $tmp['list_name'] = stripslashes($row['list_name']);
                    $tmp['list_id']   = stripslashes($row['list_id']);
                }
                $_result[] = $tmp;
            }
        }  
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
     * Get the search result (id_doc) for one single word
     *
     * @param string $word_sql Sql string for this word
     * For all other param:
     * @see docsearch
     * @return array id_doc array
     */
    function & _get_result( &$word_sql )
    {
        if($GLOBALS['B']->auth->is_user)
        {
            $w_status = 'l.status>1';
        }
        else
        {
            $w_status = 'l.status=2';
        }
        // Count all results
        //      
        $sql = "
            SELECT DISTINCT 
                w.mid 
            FROM
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_words_crc32 AS w,
                {$GLOBALS['B']->sys['db']['table_prefix']}earchive_lists AS l
            WHERE
                w.lid=l.lid
            AND 
                {$w_status}
                {$this->_l_id}
            AND 
                {$word_sql}";

        $result = $GLOBALS['B']->db->query($sql);
               
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }    
            
        $data = array();
            
        if(is_object($result))
        {
            while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $data[] = $row['mid'];
            }
        }
        return $data;
    }

    /**
     * Get bad word list
     *
     * @param array $lang Array of language bad words eg. 'en' 'fr' . If array is
     * empty the whole word list is taken
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

        $result = $GLOBALS['B']->db->query($sql);    
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }            

        while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
        {
            $this->bad_word_array[stripslashes($row['word'])] = TRUE;
        }
    }    
    
    /**
     * Create temporary words table for the AND search result 
     *
     *
     */
    function _create_tmp_table()
    {
        $this->tmp_search_table = "earchvetmp";
        $sql = "CREATE TEMPORARY TABLE {$this->tmp_search_table} (mid INT NOT NULL default 0)";
        
        $result = $GLOBALS['B']->db->query($sql);
               
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
    }

    /**
     * Insert word ids from an AND search result 
     *
     * @param array $data IDs 
     */    
    function _insert_msg_ids( $data )
    {
        $sql = "INSERT INTO {$this->tmp_search_table} (mid) VALUES ";
        $comma = '';
        
        foreach($data as $id)
        {
            $sql .= $comma.'('.$id.')';
            $comma = ',';
        }
                
        $result = $GLOBALS['B']->db->query($sql);
               
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nSQL: ".$sql."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        return TRUE;
    }    
}

?>
