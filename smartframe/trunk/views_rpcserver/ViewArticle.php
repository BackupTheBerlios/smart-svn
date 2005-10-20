<?php
// ----------------------------------------------------------------------
// Smart3 PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < framework@smart3.org >
// http://www.smart3.org/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * ViewArticle class
 *
 */

class ViewArticle extends SmartXmlRpcView
{  
    private $articlelatestPublished_sig = array();
    private $articlelatestModified_sig  = array();
    
    private $articlelatestPublished_doc = array();
    private $articlelatestModified_doc  = array();    

    /**
     * verify user and password.
     * @return bool
     */
    private function rpcAuth( &$m )
    {
        $user   = $m->getParam(0);
        $user   = $user->scalarval();
        $passwd = $m->getParam(1);
        $passwd = $passwd->scalarval();
        
        
        return $this->model->action( 'user','checkLogin',
                                      array('login'  => (string)$user,
                                            'passwd' => (string)$passwd));
    }
    
    
    /**
     * Execute this view
     */
    public function perform()
    {
        $this->articlelatestPublished_sig = array(array($GLOBALS['xmlrpcString'],$GLOBALS['xmlrpcString'],$GLOBALS['xmlrpcString'],$GLOBALS['xmlrpcInt']));
        $this->articlelatestModified_sig  = array(array($GLOBALS['xmlrpcString'],$GLOBALS['xmlrpcString'],$GLOBALS['xmlrpcString'],$GLOBALS['xmlrpcInt']));
        
        $this->articlelatestPublished_doc = 'Get latest x published articles';
        $this->articlelatestModified_doc  = 'Get latest x modifieded articles';        
        
        $s = new xmlrpc_server(
                  array(
                      "article.latestPublished" =>
                        array("function"  => array(&$this,'latestPublished'),
                              "signature" => $this->articlelatestPublished_sig,
                              "docstring" => $this->articlelatestPublished_doc),

                      "article.latestModified" =>
                        array("function"  => array(&$this,'latestModified'),
                              "signature" => $this->articlelatestModified_sig,
                              "docstring" => $this->articlelatestModified_doc) ));
                          
    }
    
    public function latestPublished( &$m )
    {
        if(!$this->rpcAuth( &$m ))
        {
            return new xmlrpcresp( new xmlrpcval(FALSE, 'boolean') );
        }
        return $this->latestArticles( $m, 'pubdate' );

    }
    
    public function latestModified( &$m )
    {
        if(!$this->rpcAuth( &$m ))
        {
            return new xmlrpcresp( new xmlrpcval(FALSE, 'boolean') );
        }
        return $this->latestArticles( $m, 'modifydate' );
    } 
    
    private function latestArticles( &$m, $field )
    {
        $latest = array();
        
        $numArticles = $m->getParam(2);
        $numArticles = $numArticles->scalarval();
        if( $numArticles < 2 )
        {
            $numArticles = 2;
        }

        // get last published/modified articles                                                   
        $this->model->action('article','select',
                             array('result'  => & $latest, 
                                   'limit'   => array('perPage' => $numArticles,
                                                      'numPage' => 1),  
                                   'order'   => array($field, 'desc'),
                                   'status'  => array('=', 4),
                                   'fields'  => array('id_article','title',
                                                      'overtitle','subtitle',
                                                      'description',$field) ));
                               
        return new xmlrpcresp( new xmlrpcval(serialize($latest), 'base64') );
    }       
}

?>