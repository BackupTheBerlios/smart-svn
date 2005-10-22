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
     * verify client user and password.
     * @return bool
     */
    private function rpcAuth( &$params )
    {
        $user   = $params->getParam(0);
        $user   = $user->scalarval();
        $passwd = $params->getParam(1);
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
    /**
     * get latest published articles
     *
     * @param object $params Client parameters
     */    
    public function latestPublished( &$params )
    {
        if(!$this->rpcAuth( &$params ))
        {
            return new xmlrpcresp(0, $GLOBALS['xmlrpcerruser'], 'Registered user required');
        }
        return $this->latestArticles( $params, 'pubdate' );

    }
    /**
     * get latest modifies articles
     *
     * @param object $params Client parameters
     */    
    public function latestModified( &$params )
    {
        if(!$this->rpcAuth( &$params ))
        {
            return new xmlrpcresp(0, $GLOBALS['xmlrpcerruser'], 'Registered user required');
        }
        return $this->latestArticles( $params, 'modifydate' );
    } 
    /**
     * get latest published/modifies articles
     *
     * @param object $params Client parameters
     * @param string $date_field Date field of the article to fetch
     */     
    private function latestArticles( &$params, $date_field )
    {
        $this->viewVar['latest_articles'] = array();
        
        $numArticles = $params->getParam(2);
        $numArticles = $numArticles->scalarval();
        if( $numArticles < 2 )
        {
            $numArticles = 2;
        }

        // get last published/modified articles                                                   
        $this->model->action('article','select',
                             array('result'  => & $this->viewVar['latest_articles'], 
                                   'limit'   => array('perPage' => $numArticles,
                                                      'numPage' => 1),  
                                   'order'   => array($date_field, 'desc'),
                                   'status'  => array('=', 4),
                                   'fields'  => array('id_article','title',
                                                      'overtitle','subtitle',
                                                      'description',$date_field) ));
        $this->addArray( $date_field );             
        return new xmlrpcresp( $this->val );
    }  
    /**
     * add articles content as xml_rpc struct array
     *
     * @param string $date_field Date field of the article to fetch
     */     
    private function addArray( &$date_field )
    {
        $content = array();
        $this->val = new xmlrpcval();
        
        foreach($this->viewVar['latest_articles'] as $val)
        {
            $struct = array();
            $struct['id_article']  = new xmlrpcval($val['id_article'], 'int');
            $struct['title']       = new xmlrpcval($val['title'],      'string');
            $struct['overtitle']   = new xmlrpcval($val['overtitle'],  'string');
            $struct['subtitle']    = new xmlrpcval($val['subtitle'],   'string');
            $struct['description'] = new xmlrpcval($val['description'],'string');
            $struct[$date_field]   = new xmlrpcval($val[$date_field],  'string');
            
            $content[] = new xmlrpcval($struct, 'struct');
        }
        
        $this->val->addArray($content);
    }
}

?>