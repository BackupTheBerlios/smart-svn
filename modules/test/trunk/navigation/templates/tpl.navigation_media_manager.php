<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Little Jo - Media Manager</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?php echo SF_RELATIVE_PATH; ?>modules/common/media/main.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/JavaScript">
function deletefile(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delete_file.value="delete";
        with(f){
        submit();
        }
        }
}
</script>

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="4" cellpadding="0">
  <tr>
    <td align="center" valign="middle" bgcolor="#336699"><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Media Manager</strong></font></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
	  <?php if($B->tpl_error != FALSE): ?>
	  <tr>
	    <td>&nbsp;</td>
	    <td align="right" valign="top"><?php echo $B->tpl_error; ?></td>
	    </tr>
		<?php endif; ?>
	  <tr>
	    <td>&nbsp;</td>
	    <td align="right" valign="top">
		<form action="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=media_manager&nodecoration=1" method="post" enctype="multipart/form-data" name="form1">
		<input type="submit" name="upload" value="upload">
            &nbsp;&nbsp;&nbsp;&nbsp; <input type="file" name="file">
        </form>
        </td>
	    </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td align="right" valign="top">&nbsp;</td>
	    </tr>
      <?php foreach($B->tpl_media_files as $media):  ?>
	  <form action="<?php echo SF_CONTROLLER; ?>?admin=1&m=navigation&sec=media_manager&nodecoration=1" method="post">
	  <tr>
        <td width="8%">
          <input type="button" name="del" id="del" value="delete" onclick="deletefile(this.form, 'Delete this media file? <?php echo htmlentities($media['file']);  ?>');">
        </td> 
        <td width="92%">
		<input name="delete_file" type="hidden" value="">
		<input name="media_file" type="hidden" value="<?php echo htmlentities($media['file']);  ?>">
		<input name="media_file_path" type="text" value="<?php echo htmlentities($media['path']);  ?>" size="70" maxlength="1000" readonly="true">
		</td>
      </tr>
	  </form>
	  <?php endforeach;  ?>
    </table>
    </td>
  </tr>
</table>
</body>
</html>
