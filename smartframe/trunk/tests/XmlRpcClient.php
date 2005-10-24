<?php
// set charset
@header( "Content-type: text/html; charset=utf-8" );  
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<title>Fetch latest Articles from Smart3 Website trough XML_RPC Client</title>
<body>

<?php
// include the xml_rpc client class
require_once('../smart/includes/PEAR/XML/RPC/RPC.php');

// the domain name
$domain      = "www.smart3.org";
// relative path to the smart3 installation
$domainPath  = "/";
// full url path to the server and the requested view as argument (without 'http://')
$rpcServer   = "{$domainPath}rpcserver.php?view=Article";

// if registered user required, enter username and password else 
// let the variables empty
$authUser    = 'xmlrpc';
$authPasswd  = '1234';

// the rpc methode to execute
// you have the choice to get the latest published or modified articles
$methode     = "latestModified";  // 'latestModified' or 
                                  // 'latestPublished'
// number of articles to get
$numArticles = 8;

///////////////////////////////////////////////////////////////////////

// map methode related fields
$methodeField = array('latestModified'  => 'modifydate',
                      'latestPublished' => 'pubdate');

// start rpc client
$client = new XML_RPC_Client("{$rpcServer}", $domain, 80); 
//$client->setDebug(1);

//$client->setAcceptedCompression('gzip');
//$client->setRequestCompression('gzip');

// set rpc methode and parameters
$msg    = new XML_RPC_Message($methode, 
                array( new XML_RPC_Value($authUser,    "string"),
                       new XML_RPC_Value($authPasswd,  "string"),
                       new XML_RPC_Value($numArticles, "int") ) );
 
$response = $client->send($msg);
$content  = $response->value();

if(!$response->faultCode())
{
    $max = $content->arraysize(); 
    
    for($i=0; $i<$max; $i++) 
    {
        // get element of the array
        $rec = $content->arraymem($i);

        // get the associative array value
        $article_date = $rec->structmem($methodeField[$methode]);
        $article_date = $article_date->scalarval();
        echo "<div>Date: {$article_date}</div>";
        
        $overtitle = $rec->structmem('overtitle');
        $overtitle = $overtitle->scalarval();
        if(!empty($overtitle))
        {
            echo "<div>Overtitle: {}</div>";
        }
        
        $id_article = $rec->structmem('id_article');
        $id_article = $id_article->scalarval();
        $title      = $rec->structmem('title');
        $title      = $title->scalarval();
        echo "<div>Title: <a href='http://{$domain}{$domainPath}index.php?view=article&id_article={$id_article}' target='_blank'>{$title}</a></div>";

        $subtitle = $rec->structmem('subtitle');
        $subtitle = $subtitle->scalarval();        
        if(!empty($subtitle))
        {
            echo "<div>Subtitle: {$subtitle}</div>";
        }
        
        $description = $rec->structmem('description');
        $description = $description->scalarval();           
        if(!empty($description))
        {
            echo "<div>Description: {$description}</div>";
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