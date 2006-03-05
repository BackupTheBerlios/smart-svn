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
 * ActionArticleBBParseComment class 
 * USAGE:
 *
 * $model->action('article','parseComments',
                  array('content' => & (string)));
 *
 */

// needed for error checking
require_once SMART_BASE_DIR . 'smart/includes/PEAR/PEAR.php';
// base class
require_once SMART_BASE_DIR . 'modules/common/includes/PEAR/Text/BBCodeParser.php';

class ActionArticlePhpBBParseComment extends SmartAction
{
    /**
     * phpBB parser
     *
     * @param array $data
     * @return bool true or false on error
     */
    public function perform( $data = FALSE )
    {
        /* get options from the ini file */
        // $config = parse_ini_file('BBCodeParser.ini', true);
        $config = parse_ini_file(SMART_BASE_DIR . 'modules/common/includes/PEAR/Text/BBCodeParser_V2.ini', true);
        $options = &PEAR::getStaticProperty('HTML_BBCodeParser', '_options');
        $options = $config['HTML_BBCodeParser'];
        unset($options);

        if( !isset($this->model->phpBBParser) || !is_object($this->model->phpBBParser) )
        {
            $this->model->phpBBParser = new HTML_BBCodeParser(SMART_BASE_DIR . 'modules/common/includes/PEAR/Text/BBCodeParser_V2.ini');
        }

        $this->model->phpBBParser->setText( $data['content'] );
        $this->model->phpBBParser->parse();
        $data['content'] = $this->model->phpBBParser->getParsed();
    }     
}

?>
