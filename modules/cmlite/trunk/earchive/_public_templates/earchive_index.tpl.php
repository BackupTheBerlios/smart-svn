<?php /* index Template. See also /view/class.view_index.php */ ?>

<?php /* Only allow calling this template from whithin the application */ ?>
<?php if (!defined('SF_SECURE_INCLUDE')) exit; ?> 

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
                      <td align="left" valign="top" class="leftnavlinks"><a href="index.php?admin=1">Admin</a></td>
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
                               <?php if (count($B->tpl_list) > 0): ?>
                                 <?php foreach($B->tpl_list as $list): ?>                            
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
                        <td align="left" valign="top"><p class="pager">E-archive
                            can automatically archive email messages (+ attachments)
                            and make those accessible through a public web page.
                            It is build upon the framework <a href="http://smart.open-publisher.net">SMART</a>.
                            E-archive is a module of this framework.
                            It is still in a test phase. So there is no garuantee
                            that it is bug free.</p>
                          <p class="pager">Current Version 0.1.4a</p>
                          <p class="pager"><a href="http://developer.berlios.de/project/showfiles.php?group_id=1850" target="_blank">Download E-archive from the project page at Berlios </a></p>
                            <p class="pager"><a href="earchive-0.1.4a.zip">Or download from this server </a></p>
                            <p class="pager">For questions and suggestions please
                              use this <a href="http://forum.open-publisher.net" target="_blank">Forum</a>.</p>
                            <h4 class="pager">Installation:</h4>
                            <p class="pager">Transfer the unpacked archive to
                              your web server. You can even install E-archive
                              in a subdirectory. Point your navigator to this
                              directory and you should see an install menu. Follow
                              the instructions.</p>
                            <h4 class="pager">Administration</h4>
                            <p class="pager">The following modules are installed:</p>
                            <ul class="pager">
                                <li><strong>USER</strong> - User management.
                                  You can assign one of 5 right levels to a user
                                  account. <span class="style4"><br />
                                    Registered</span> - Access to public content
                                    to which visitors must be registered.<br />
                                    <span class="style4">Contributor</span> -
                                    Not relevant for
                                    E-archive<br />
                                    <span class="style4">Author</span> - Not
                                    relevant for E-archive<br />
                                    <span class="style4">Editor</span> - Can
                                    add, modify email accounts to which e-archive
                                    has access. Can add, modify user accounts.<br />
                                    <span class="style4">Administrator</span> -
                                    Can do every thing</li>
                                <li><strong>Options - </strong>Modify system
                                  options. Only for administrators</li>
                                <li><strong>Earchive - </strong>Administration
                                  of email boxes to which this modul has access. </li>
                          </ul>
                            <h4 class="pager">The E-archiv Module</h4>
                            <p class="pager">If you activate this module in the
                              top right select menu of the administration you
                              will see a page with all added email accounts to
                              which E-archive has access. Here you can add, edit,
                              delete or modify  email accounts and
                              archived messages. If you add a new account
                              you
                              have
                              to
                              fill out the following fields:</p>
                            <ul>
                                <li class="pager">                                <strong>Name</strong> -
                                  Short description of the account</li>
                                <li class="pager"><strong> Email Account/Server
                                    data - </strong>Here you must enter the access
                                    data to connect to an existing email account.
                                    The
                                    format is straightforward. If you make mistakes
                                    here it wont work.<br />
                                    <br />
                                    For a pop3 account:<br />
                                    pop3://username:password@pop3.mydomain.com:110/INBOX<br />
                                    <br />
                                    More examples:<br />
IMAP: imap://username:password@mail.example.com:143/INBOX<br />
*<br />
IMAP SSL: imaps://username:password@example.com:993/INBOX<br />
*<br />
POP3: pop3://username:password@mail.example.com:110/INBOX<br />
*<br />
POP3 SSL: pop3s://username:password@mail.example.com:993/INBOX<br />
*<br />
NNTP: nntp://username:password@mail.example.com:119/comp.test </li>
                                <li class="pager"><strong>Email to fetch - </strong>The
                                email address &quot;foo@mydomain.com&quot;</li>
                                <li class="pager"><strong>Description - </strong>Detailed
                                description</li>
                          </ul>
                            <p class="pager">There are 2 ways on which E-archive
                              can connect to one or more email boxes. One is;
                              you have to execute, by the php interpreter, the
                              file <strong>/admin/modules/earchive/fetch_emails/fetch_emails.php
                              </strong>through a cronjob. The other way is to
                              activate the fetch email process manually from
                              within the options menu; &quot;OPTION&gt;fetch
                              emails&quot;. </p>
                            <h4 class="pager">The public templates </h4>
                            <p class="pager">In templates you can define the
                              layout of the public web page. You will find the
                              templates in the root folder of a E-archive installation.
                              The template file names are of the following format:<br />
                              <strong>xxx_yyy.tpl.php <br />
                              </strong>where <strong>xxx</strong> stay for the
                              template group. That means; you can define more
                              layout groups for the same web project. E-archive
                              recognize if there are more groups and you can
                              switch between them in the OPTION menu of the administration.
                              <strong>yyy</strong> stay for the template name.
                              You have to define this name in a url request if
                              you want to load a specific template. <br />
                              Example:
                              index.php?<strong>tpl</strong>=article
                                <br />
                                <strong>article</strong> is the name of the template.
                                The complete template file name is yyy_article.tpl.php.
                                index is the default template name If no template
                                name is defined.<br />
                              E-archive is delivered with the following templates:</p>
                            <ul>
                                <li class="pager"><strong>earchive_index.tpl.php</strong> -
                                  Layout of the main entry page. This index template
                                  is loaded by default and must be present.</li>
                                <li class="pager"><strong>earchive_list.tpl.php
                                    - </strong>This layout show  the
                                    subjects of the archived emails, sorted
                                    by date.</li>
                                <li class="pager"><strong>earchive_message.tpl.php
                                    - </strong>The layout to show a single whole
                                    email message + attachements.</li>
                                <li class="pager"><strong>earchive_search.tpl.php
                                    - </strong>Layout of a search result.</li>
                                <li class="pager"><strong>earchive_attach.tpl.php
                                    - </strong>This isnt a really layout but
                                    a template which sends http headers to the
                                    visitor navigator to download a specific
                                    attachement.</li>
                                <li class="pager"><strong>earchive_login.tpl.php
                                    - </strong>This layout becomes active if
                                    a visitor will access web content, which
                                    is reserved for registered users. The template
                                    name must be always <strong>login</strong>.</li>
                                <li class="pager"><strong>earchive_register.tpl.php
                                    - </strong>Here a visitor can register if
                                    this option is enabled.</li>
                          </ul>                            
                            <p class="pager"><strong>earchive </strong>is the
                              group under wich the templates are grouped. To
                              create a new layout group you can copy the same
                              templates, change the groupname and save those
                              templates in the smae public root folder. You can
                              switch to this new template group in the OPTION
                              menu.</p>                            
                            <h4 class="pager">ToDO                            </h4>
                            <ul>
                                <li class="pager">Add a template if a visitor
                                  forgot account data.</li>
                                <li class="pager">Switch to PEAR MDB2 if there
                                  is a stable release.</li>
                          </ul>
                            <h4 class="pager">Get involved</h4>
                            <p class="pager">Please contact me if you have interests
                              to get involved in this project. Requirements:</p>
                            <ul>
                                <li class="pager"> PHP knowledge.</li>
                                <li class="pager">Experience with different database
                                  types would be great.</li>
                                <li class="pager">Experience with           
                                Subversion </li>
                                <li class="pager">Teamwork</li>
                            </ul>                            
                            <h4 class="pager">Contact</h4>
                            <p class="pager">Armand Turpel &lt;<a href="mailto:smart%20AT%20open-publisher.net">smart AT open-publisher.net</a>&gt; </p>
                            <h4 class="pager">Lizenz</h4>
                            <p class="pager">GPL</p>
                            <h3 class="pager">Technical requirements</h3>
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
