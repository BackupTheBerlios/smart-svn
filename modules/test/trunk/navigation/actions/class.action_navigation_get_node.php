<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * action_navigation_get class 
 *
 */

class action_navigation_get_node extends action
{
    /**
     * Fill up variables with navigation node title and text
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {        
        // check if a tree object exists
        if(!is_array($this->B->node))
        {
            // load navigation nodes  
            $node = array();
            include ( SF_BASE_DIR . 'data/navigation/nodes.php' ); 
            $this->B->node = & $node;     
        }   

        $_result = & $this->B->$data['result'];      

        if(SF_SECTION == 'public')
        {
            // check if cache ID exists
            if ( M( MOD_COMMON, 
                    'cache_get',
                    array('result'     => $data['result'],
                          'cacheID'    => SF_SECTION.$data['node'],
                          'cacheGroup' => 'navigation'))) 
            {
                return TRUE;
            }  
        }
        
        $_result = $this->getAllData( $data['node'] );     

        // format text
        if( $data['format'] == 'wikki' )
        {
            if(!is_object($this->B->wiki))
            {
                include_once(SF_BASE_DIR . 'modules/common/PEAR/Text/Wiki.php');
                $this->B->wiki = & new Text_Wiki();

                // wiki configuration
                //
                $options = array('http://',
                                 'https://',
                                 'ftp://',
                                 'gopher://',
                                 'news://',
                                 'mailto:',
                                 './');
                $this->B->wiki->setParseConf('Url', 'schemes', $options);                
                
                $this->B->wiki->setRenderConf('Xhtml', 'url', array('target'       => '_self',
                                                                    'css_inline'   => 'smart',
                                                                    'css_footnote' => 'smart',
                                                                    'css_descr'    => 'smart',
                                                                    'css_img'      => 'smart'));
                $this->B->wiki->setRenderConf('Xhtml', 'image', array('base'     => './',
                                                                      'css'      => 'smart',
                                                                      'css_link' => 'smart'));
                $this->B->wiki->setRenderConf('Xhtml', 'list', array( 'css_ol'    => 'smart',
                                                                      'css_ol_li' => 'smart',
                                                                      'css_ul'    => 'smart',
                                                                      'css_ul_li' => 'smart'));

                $this->B->wiki->setRenderConf('Xhtml', 'anchor', 'css', 'smart');
                $this->B->wiki->setRenderConf('Xhtml', 'horiz', 'css', 'smart');
                $this->B->wiki->setRenderConf('Xhtml', 'break', 'css', 'smart');
                $this->B->wiki->setRenderConf('Xhtml', 'strong', 'css', 'smart');
                $this->B->wiki->setRenderConf('Xhtml', 'paragraph', 'css', 'smart');
                $this->B->wiki->setRenderConf('Xhtml', 'heading', array('css_h1' => 'smart',
                                                                        'css_h2' => 'smart',
                                                                        'css_h3' => 'smart',
                                                                        'css_h4' => 'smart',
                                                                        'css_h5' => 'smart',
                                                                        'css_h6' => 'smart'));
                $this->B->wiki->setRenderConf('Xhtml', 'table', array('css_table' => 'smart',
                                                                      'css_tr'    => 'smart',
                                                                      'css_th'    => 'smart',
                                                                      'css_td'    => 'smart'));
                $this->B->wiki->setRenderConf('Xhtml', 'toc', array('css_list' => 'smart',
                                                                    'css_item' => 'smart'));

                $this->B->wiki->setRenderConf('Xhtml', 'code', array('css'      => 'smart',
                                                                     'css_code' => 'smart',
                                                                     'css_php'  => 'smart',
                                                                     'css_html' => 'smart'));
                                                                        
                
            }
            $_result['body'] = $this->B->wiki->transform($_result['body'], 'Xhtml');    
        }

        if(SF_SECTION == 'public')
        {
            // save result to cache
            M( MOD_COMMON, 
               'cache_save',
               array('result' => $_result));  
        }
    }   
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // validate $data['node']. no chars else than 0123456789 are accepted
        if( preg_match("/[^0-9-]/", $data['node']) )
        {
            $this->B->$data['error']  = 'Wrong node format';
            return FALSE;
        }   
        
        return TRUE;
    }  
    /**
     * get all node data
     *
     * @param int $node_id
     * @return array
     */     
    function & getAllData( $node_id )
    {
        // We need PEAR File to read the nodes file 
        include_once('File.php');

        $fp = & new File();
        
        // location of the node body (text)
        $node_file  = SF_BASE_DIR . 'data/navigation/'.$node_id;

        $result['body']      = $fp->readAll( $node_file );
        $result['node']      = $node_id;
        $result['title']     = $this->B->node[$node_id]['title'];
        $result['status']    = $this->B->node[$node_id]['status'];
        $result['order']     = $this->B->node[$node_id]['order'];
        $result['parent_id'] = $this->B->node[$node_id]['parent_id'];

        return $result;
    }    
}

?>