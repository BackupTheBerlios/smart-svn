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
 * module loader of the user module
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
include_once SF_BASE_DIR . '/admin/modules/mailarchiver/class.mailarchiver.php';
// the mail class
include_once SF_BASE_DIR . '/admin/modules/mailarchiver/MAIL/IMAP.php';

// Set up class, initiate a mailbox connection
$msg =& new Mail_IMAP();

//User Class instance
$B->marchiver = & new mailarchiver;

$lists = $B->marchiver->get_lists( array('lid','emailserver','emailuser','emailpasswd','folder') );

foreach ($lists as $account)
{
    if (PEAR::isError($msg->connect('pop3://'.$account['emailuser'].':'.$account['emailpasswd'].'@'.$account['emailserver'].':110/INBOX')))
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
            
            $data['lid']     = $account['lid'];
            $data['subject'] = $msg->header[$mid]['subject'];
            $data['sender']  = $msg->header[$mid]['from_personal'][0];
            $data['mdate']   = date('Y-m-d h:i:s', $msg->header[$mid]['udate']);
            $data['mes_id']  = md5($msg->header[$mid]['message_id']);

            $body = $msg->getBody($mid, $pid);
            $mbody = '';
            
            if ($body['ftype'] == 'text/plain')
            {
                $data['mbody'] = nl2br(htmlspecialchars($body['message']));
            }
            else
            {
                $data['mbody'] = $body['message'];
            }

            $mes_folder = FALSE;

            // Now the attachments
            if (isset($msg->attachPid[$mid]) && count($msg->attachPid[$mid]) > 0)
            {
                // get list messages attachment folder string
                $mes_folder = $B->util->unique_md5_str();
                $path = SF_BASE_DIR . '/data/mailarchiver/'.$account['folder']./. $mes_folder;

                if(!@mkdir($path, 0775))
                {
                    trigger_error('Unable to create list message folder', E_USER_ERROR);
                }  
                
                foreach ($msg->attachPid[$mid] as $i => $aid)
                {
                   $pid = $msg->attachPid[$mid][$i];
                   $filename = $msg->attachFname[$mid][$i];
                   $filetype = $msg->attachFtype[$mid][$i];
                   $filesize = $msg->attachFsize[$mid][$i];
                   
                    // Parse header information
                    //$msg->getHeaders($mid, $pid);
                    $msg->getParts($mid, $pid);
                    $body = $msg->getBody($mid, $pid);
                    
                    if($f = @fopen($path.'/'.$filename, 'wb'))
                    {
                        @fwrite($f, $body, $filesize);
                        @fclose($f);
                    }
                    
                }
            }
            if (FALSE !== $mes_folder)
                $data['folder']  = $mes_folder;

            $B->marchiver->add_message( $data );

            // Clean up left over variables
            $msg->unsetParts($mid);
            $msg->unsetHeaders($mid);
        }    
    }
}
// Close the stream
$msg->close();

?>
