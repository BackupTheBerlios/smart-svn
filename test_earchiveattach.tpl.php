<?php 
if (!defined('SF_SECURE_INCLUDE')) exit;  

include_once ('HTTP_Download/Download.php');

//get the requested email list name and store the result in the array $B->list 
$B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_LIST, 
             array('var' => 'list', 'lid' => (int)$_GET['lid'], 'fields' => array('folder'))); 
//get the requested message 
$B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_MESSAGE, 
             array('var'=>'msg', 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('folder')));                        
//get the attachment 
$B->M( MOD_MAILARCHIVER, 
             MAILARCHIVER_ATTACH, 
             array('var'=>'attach', 'aid'=>(int)$_GET['aid'], 'mid'=>(int)$_GET['mid'], 'lid'=>(int)$_GET['lid'], 'fields'=>array('file','size','type')));


$params = array(
      'file'                  => './data/mailarchiver/'.$B->list['folder'].'/'.$B->msg['folder'].'/'.stripslashes($B->attach['file']),
      'contenttype'           => $B->attach['type'],
      'contentdisposition'    => array(HTTP_DOWNLOAD_ATTACHMENT, stripslashes($B->attach['file'])),
    );
    
$error = HTTP_Download::staticSend($params, false);

?>