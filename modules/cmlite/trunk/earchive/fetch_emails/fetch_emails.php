<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * fetch emails from email accounts.
 * This script should be executed by a cronjob
 */

/*
 * Secure include of files from this script
 */
define ('SF_SECURE_INCLUDE', 1);

// Define the absolute path to the Framework base
//
define ('SF_BASE_DIR', dirname(dirname(dirname(dirname(dirname(__FILE__))))));

// Define section area
define ('SF_SECTION', 'admin');

// Include the base file
include (SF_BASE_DIR . '/admin/include/base.inc.php');

// the user class
include_once (SF_BASE_DIR . '/admin/modules/earchive/class.earchive.php');

// the PEAR IMAP class
include_once (SF_BASE_DIR . '/admin/modules/user/PEAR/MAIL/IMAP.php');

// Set up class, initiate a mailbox connection
$msg =& new Mail_IMAP();

//User Class instance
$B->earchive = & new earchive;

// word indexer class
include_once(SF_BASE_DIR.'/admin/modules/earchive/class.sfWordIndexer.php');
$word_indexer = & new word_indexer();

// earchive util class
include_once(SF_BASE_DIR.'/admin/modules/earchive/class.util.php');

$B->e_util = & new earchiveUtil;

// get email accounts
$lists = $B->earchive->get_lists( array('lid','emailserver','folder'), 'status>1' );

if(count($lists) > 0)
{

    foreach ($lists as $account)
    {
        if (PEAR::isError($msg->connect($account['emailserver'])))
        {
            trigger_error('Unable to build a connection to: '.$account['emailserver'], E_USER_ERROR);
            continue; // on error next list
        }
    
        // Retrieve message count
        $msgcount = $msg->messageCount();
    
        if ($msgcount > 0)
        {
            /*
             * Each message of a mailbox is numbered offset from 1
             * Create the $mid (message id) and recursively gather
             * message information.
            */
            for ($mid = 1; $mid <= $msgcount; $mid++)
            {
                // Get the default part id
                $pid = $msg->getDefaultPid($mid);
    
                // Parse header information
                $msg->getHeaders($mid, $pid);
    
                // Parse inline/attachment information specific to this part id
                //
                // See member variables begining with in or attach for
                // available information
                $msg->getParts($mid, $pid);
                
                $data = array();
                
                $x = 0;
                $comma = '';
                $from = '';
                
                //echo "<pre>";print_r($msg->header[$mid]);echo "</pre>";
                
                // check if header from_personal is available
                if(!empty($msg->header[$mid]['from_personal'][0]))
                {
                    // get the from string
                    foreach($msg->header[$mid]['from_personal'] as $f)
                    {
                        $from .= $comma.$msg->header[$mid]['from_personal'][$x].' &lt;'.$msg->header[$mid]['from'][$x].'&gt;' ;
                        $comma = ', ';
                        $x++;
                    }
                    // decode from string
                    $from = $B->e_util->decodeEmailHeader($from);
                }
                else
                {
                    // get from address
                    $from = $B->e_util->decodeEmailHeader($msg->header[$mid]['fromaddress']).' '.$msg->header[$mid]['senderaddress'];
                    $from = str_replace("<","&lt;",$from);
                    $from = str_replace(">","&gt;",$from);
                }
                
                // decode subject string                
                $subject =$B->e_util->decodeEmailHeader($msg->header[$mid]['subject']);
                
                $subject = str_replace("<","&lt;",$subject);
                $subject = str_replace(">","&gt;",$subject);               
                  
                $data['lid']      = $account['lid'];
                $data['subject']  = $B->db->quoteSmart($subject);
                $data['sender']   = $B->db->quoteSmart($B->e_util->html_activate_links($from));
                $data['mdate']    = $B->db->quoteSmart(date('Y-m-d H:i:s', $msg->header[$mid]['udate']));
                
                $body = $msg->getBody($mid, $pid);
                $mbody = '';
                
                if ($body['ftype'] == 'text/plain')
                {
                    $mess = str_replace("<","&lt;",$body['message']);
                    $mess = str_replace(">","&gt;",$mess);
                    $data['body'] = $B->db->quoteSmart(nl2br($B->e_util->html_activate_links($mess)));
                }
                else
                {
                    $data['body'] = $B->db->quoteSmart($body['message']);
                }
        
                $mes_folder = FALSE;
                $is_attach  = FALSE;
    
                // check if there are attachments attachments
                if (isset($msg->attachPid[$mid]) && count($msg->attachPid[$mid]) > 0)
                {
                    $is_attach = TRUE;
                    // get list messages attachment folder string
                    $mes_folder = $B->util->unique_md5_str();
                    $data['folder']  = $B->db->quoteSmart($mes_folder);
                }
                else
                {
                    $data['folder']  = '0';
                }
                
                $_content = '';
                
                if(FALSE === ($message_id = $B->earchive->add_message( $data )))
                {
                    trigger_error('Cannot add message: '.var_export($data).__FILE__.' '.__LINE__, E_USER_ERROR);
                    continue;// switch to next message
                }

                // index content
                $_content = $data['subject'].' '.$data['sender'].' '.$data['body'];
                $word_indexer->indexing_words( $_content, 'earchive_words_crc32', array('mid' => $message_id, 'lid' => $account['lid']));
                
                // Now the attachments
                if (TRUE === $is_attach)
                {
                    $path = SF_BASE_DIR . '/data/earchive/'.$account['folder'].'/'. $mes_folder;
    
                    if(!@mkdir($path, SF_DIR_MODE))
                    {
                        trigger_error('Unable to create list message folder', E_USER_ERROR);
                    }  
                    
                    foreach ($msg->attachPid[$mid] as $i => $aid)
                    {
                       $pid = $msg->attachPid[$mid][$i];
                       $att_data = array();
                       
                       $att_data['file'] = $B->db->quoteSmart($B->e_util->decodeEmailHeader($msg->attachFname[$mid][$i]));
                       $att_data['type'] = $B->db->quoteSmart($msg->attachFtype[$mid][$i]);
                       $att_data['size'] = $msg->attachFsize[$mid][$i];
                       
                        // Parse header information
                        $msg->getParts($mid, $pid);
                        $body = $msg->getBody($mid, $pid);
    
                        if($f = @fopen($path.'/'.$msg->attachFname[$mid][$i], 'wb'))
                        {
                            @fwrite($f, $body['message'], $att_data['size']);
                            @fclose($f);
                            @chmod($path.'/'.$msg->attachFname[$mid][$i], SF_FILE_MODE);
                            $B->earchive->add_attach( $message_id, $account['lid'], $att_data );
                        }
                    }
                }
                // Clean up left over variables
                $msg->unsetParts($mid);
                $msg->unsetHeaders($mid);
                $msg->delete($mid);
            } 
            $msg->expunge();
        }
    }
    // Close the stream
    $msg->close();

}
else
{
    trigger_error("No list available\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_NOTICE);
}

?>
