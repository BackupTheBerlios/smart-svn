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
define ('SF_BASE_DIR', dirname(dirname(dirname(dirname(__FILE__)))) . '/');

// Define section area
define ('SF_SECTION', 'admin');

// include default definitions
include_once( SF_BASE_DIR . 'smart/includes/defaults.php' );

// just a dirty workaround class to load
// config.php variables
class workaround
{
    function workaround()
    {
    }
}

// connect to the db
class connect
{
    function connect()
    {
        $this->B = & $GLOBALS['B'];
        
        // include system config array $this->B->sys
        if(file_exists(SF_BASE_DIR . 'modules/common/config/config.php'))
            include_once( SF_BASE_DIR . 'modules/common/config/config.php' );  

        // if setup was done
        if($this->B->sys['info']['status'] == TRUE)
        { 
            // include PEAR DB class
            include_once( SF_BASE_DIR . 'modules/common/PEAR/DB.php');        
        
            $dsn = array('phptype'  => $this->B->sys['db']['dbtype'],
                         'username' => $this->B->sys['db']['user'],
                         'password' => $this->B->sys['db']['passwd'],
                         'hostspec' => $this->B->sys['db']['host'],
                         'database' => $this->B->sys['db']['name']);

            $this->B->dboptions = array('debug'       => 0,
                                'portability' => DB_PORTABILITY_NONE);

            $this->B->db =& DB::connect($dsn, $this->B->dboptions, TRUE);
            if (DB::isError($db)) 
            {
                  trigger_error( 'Cannot connect to the database: '.__FILE__.' '.__LINE__, E_USER_ERROR  );
            }
      }
      else
      {
          exit;
      }    
  }
}

// get os related separator to set include path
if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    $tmp_separator = ';';
else
    $tmp_separator = ':';
    
// set include path to the PEAR packages
ini_set( 'include_path', SF_BASE_DIR . 'modules/common/PEAR' . $tmp_separator . ini_get('include_path') );


$B = new workaround();
$connect = new connect();


// the user class
include_once (SF_BASE_DIR . 'modules/earchive/includes/class.earchive.php');

// the PEAR IMAP class
include_once (SF_BASE_DIR . 'modules/common/PEAR/MAIL/IMAP.php');

// Set up class, initiate a mailbox connection
$msg =& new Mail_IMAP();

//User Class instance
$earchive = & new earchive;


include_once(SF_BASE_DIR.'modules/common/includes/class.commonUtil.php');

// word indexer class
include_once(SF_BASE_DIR.'modules/common/includes/class.sfWordIndexer.php');
$word_indexer = & new word_indexer();

// earchive util class
include_once(SF_BASE_DIR.'modules/earchive/includes/class.util.php');

$e_util = & new earchiveUtil;

// get email accounts
$lists = $earchive->get_lists( array('lid','emailserver','folder'), 'status>1' );

if(count($lists) > 0)
{
    // loop through the email accounts
    foreach ($lists as $account)
    {
        if (!$msg->connect($account['emailserver']))
        {
            $_error  = $msg->alerts()."\n";
            $_error .= $msg->errors();
            trigger_error("Unable to build a connection to: ".$account['emailserver']."\n\n".$_error, E_USER_ERROR);
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
                // Parse inline/attachment information specific to this part id
                //
                // See member variables begining with in or attach for
                // available information
                $msg->getParts($mid, 0, false, array('retrieve_all' => TRUE));
    
                // Parse header information
                $msg->getHeaders($mid );

                if (!isset($msg->header[$mid]['subject']) || empty($msg->header[$mid]['subject']))
                {
                    $msg->header[$mid]['subject'] = "no subject provided";
                }
                
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
                    $from = $e_util->decodeEmailHeader($from);
                }
                else
                {
                    // get from address
                    $from = $e_util->decodeEmailHeader($msg->header[$mid]['fromaddress']).' '.$msg->header[$mid]['senderaddress'];
                    $from = str_replace("<","&lt;",$from);
                    $from = str_replace(">","&gt;",$from);
                }
                    
                if(empty($from))
                {
                    $from = "&lt; no sender &gt;";
                }
                    
                // decode subject string                
                $subject =$e_util->decodeEmailHeader($msg->header[$mid]['subject']);
                
                $subject = str_replace("<","&lt;",$subject);
                $subject = str_replace(">","&gt;",$subject);               
                  
                $data['lid']      = $account['lid'];
                $data['subject']  = $B->db->quoteSmart($subject);
                $data['sender']   = $B->db->quoteSmart($e_util->html_activate_links($from));
                $data['mdate']    = $B->db->quoteSmart(date('Y-m-d H:i:s', $msg->header[$mid]['udate']));

                $_mbody = '';

                // Show inline parts first
                if (isset($msg->msg[$mid]['in']['pid']) && count($msg->msg[$mid]['in']['pid']) > 0)
                {
        
                    $_mbody = '';
                    foreach($msg->msg[$mid]['in']['pid'] as $i => $inid)
                    {
                    
                        $body = $msg->getBody( $mid, $msg->msg[$mid]['in']['pid'][$i]  );

                        if ($body['ftype'] == 'text/html')
                        {
                            $data['body'] = $B->db->quoteSmart($body['message']);
                        }
                        elseif ($body['ftype'] == 'text/plain')
                        {
                            $mess = str_replace("<","&lt;",$body['message']);
                            $mess = str_replace(">","&gt;",$mess);
                            $data['body'] = $B->db->quoteSmart(nl2br($e_util->html_activate_links($mess)));
                        }
                        else
                        {
                            $data['body'] = '';
                        }
        
                        if($data['body'] == 'NULL')
                        {
                           $b = '';
                           $data['body'] = $B->db->quoteSmart($b);
                        }
                        $_mbody .= $data['body'];
                    }
                    $data['body'] = $_mbody;
                }
                else
                {
                    $body = $msg->getBody( $mid );

                    if ($body['ftype'] == 'text/html')
                    {
                        $data['body'] = $B->db->quoteSmart($body['message']);
                    }
                    elseif ($body['ftype'] == 'text/plain')
                    {
                        $mess = str_replace("<","&lt;",$body['message']);
                        $mess = str_replace(">","&gt;",$mess);
                        $data['body'] = $B->db->quoteSmart(nl2br($e_util->html_activate_links($mess)));
                    }
                    else
                    {
                        $data['body'] = '';
                    }
                    
                    if($data['body'] == 'NULL')
                    {
                       $b = '';
                       $data['body'] = $B->db->quoteSmart($b);
                    }                
                }

                $mes_folder = FALSE;
                $is_attach  = FALSE;
    
                // check if there are attachments attachments
                if (isset($msg->msg[$mid]['at']['pid']) && count($msg->msg[$mid]['at']['pid']) > 0)
                {
                    $is_attach = TRUE;
                    // get list messages attachment folder string
                    $mes_folder = commonUtil::unique_md5_str();
                    $data['folder']  = $B->db->quoteSmart($mes_folder);
                }
                else
                {
                    $data['folder']  = '0';
                }
                
                $_content = '';
                
                if(FALSE === ($message_id = $earchive->add_message( $data )))
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
                    $path = SF_BASE_DIR . 'data/earchive/'.$account['folder'].'/'. $mes_folder;
    
                    if(!@mkdir($path, SF_DIR_MODE))
                    {
                        trigger_error('Unable to create list message folder', E_USER_ERROR);
                    }  
                    
                    foreach ($msg->msg[$mid]['at']['pid'] as $i => $aid)
                    {
                       $att_data = array();
                       
                       $_file = $e_util->decodeEmailHeader($msg->msg[$mid]['at']['fname'][$i]);
                       
                       $att_data['file'] = $B->db->quoteSmart($_file);
                       $att_data['type'] = $B->db->quoteSmart($msg->msg[$mid]['at']['ftype'][$i]);
                       $att_data['size'] = $msg->msg[$mid]['at']['fsize'][$i];
                       
                       $body = $msg->getBody( $mid, $msg->msg[$mid]['at']['pid'][$i]);

                       if($f = @fopen($path.'/'.$_file, 'wb'))
                       {
                           @fwrite($f, $body['message'], $att_data['size']);
                           @fclose($f);
                           @chmod($path.'/'.$att_data['file'], SF_FILE_MODE);
                           $earchive->add_attach( $message_id, $account['lid'], $att_data );
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
