<?php /* ### Index template. It is loaded by default if no template is defined. ### */ ?>

             
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php /* 
        --------------------------------------------------------------
        Print out system variables defined in the admin options menu. 
        --------------------------------------------------------------*/?>
<title><?php echo $B->sys['option']['site_title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />

<style type="text/css">
<!--
body,td,th {
    font-family: Verdana, Arial, Helvetica, sans-serif;
}
body {
    margin-left: 0px;
    margin-top: 0px;
    margin-right: 0px;
    margin-bottom: 0px;
}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#66CCFF"><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td><font color="#000099" size="4" face="Verdana, Arial, Helvetica, sans-serif"><strong>SMART</strong></font><font size="4" face="Verdana, Arial, Helvetica, sans-serif"> <font size="3">PHP Framework - <strong>Test</strong></font></font></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="4" cellpadding="4">
      <tr>
        <td width="15%" align="left" valign="top">
        <?php /* ### include the navigation menu view (template) ### */ ?>
        <?php include( $B->M( MOD_SYSTEM, 'GET_PUBLIC_VIEW', array('tpl' => 'navigation')) ); ?>
        </td>
        <td width="85%" align="left" valign="top">
        <?php /*----------------------------------------------------------------------------------------
                Print out the welcome message defined in the the event call at the top of this template. 
                ----------------------------------------------------------------------------------------*/ ?>
        <font face="Verdana, Arial, Helvetica, sans-serif">
        <h3><?php echo $B->tpl_welcome_string;  ?></h3></font>
        
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">To understand
            how event calls inside the templates interact with the corresponding
            action classes of each module you have to study the templates. I
            recommand you
            to
            start
            with this
            template 'default_index.tpl.php'. You will find the public templates
            at the root folder of this SMART installation.</font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">This page is produced by
              the index template (default_index.tpl.php). </font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">If no
          $_REQUEST 'tpl' var is definded this template is loaded by default.</font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Whats
            new:</strong></font></p>
        <p><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><em>Version
              0.2.2a</em></font></p>
        <ul>
          <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Rebuild
              the directory structure</font>
            <ul>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">admin
                  </font>
                <ul>
                  <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">index.php
                      (Admin Front Controller)</font></li>
                </ul>
              </li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">doc</font></li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">logs</font></li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">modules</font>
                <ul>
                      <li>xxx
                          <ul>
                            <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">actions</font></li>
                              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">filters</font></li>
                              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">templates
                                                    (admin templates)</font></li>
                              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">event_handler.php</font></li>
                              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">filter_handler.php</font></li>
                          </ul>
                      </li>
                </ul>
              </li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">smart
                  ( core framework )</font>
                <ul>
                  <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">actions</font></li>
                  <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">filters</font></li>
                  <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">includes
                      ( core files)</font></li>
                  <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">event_handler.php</font></li>
                  <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">filter_handler.php</font></li>
                </ul>
                </li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">index.php (Public Front Controller)</font></li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">default_xxx.tpl.php
                  (public template)</font></li>
              <li><font size="2" face="Verdana, Arial, Helvetica, sans-serif">default_yyy.tpl.php</font></li>
            </ul>
          </li>
          <li><font size="2">Add filter handlers. Those handlers works in the
                              same way as the event handlers but independ from those:<br />
                <br />
                $B-&gt;F ( MODULE , FILTER ACTION , ADDITIONAL ACTION DATA )<br />
                <br />
                Each module and the core system can provide such a filter handler.<br />
                Those filter handlers can be called from anywhere of the system.</font></li>
          <li><font size="2">Add prepend and append event calls to the public
                  front controller before and after the application logic. Those
                  filter events are calling some filter
                  actions.<br />
                <br />
                Example of filter actions of the test package:
                <br />
                <strong><br />
                Prepend</strong><br />
                - add headers<br />
                - detecting spam bots<br />
                - do logging<br />
                <br />
                <strong>Append
                <br />
                -</strong> trim spaces of the output<br />
                - obfuscating email adresses
              </font></li>
        </ul>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
