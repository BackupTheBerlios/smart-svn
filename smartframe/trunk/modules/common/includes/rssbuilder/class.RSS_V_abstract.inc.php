<?php
require_once 'interface.RSS.inc.php';
require_once 'class.RSSBase.inc.php';

abstract class RSS_V_abstract extends RSSBase implements RSS {

  protected $rssdata;
  protected $xml;
  protected $filename;

  function __construct(RSSBuilder &$rssdata) {
    parent::__construct();
    $this->rssdata =& $rssdata;
  } // end constructor

  protected function &getRSSData() {
    return $this->rssdata;
  } // end function

  protected function generateXML() {
    $this->xml = new DomDocument('1.0', $this->rssdata->getEncoding());
    $this->xml->appendChild($this->xml->createComment(''));
  } // end function

  public function outputRSS($output = TRUE) {
    if (!isset($this->xml)) {
      $this->generateXML();
    } // end if
    header('content-type: text/xml;charset=' . $this->rssdata->getEncoding() . " \r\n");
    header('Content-Disposition: inline; filename=' . $this->rssdata->getFilename());
    echo $this->xml->saveXML();
  } // end function

  public function saveRSS($path = '') {
    if (!isset($this->xml)) {
      $this->generateXML();
    } // end if
    $this->xml->save($path . $this->rssdata->getFilename());
    return (string) $path . $this->rssdata->getFilename();
  } // end function

  public function getRSSOutput() {
    if (!isset($this->xml)) {
      $this->generateXML();
    } // end if
    return $this->xml->saveXML();
  } // function
} // end class
?>