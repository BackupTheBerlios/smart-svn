<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<title>Fetch latest Articles from Smart3 Website trough XML_RPC Client</title>
<body>

<?php
// include the xml_rpc client class
require_once('../smart/includes/xml_rpc/xmlrpc.inc');

// the domain name
$domain      = "127.0.0.1";
// relative path to the smart3 installation
$domainPath  = "/projects/smartframe/";
// full url path to the server and the requested view as argument (without 'http://')
$rpcServer   = "{$domainPath}rpcserver.php?view=Article";

// if registered user required, enter username and password else 
// let the variables empty
$authUser    = 'superuser';
$authPasswd  = 'a';

// the rpc methode to execute
// you have the choice to get the latest published or modified articles
$methode     = "article.latestPublished"; // 'article.latestModified' or 
                                          // 'article.latestPublished'
// number of articles to get
$numArticles = 5;

///////////////////////////////////////////////////////////////////////

// map methode related fields
$methodeField = array('article.latestModified'  => 'modifydate',
                      'article.latestPublished' => 'pubdate');

// start rpc client
$client = new xmlrpc_client("{$rpcServer}", $domain, 80);  
// set rpc methode and parameters
$msg    = new xmlrpcmsg($methode, 
                array( new xmlrpcval($authUser,    "string"),
                       new xmlrpcval($authPasswd,  "string"),
                       new xmlrpcval($numArticles, "int") ) );
 
//$client->setDebug(1);
$response = $client->send($msg);
$val      = $response->value();

if(!$response->faultCode())
{
    if(FALSE === ($result = $val->scalarval()))
    {
        die("access denied");
    }
    
    $articles = unserialize($result);
    foreach($articles as $art)
    {
        echo "<div>Date: {$art[$methodeField[$methode]]}</div>";
        
        if(!empty($art['overtitle']))
        {
            echo "<div>Overtitle: {$art['overtitle']}</div>";
        }
        
        echo "<div>Title: <a href='http://{$domain}{$domainPath}index.php?view=article&id_article={$art['id_article']}' target='_blank'>{$art['title']}</a></div>";
        
        if(!empty($art['subtitle']))
        {
            echo "<div>Subtitle: {$art['subtitle']}</div>";
        }
        
        if(!empty($art['description']))
        {
            echo "<div>Description: {$art['description']}</div>";
        }
        
        echo "<br>";
    }
}
else
{
    print "Fault: ";
    print "Code: " . htmlspecialchars($response->faultCode())
     . " Reason '" . htmlspecialchars($response->faultString()) . "'<br>";
}    
?>

</body>
</html>