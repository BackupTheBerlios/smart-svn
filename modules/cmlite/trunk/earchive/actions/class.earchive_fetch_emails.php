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
 * earchive_fetch_emails class 
 *
 */

// the PEAR IMAP class
include_once (SF_BASE_DIR . 'modules/common/PEAR/MAIL/IMAP.php');
 
class earchive_fetch_emails
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
    function earchive_fetch_emails()
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
     * fetch emails from accounts
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // Set up class, initiate a mailbox connection
        $this->_msg = & new Mail_IMAP();

        // fetch list with specific status
        if( isset($data['status']) )
        {
            $status = $data['status'];
        }
        else
        {
            $status = NULL;
        }

        // get all available lists
        $this->B->M( MOD_EARCHIVE, 
                     'get_lists', 
                     array( 'var'    => 'all_lists',
                            'status' => $status,
                            'fields' => array('lid','emailserver','folder')));        

        if(count($this->B->all_lists) > 0)
        {            
            // loop through the email accounts
            foreach ($this->B->all_lists as $account)
            {

                if (!$this->_msg->connect($account['emailserver']))
                {
                    $_error  = $this->_msg->alerts()."\n";
                    $_error .= $this->_msg->errors(); 
                    trigger_error("Unable to build a connection to: ".$account['emailserver']."\n\n".$_error, E_USER_ERROR);
                    continue; // on error next list
                }
                
                $this->_fetch_messages( $account );      
                $this->_msg->expunge();             
            }            
            // Close the stream
            $this->_msg->close();             
        }
    }
    /**
     * fetch all messages from one account
     *
     * @param array $account account data
     */    
    function _fetch_messages( & $account )
    {
        // Retrieve message count
        $msgcount = $this->_msg->messageCount();
    
        if ($msgcount > 0)
        {
            /*
             * Each message of a mailbox is numbered offset from 1
             * Create the $mid (message id) and recursively gather
             * message information.
            */
            for ($mid = 1; $mid <= $msgcount; $mid++)
            {
                // init message data array
                $this->_message_data = array( 'lid'        => 0,
                                              'message_id' => '',
                                              'root_id'    => '',
                                              'parent_id'  => '',
                                              'enc_type'   => '',
                                              'sender'     => '',
                                              'subject'    => '',
                                              'mdate'      => '',
                                              'body'       => '',
                                              'folder'     => '',
                                              'header'     => '');
                
                // Parse inline/attachment information specific to this part id
                //
                // See member variables begining with in or attach for
                // available information
                $this->_msg->getParts($mid, 0, false, array('retrieve_all' => TRUE));
    
                // Parse header information
                $this->_msg->getHeaders( $mid );
                // fetch header data
                $this->_fetch_headers  ( $mid );  
                // fetch body data
                $this->_fetch_body     ( $mid );
                
                // list id of this message
                $this->_message_data['lid'] = $account['lid'];
                
                $this->_message_data['folder'] = $this->B->db->quoteSmart($_folder = commonUtil::unique_md5_str());
                
                if($this->B->sys['module']['earchive']['get_header'] == TRUE)
                {
                    $this->_message_data['header'] = $this->B->db->quoteSmart($this->_msg->getRawHeaders( $mid ));
                }
                else
                {
                    $this->_message_data['header'] = "'".""."'";
                }
                
                // add message in db table
                if(FALSE == ($message_id = $this->B->M( MOD_EARCHIVE, 'add_message', $this->_message_data ) ))
                {
                    // Clean up left over variables
                    $this->_msg->unsetParts($mid);
                    $this->_msg->unsetHeaders($mid);                
                    trigger_error('Cannot add message: '.var_export($this->_message_data).__FILE__.' '.__LINE__, E_USER_ERROR);
                    continue;// switch to next message
                }  
                
                // fetch attachments data
                $this->_fetch_attach ( $message_id, $mid, $account, $_folder );                

                // Clean up left over variables
                $this->_msg->unsetParts($mid);
                $this->_msg->unsetHeaders($mid);                
                // delte message from inbox
                $this->_msg->delete($mid);
                
                // prepare content for indexing
                $_content = $this->_message_data['subject'].' '.$this->_message_data['sender'].' '.$this->_message_data['body'];
                // indexing content
                $this->B->M( MOD_EARCHIVE, 
                             'word_indexer', 
                             array( 'content' => $_content,
                                    'mid'     => $message_id, 
                                    'lid'     => $account['lid']));   
            }
        }
    }
    /**
     * fetch all attachments from a message
     *
     * @param int $mid message id
     * @param array $account account data
     */ 
    function _fetch_attach( $message_id, $mid, $account, $_folder )
    {
        // check if there are attachments attachments
        if (!isset($this->_msg->msg[$mid]['at']['pid']) && count($this->_msg->msg[$mid]['at']['pid']) == 0)
        {
            return TRUE;
        }
        else
        {    
            $path = SF_BASE_DIR . 'data/earchive/'.$account['folder'].'/'.$_folder;
    
            if(!@mkdir($path, SF_DIR_MODE))
            {
                trigger_error('Unable to create list message folder', E_USER_ERROR);
            }  
                    
            foreach ($this->_msg->msg[$mid]['at']['pid'] as $i => $aid)
            {
                $att_data = array();
                
                $body = $this->_msg->getBody( $mid, $this->_msg->msg[$mid]['at']['pid'][$i]);
       
                $_file = $this->_decodeEmailHeader($this->_msg->msg[$mid]['at']['fname'][$i]);
                
                $att_data['path_file'] = $path.'/'.$_file;
                $att_data['file']      = $this->B->db->quoteSmart($_file);
                $att_data['type']      = $this->B->db->quoteSmart($this->_msg->msg[$mid]['at']['ftype'][$i]);
                $att_data['size']      = $this->_msg->msg[$mid]['at']['fsize'][$i];
                $att_data['mid']       = $message_id;
                $att_data['lid']       = $account['lid'];
                $att_data['content']   = $body['message'];
                
                $this->B->M( MOD_EARCHIVE,
                             'add_attach',
                             $att_data);                             
            }
        }
        return TRUE;
    }
    /**
     * fetch body of a message
     *
     * @param int $mid message id
     */
    function _fetch_body( $mid )
    {
        $this->_message_data['body'] = '';

        // Show inline parts first
        if (isset($this->_msg->msg[$mid]['in']['pid']) && count($this->_msg->msg[$mid]['in']['pid']) > 0)
        {
            foreach($this->_msg->msg[$mid]['in']['pid'] as $i => $inid)
            {
                $body = $this->_msg->getBody( $mid, $this->_msg->msg[$mid]['in']['pid'][$i] );

                $this->_message_data['body'] .= $body['message'];
            }
        }
        else
        {
            $body = $this->_msg->getBody( $mid );
            $this->_message_data['body'] .= $body['message'];                  
        }
        
        $this->_message_data['enc_type'] = $body['ftype'];
        $this->_message_data['body'] = $this->B->db->quoteSmart($this->_message_data['body']);
        
        return TRUE;
    }
    /**
     * fetch header data of a message
     *
     * @param int $mid message id
     */
    function _fetch_headers( $mid )
    {
        if (!isset($this->_msg->header[$mid]['subject']) || empty($this->_msg->header[$mid]['subject']))
        {
            $this->_message_data['subject'] = "no subject provided";
        }
        else
        {
            // decode subject string                
            $this->_message_data['subject'] = $this->_decodeEmailHeader($this->_msg->header[$mid]['subject']);
                
            $this->_message_data['subject'] = str_replace("<","&lt;",$this->_message_data['subject']);
            $this->_message_data['subject'] = str_replace(">","&gt;",$this->_message_data['subject']);          
        }
                
        if(!empty($this->_msg->header[$mid]['from_personal'][0]))
        {
            $this->_message_data['sender'] = '';
            $x = 0;
            $comma = '';  
            
            // get the from string
            foreach($this->_msg->header[$mid]['from_personal'] as $f)
            {
                $this->_message_data['sender'] .= $comma.$this->_msg->header[$mid]['from_personal'][$x].' &lt;'.$this->_msg->header[$mid]['from'][$x].'&gt;' ;
                $comma = ', ';
                $x++;
            }
            // decode from string
            $this->_message_data['sender'] = $this->_decodeEmailHeader($this->_message_data['sender']);
        }
        else
        {
            // get from address
            $this->_message_data['sender'] = $this->_decodeEmailHeader($this->_msg->header[$mid]['fromaddress']).' '.$this->_msg->header[$mid]['senderaddress'];
            $this->_message_data['sender'] = str_replace("<","&lt;",$this->_message_data['sender']);
            $this->_message_data['sender'] = str_replace(">","&gt;",$this->_message_data['sender']);
        }     
        
        if(empty($this->_message_data['sender']))
        {
            $this->_message_data['sender'] = "&lt; no sender &gt;";
        }

        // get message_id
        $this->_message_data['message_id']  = md5($this->_msg->header[$mid]['message_id']);

        // check if this message is related to a previous message (reply)
        if(!empty($this->_msg->header[$mid]['references']))
        {
            $chunk = preg_split("/[[:blank:]]/", $this->_msg->header[$mid]['references']);
            if($chunk[0] != $this->_msg->header[$mid]['message_id'])
            {
                $this->_message_data['root_id']    = md5($chunk[0]);
                $this->_message_data['parent_id']  = md5(end($chunk));
                
                // check if this parent message is in db table
                if($this->_check_parent( $this->_message_data['parent_id'] ) == 0)
                {
                    $this->_message_data['root_id']    = '';
                    $this->_message_data['parent_id']  = '';                
                }
            }
        }
        
        $this->_message_data['sender']  = $this->B->db->quoteSmart($this->_html_activate_links($this->_message_data['sender']));
        $this->_message_data['subject'] = $this->B->db->quoteSmart($this->_message_data['subject']);
        $this->_message_data['mdate']   = $this->B->db->quoteSmart(date('Y-m-d H:i:s', $this->_msg->header[$mid]['udate']));
    
        return TRUE;
    }
    
    /**
     * Convert textual links and emails in html tags
     *
     * @return string Converted text
     */    
    function _html_activate_links(&$str) 
    {
        $str = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1" target="_blank">\\1</a>', $str);
        $str = eregi_replace('([[:space:]()[{}])(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '\\1<a href="http://\\2" target="_blank">\\2</a>', $str);
        $str = eregi_replace('([_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})','<a href="mailto:\\1">\\1</a>', $str);
        return $str;
    } 
    /**
     * Given a header, this function will decode it
     * according to RFC2047. Probably not *exactly*
     * conformant, but it does pass all the given
     * examples (in RFC2047).
     * function from PEAR Mail_Mime
     *
     * @param string Input header value to decode
     * @return string Decoded header value
     */
    function _decodeEmailHeader(&$input)
    {
        // Remove white space between encoded-words
        $input = preg_replace('/(=\?[^?]+\?(q|b)\?[^?]*\?=)(\s)+=\?/i', '\1=?', $input);

        // For each encoded-word...
        while (preg_match('/(=\?([^?]+)\?(q|b)\?([^?]*)\?=)/i', $input, $matches)) {

            $encoded  = $matches[1];
            $charset  = $matches[2];
            $encoding = $matches[3];
            $text     = $matches[4];

            switch (strtolower($encoding)) {
                case 'b':
                    $text = base64_decode($text);
                    break;

                case 'q':
                    $text = str_replace('_', ' ', $text);
                    preg_match_all('/=([a-f0-9]{2})/i', $text, $matches);
                    foreach($matches[1] as $value)
                        $text = str_replace('='.$value, chr(hexdec($value)), $text);
                    break;
            }

            $input = str_replace($encoded, $text, $input);
        }

        return $input;
    } 
    
    /**
     * Check if a parent message exist
     *
     * @param $parent_id
     * @return int Number of parents 0 or 1
     */      
    function _check_parent( &$parent_id )
    {
        $sql = "
            SELECT
                mid
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                message_id='{$parent_id}' LIMIT 1";  
        
        $result = $this->B->db->query( $sql );
        
        return $result->numRows();
    }
}

?>
