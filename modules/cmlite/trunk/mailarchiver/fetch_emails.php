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
define ('SF_BASE_DIR', dirname(dirname(dirname(dirname(__FILE__)))));

// Define section area
define ('SF_SECTION', 'admin');

// Include the base file
include (SF_BASE_DIR . '/admin/include/base.inc.php');

// the user class
include_once (SF_BASE_DIR . '/admin/modules/mailarchiver/class.mailarchiver.php');

// the PEAR IMAP class
include_once ('MAIL/IMAP.php');

// Set up class, initiate a mailbox connection
$msg =& new Mail_IMAP();

//User Class instance
$B->marchiver = & new mailarchiver;

$lists = $B->marchiver->get_lists( array('lid','emailserver','folder'), 'status>1' );

foreach ($lists as $account)
{
    if (PEAR::isError($msg->connect($account['emailserver'])))
        trigger_error('Unable to build a connection', E_USER_ERROR);

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
            
            $data['lid']      = $account['lid'];
            $data['subject']  = $B->db->quoteSmart($msg->header[$mid]['subject']);
            $data['sender']   = $B->db->quoteSmart($msg->header[$mid]['reply_toaddress']);
            $data['mdate']    = $B->db->quoteSmart(date('Y-m-d h:i:s', $msg->header[$mid]['udate']));
            
            $body = $msg->getBody($mid, $pid);
            $mbody = '';
            
            if ($body['ftype'] == 'text/plain')
            {
                $data['body'] = $B->db->quoteSmart(nl2br($body['message']));
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
            
            if(FALSE === ($message_id = $B->marchiver->add_message( $data )))
                trigger_error('Cannot add message: '.var_export($data).__FILE__.' '.__LINE__, E_USER_ERROR);

            // Now the attachments
            if ((TRUE === $is_attach) && ($message_id !== FALSE))
            {
                $path = SF_BASE_DIR . '/data/mailarchiver/'.$account['folder'].'/'. $mes_folder;

                if(!@mkdir($path, SF_DIR_MODE))
                {
                    trigger_error('Unable to create list message folder', E_USER_ERROR);
                }  
                
                foreach ($msg->attachPid[$mid] as $i => $aid)
                {
                   $pid = $msg->attachPid[$mid][$i];
                   $att_data = array();
                   
                   $att_data['file'] = $B->db->quoteSmart($msg->attachFname[$mid][$i]);
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
                        $B->marchiver->add_attach( $message_id, $account['lid'], $att_data );
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

?>
