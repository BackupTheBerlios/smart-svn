<?php 
if (!defined('SF_SECURE_INCLUDE')) exit;  

//Get the demanded attachment and send it to the client

include_once ('HTTP/Download.php');

//get the top requested email list attachment folder 
$B->M( MOD_EARCHIVE, 
       EARCHIVE_LIST, 
       array('var' => 'tpl_list', 'lid' => (int)$_GET['lid'], 'fields' => array('folder'))); 

//get the requested message attachment folder
$B->M( MOD_EARCHIVE, 
       EARCHIVE_MESSAGE, 
       array('var'=>'tpl_msg', 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('folder')));                        

//get the attachment file name, type
$B->M( MOD_EARCHIVE, 
       EARCHIVE_ATTACH, 
       array('var'=>'tpl_attach', 'aid'=>(int)$_GET['aid'], 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('file','type')));

// send http header and content
$params = array(
      'file'                  => './data/earchive/'.$B->tpl_list['folder'].'/'.$B->tpl_msg['folder'].'/'.stripslashes($B->tpl_attach['file']),
      'contenttype'           => $B->tpl_attach['type'],
      'contentdisposition'    => array(HTTP_DOWNLOAD_ATTACHMENT, stripslashes($B->tpl_attach['file'])),
    );
    
$error = HTTP_Download::staticSend($params, false);

if (TRUE !== $error) 
{
    trigger_error($error->message." ".$params['file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
}

exit;

?>