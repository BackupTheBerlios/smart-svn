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
    /**
     * verify client user and password.
     * @return bool
     */
    private function rpcAuth( &$params )
    {
        $user   = $params[0];
        $passwd = $params[1];
        
        return $this->model->action( 'user','checkLogin',
                                      array('login'  => (string)$user,
                                            'passwd' => (string)$passwd));
    }

    /**
     * Execute this view. 
     * Register callback methods and start XML-RPC server 
     */
    public function perform()
    {  
        $this->addCallback(
                'article.latestPublished',    // XML-RPC method name
                'this:latestPublished',       // methode to callback
                array('struct','string', 'string','int'), // Array specifying the method signature
                'Returns the last published article as a struct array.'  // Documentation string
               );        

        $this->addCallback(
                'article.latestModified',
                'this:latestModified',
                array('struct','string', 'string','int'),
                'Returns the last modified article as a struct array.'
               );  
               
        $this->serve();                     
    }
    /**
     * get latest published articles
     *
     * @param object $params Client parameters
     * @return array struct data
     */    
    public function latestPublished( &$params )
    {
        if(!$this->rpcAuth( &$params ))
        {
            return new IXR_Error(1000, 'Authentication fails. Registered user required.');
        }
        
        $this->latestArticles( $params, 'pubdate' );
        return $this->viewVar['latest_articles'];
    }
    /**
     * get latest modifies articles
     *
     * @param object $params Client parameters
     * @return array struct data
     */    
    public function latestModified( &$params )
    {
        if(!$this->rpcAuth( &$params ))
        {
            return new IXR_Error(1000, 'Authentication fails. Registered user required.');
        }
        
        $this->latestArticles( $params, 'modifydate' );
        return $this->viewVar['latest_articles'];
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
        
        $numArticles = $params[2];

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
    }  
}

?>