<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?> 
<?php //get all available email lists and store the result in the array $B->list ?>
<?php $B->M( MOD_EARCHIVE, 
             EARCHIVE_LISTS, 
             array('var' => 'list', 'fields' => array('lid','name','email','description','status'))); ?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="index">
<meta name="description" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>" />
<meta name="keywords" content="<?php echo str_replace("\"","'",$B->sys['option']['site_desc']); ?>" />
<title><?php echo htmlspecialchars($B->sys['option']['site_title']); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $B->sys['option']['charset']; ?>" />
<link href="media/earchive.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {color: #0000FF}
-->
</style>
</head>

<body>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="760" bgcolor="#0066FF">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="3%"><img src="media/logo.gif" width="760" height="64" /></td>
                </tr>
            </table></td>
        <td bgcolor="#0066FF">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="middle" bgcolor="#333333"><table width="760"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="21%"><img src="media/empty.gif" alt="" name="empty" width="8" height="25" id="empty" /></td>
                <td width="79%" align="right" valign="middle"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="14%" align="left" valign="middle" class="topbar">
                            <?php //Show user name if the visitor is a registered user ?>
                            <?php if($B->auth->is_user): ?>
                              Hi,&nbsp;<?php echo $B->user_lastname.'&nbsp;'.$B->user_forename; ?>
                            <?php endif; ?>
                        </td>
                        <td width="26%" align="left" valign="middle">
                            <?php //show register link if allowed  ?>
                            <?php if((!$B->auth->is_user)&&($B->sys['option']['user']['allow_register']==TRUE)): ?>
                            <a href="index.php?tpl=register" class="topbarlink">register</a>
                            <?php endif; ?>
                        </td>
                        <td width="10%" align="left" valign="top">&nbsp;</td>
                        <form name="esearch" id="esearch" method="post" action="index.php?tpl=search">
                            <td width="50%" align="right" valign="middle">
                                <input name="search" type="text" value="<?php if($_POST['search']) echo htmlspecialchars(stripslashes($_POST['search'])); else echo "search"; ?>" size="25" maxlength="128" class="searchform" /></td>
                        </form>
                    </tr>
                </table></td>
            </tr>
        </table></td>
        <td bgcolor="#333333">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" valign="top"><table width="760"  border="0" cellspacing="2" cellpadding="2">
            <tr>
                <td width="19%" align="center" valign="top" class="vline">
                <table width="100%"  border="0" cellspacing="4" cellpadding="2">
                    <tr>
                        <td align="left" valign="top" class="leftnavlinks">
                        <?php if(!isset($_GET['tpl'])): ?>
                            <strong>Home</strong>
                        <?php else: ?>
                            <a href="index.php">Home</a>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" class="leftnavlinks"><a href="admin/index.php">Admin</a></td>
                    </tr>                  
                    <tr>
                        <td align="left" valign="top" class="leftnavlinks pager">&nbsp;</td>
                    </tr>
                </table>
                    <table width="100%"  border="0" cellspacing="6" cellpadding="0">
                        <tr>
                            <td width="1%" colspan="2" align="left" valign="top"><span class="style3">E-archives</span></td>
                      </tr>
                               <?php //show available lists links ?>
                               <?php if (count($B->list) > 0): ?>
                                 <?php foreach($B->list as $list): ?>                            
                                   <tr>
                                     <td width="1%" align="left" valign="top" class="leftnavlinks">-</td>
                                     <td width="99%" align="left" valign="top" class="leftnavarchives"><a href="index.php?tpl=list&lid=<?php echo $list['lid']; ?>"><?php echo $list['name']; ?></a></td>
                                   </tr>
                                 <?php endforeach; ?>
                              <?php else: ?>
                                <tr>
                                   <td width="1%" align="left" valign="top" class="leftnavlinks">-</td>
                                   <td width="99%" align="left" valign="top" class="leftnavarchives">no archive</td>
                                </tr>                       
                              <?php endif; ?>     
                    </table>
              </td>
                <td width="81%" align="left" valign="top"><table width="100%"  border="0" cellspacing="2" cellpadding="0">
                    <tr>
                        <td align="left" valign="top"><p class="pager">E-archive ist ein Verwaltungssystem mit dem man den Inhalt von Email Konten archivieren kann, inklusiv Attachments. Die Basis die das Ganze tr&auml;gt besteht aus einem Framework (<a href="http://smart.open-publisher.net">SMART</a>). E-archive ist nichts weiter als ein Modul dieses Frameworks. Zur Zeit befindet sich das Framework wie auch das E-archive Modul noch in einer Testphase die nicht abgeschlossen ist.</p>
                          <p class="pager">Aktuelle Version 0.1.4a</p>
                          <p class="pager"><a href="http://developer.berlios.de/project/showfiles.php?group_id=1850" target="_blank">Download E-archive from the project page at Berlios </a></p>
                            <p class="pager"><a href="earchive-0.1.4a.zip">Or download from this server </a></p>
                            <p class="pager">Fragen und Anregungen k&ouml;nnen im
                              <a href="http://forum.open-publisher.net" target="_blank">Forum</a> gestellt werden.</p>
                            <h4 class="pager">Installation:</h4>
                            <p class="pager">Nachdem das komprimierte Archiv ausgepackt wurde, muss der Inhalt des Hauptordners per ftp auf den Server hochgeladen werden. Dabei kann man E-archiv auch in ein Unterordner kopieren. Danach im Webbrowser den Ordner aufrufen wo sich das Ganze befindet. Es sollte nun ein Installationsmenu erscheinen.</p>
                            <h4 class="pager">Administration</h4>
                            <p class="pager">Neben E-archiv sind weitere folgende 
                                Module installiert die man im ausklappbaren Menu ausw&auml;hlen kann:</p>
                            <ul class="pager">
                                <li><strong>USER</strong> - Benutzerverwaltung. Hier kann man neue Benutzer anlegen und verwalten. Einem Benutzer kann man 5 Rechteebenen zuweisen. <span class="style4"><br />
                                    Registered</span> - Nur Zugriff auf &ouml;ffentlichen Inhalt der f&uuml;r die normalen Seitenbesucher gesperrt ist. Kein Zugriff auf die Administration. <br />
                                    <span class="style4">Contributor</span> - f&uuml;r E-archive nicht relevant<br />
                                    <span class="style4">Author</span> - f&uuml;r E-archive nicht relevant <br />
                                    <span class="style4">Editor</span> - Kann in der Administration Emailkontenzugriffe anlegen und &auml;ndern. Ebenso Benutzer anlegen und verwalten. Er kann allerdings keine Konten l&ouml;schen.<br />
                                    <span class="style4">Administrator</span> - darf alles </li>
                                <li><strong>Options - </strong>Hier hat man Zugriff auf Systemeinstellungen. Nur f&uuml;r Administratoren.</li>
                                <li><strong>Earchive - </strong>Verwaltung der Emailkonten. </li>
                          </ul>
                            <h4 class="pager">Das E-archiv Modul</h4>
                            <p class="pager">Wenn man das EARCHIVE Modul im Auswahlmenu aktiviert erscheint eine Seite auf der alle bis jetzt angelegten Listen angezeigt werden. Auf der rechten oberen H&auml;lfte unterhalb des Seitenkopfes befindet sich ein Link &quot;<strong>add list</strong>&quot; um eine neue Liste bzw Emailkonto, auf das zugegriffen werden soll, anzulegen. Beim Anlegen eines neuen Kontos sind folgende Felder auszuf&uuml;llen:</p>
                            <ul>
                                <li class="pager">                                <strong>Name</strong> - Kurzbeschreibung des Kontos </li>
                                <li class="pager"><strong> Email Account/Server data - </strong>Hier m&uuml;ssen die Zugansdaten des Emailaccounts eingegeben werden. Das Format muss genau eingehalten werden damit es funktioniert.<br />
                                    F&uuml;r ein pop3 Konto ist das:<br />
                                    pop3://username:passwort@pop3.meinedomain.de:110/INBOX<br />
                                    <br />
                                    Noch einige Beispiele:<br />
IMAP: imap://user:pass@mail.example.com:143/INBOX<br />
*<br />
IMAP SSL: imaps://user:pass@example.com:993/INBOX<br />
*<br />
POP3: pop3://user:pass@mail.example.com:110/INBOX<br />
*<br />
POP3 SSL: pop3s://user:pass@mail.example.com:993/INBOX<br />
*<br />
NNTP: nntp://user:pass@mail.example.com:119/comp.test </li>
                                <li class="pager"><strong>Email to fetch - </strong>Die Email die die Daten sammelt</li>
                                <li class="pager"><strong>Description - </strong>Ausf&uuml;hrliche Beschreibung des Kontos </li>
                          </ul>
                            <p class="pager">Um jetzt das Konto automatisch abzufragen sollte &uuml;ber ein Cronjob die Datei <strong>/admin/modules/earchive/fetch_emails/fetch_emails.php </strong>aufgerufen werden. Man kann eigentlich soviele Konten einrichten wie man m&ouml;chte. Allerdings sollte die maximale Ausf&uuml;hrungszeit von php Skripte beachtet werden und gegebenfalls heraufsetzen. Alternativ dazu kann man das Abrufen der Emails auch manuell im Optionsmenu aktivieren. Siehe &quot;OPTION&gt;fetch&nbsp;emails&quot;. </p>
                            <h4 class="pager">Die Templates </h4>
                            <p class="pager">In den Templates wird das Layout f&uuml;r die &ouml;ffentlichen Webseiten festgelegt. Die Templates befinden sich im Hauptverzeichniss. F&uuml;r den Templatedateinamen muss folgendes Format eingehalten werden:<br />
                                <strong>xxx_yyy.tpl.php <br />
                                </strong>wobei <strong>xxx</strong> f&uuml;r das Templategruppe steht. D.h. es ist m&ouml;glich eine Webseite in mehreren Layouts herzustellen die man im Optionsmenu ausw&auml;hlen kann. <strong>yyy</strong> beschreibt den Namen der Template. Dieser muss in Links angegeben werden um diese zu laden. Z.B index.php?<strong>tpl</strong>=index index ist der Name der Template. Der Name der Templatedatei ist dann yyy_index.tpl.php. Die Templates werden indirekt von index.php eingebunden. E-archive wird mit folgenden Templates ausgeliefert:</p>
                            <ul>
                                <li class="pager"><strong>earchive_index.tpl.php</strong> - Die Eingansseite. Diese index Template muss immer vorhanden sein. Diese wird aufgerufen wenn keine <strong>tpl</strong> Variable definiert wurde.</li>
                                <li class="pager"><strong>earchive_list.tpl.php - </strong>Hier werden die Subjekte der Email Beitr&auml;ge nach Datum geordnet angezeigt.</li>
                                <li class="pager"><strong>earchive_message.tpl.php - </strong>Hier werden die einzelnen Beitr&auml;ge angezeigt.</li>
                                <li class="pager"><strong>earchive_search.tpl.php - </strong>Hier werden die Beitr&auml;ge f&uuml;r eine Suche angezeigt.</li>
                                <li class="pager"><strong>earchive_attach.tpl.php - </strong>Diese Template wird aktiviert wenn ein Emailanhang angeklickt wurde. Der Anhang wird dann an den Klienten Navigator gesendet. </li>
                                <li class="pager"><strong>earchive_login.tpl.php - </strong>Diese Template wird automatisch aufgerufen wenn ein Besuchr versucht eine Liste aufzurufen die nur f&uuml;r registrierte Benutzer zug&auml;nglich ist. Der Templatename muss immer <strong>login</strong> sein.</li>
                                <li class="pager"><strong>earchive_register.tpl.php - </strong>Falls ein Besucher nicht registriert ist kann er das hier tun falls im Optionsmenu es erlaubt wurde. </li>
                          </ul>                            
                            <p class="pager"><strong>earchive </strong>ist die Gruppe unter der die Templates zusammengefasst werden. Um eine neue Gruppe zu erstellen, k&ouml;nnen die gleichen Templatenamen genommen werden aber mit einem anderen Gruppennamen. Das Optionsmenu erkennt wenn eine weitere Gruppe existiert und stellt sie zur Auswahl. </p>                            
                            <p class="pager">In den Templates werden Funktionen benutzt um vom Earchivemodul die gew&uuml;nschten Daten zu erhalten. Bis auf weiteres siehe dazu den Aufbau der Templates. Eine genauere Beschreibung davon wird folgen. </p>
                            <p class="pager">Beim Erstellen der Templates sollte das Debugging eingeschaltet werden. Editiere dazu die Datei /admin/include/defaults.php Darin muss folgende Zeile so aussehen: <br />
                                <strong>define('SF_ERROR_HANDLE',               'SHOW|LOG');</strong> was bedeutet, dass Fehler angezeigt werden. Desweiteren werden diese auch in eine Logdatei geschrieben die sich im /admin/logs befindet. In einer sp&auml;teren Version wird das Debugging im Optionsmenu verwaltet. </p>
                            <h4 class="pager">ToDO                            </h4>
                            <p class="pager">Was unbedingt in Ordnung gebracht werden muss:</p>
                            <ul>
                                <li class="pager">L&ouml;schen bzw um&auml;ndern von Beitr&auml;gen und Attachments </li>
                                <li class="pager">Bei mehreren und intensiv benutzten Listen muss die Preformance stimmen.  In E-archive wird auf PEAR DB gesetzt um soweit wie m&ouml;glich kompatibel zu mehreren Datenbankensystemen zu sein. Das schl&auml;gt sich nat&uuml;rlich negativ auf die Performance aus und das ganz heftig bei INSERT Operationen. Die &Uuml;berlegungen gehen dahin eine eigene Abstraktionsschnittstelle zu schreiben die nur f&uuml;r einige Systeme ausgelegt ist. Oder abwarten wie die Entwicklung mit PEAR MDB2 im Hinblick auf php5 weitergeht. </li>
                          </ul>
                            <h4 class="pager">Mitarbeiter gesucht</h4>
                            <p class="pager">Wer Interesse hat an der Entwicklung bzw der Dokumentation des Projektes teilzunehmen kann sich bei mir melden. Die Vorraussetzungen sind:</p>
                            <ul>
                                <li class="pager">Gute php Kentnisse (vorzugsweise bessere als meine Eigenen) </li>
                                <li class="pager">Wenn m&ouml;glich, Erfahrungen im Umgang mit verschiedenen Datenbank-Typen </li>
                                <li class="pager">Einige Erfahrung mit dem Versionssystem Subversion </li>
                                <li class="pager">Teamarbeit </li>
                            </ul>                            <h4 class="pager">Contact</h4>
                            <p class="pager">Armand Turpel &lt;<a href="mailto:smart%20AT%20open-publisher.net">smart AT open-publisher.net</a>&gt; </p>
                            <h4 class="pager">Lizenz</h4>
                            <p class="pager">GPL</p>
                            <h3 class="pager">Technische Vorraussetzungen </h3>
                            <ul>
                                <li class="pager">PHP &gt; 4.3 </li>
                                <li class="pager">MySql &gt; 3.23.xx</li>
                                <li class="pager">IMAP php extension</li>
                                <li class="pager">XML php extension</li>
                                <li class="pager">GD php extension </li>
                            </ul></td>
                    </tr>
                </table></td>
            </tr>
        </table></td>
        <td>&nbsp;</td>
    </tr>
    <tr valign="middle" bgcolor="#0066FF">
        <td colspan="2" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="8">
            <tr>
                <td><span class="footer">E-archive - &copy; 2004&nbsp;&nbsp;&nbsp; <a href="http://e-archive.open-publisher.net" target="_blank" class="footer">e-archive.open-publisher.net</a> </span></td>
            </tr>
        </table></td>
    </tr>   
</table>
</body>
</html>
