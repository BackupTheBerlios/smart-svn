<?php 
if (!defined('SF_SECURE_INCLUDE')) exit;  

//Get the demanded attachment and send it to the client

include_once ('HTTP/Download.php');

//get the top requested email list attachment folder 
$B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_LIST, 
             array('var' => 'list', 'lid' => (int)$_GET['lid'], 'fields' => array('folder'))); 

//get the requested message attachment folder
$B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_MESSAGE, 
             array('var'=>'msg', 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('folder')));                        

//get the attachment file name, type
$B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_ATTACH, 
             array('var'=>'attach', 'aid'=>(int)$_GET['aid'], 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('file','type')));

// send http header and content
$params = array(
      'file'                  => './data/mailarchiver/'.$B->list['folder'].'/'.$B->msg['folder'].'/'.stripslashes($B->attach['file']),
      'contenttype'           => $B->attach['type'],
      'contentdisposition'    => array(HTTP_DOWNLOAD_ATTACHMENT, stripslashes($B->attach['file'])),
    );
    
$error = HTTP_Download::staticSend($params, false);

if (TRUE !== $error) 
{
    trigger_error($error->message." ".$params['file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
}

exit;

?>