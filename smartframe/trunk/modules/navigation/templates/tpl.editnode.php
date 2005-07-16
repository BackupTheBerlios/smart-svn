<?php if($tpl['format']==2): ?>
<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="./modules/common/media/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript" src="./modules/navigation/media/tiny_mce_init.js"></script>
<!-- /tinyMCE -->
<?php elseif($tpl['format']==1): ?>
<!-- PEAR text_wikki -->
<script language="javascript" type="text/javascript" src="./modules/common/media/textarea.js"></script>
<script language="javascript" type="text/javascript" src="./modules/navigation/media/text_wikki_func.js"></script>
<!-- /PEAR text_wikki -->
<?php endif; ?>
<script language="JavaScript" type="text/JavaScript">
function deletenode(f, mes)
{
      check = confirm(mes);
        if(check == true)
        {
            f.delete_node.value="1";
        with(f){
        submit();
        }
        }
}
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<form name="edituser" method="post" action="<?php echo SMART_CONTROLLER; ?>?mod=navigation&view=editnode">
<input name="id_node" type="hidden" value="<?php echo $tpl['node']['id_node']; ?>">
<input name="modifynodedata" type="hidden" value="true">
<input name="id_parent" type="hidden" value="<?php echo $tpl['node']['id_parent']; ?>">
<input name="delete_node" type="hidden" value="0">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td colspan="2" class="moduleheader2">Edit Node ID: <?php echo $tpl['node']['id_node']; ?></td>
    </tr>
  <tr>
    <td width="80%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if($tpl['error'] != FALSE): ?>
      <tr>
        <td height="25" align="left" valign="top" class="itemerror"><?php echo $tpl['error']; ?></td>
      </tr>
      <?php endif; ?>
      <tr>
        <td align="left" valign="top" >
	       <div class="font12 indent5">
	          <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation">Top</a>
	          <?php foreach($tpl['branch'] as $node): ?>
	           / <a href="<?php echo SMART_CONTROLLER; ?>?mod=navigation&id_node=<?php echo $node['id_node']; ?>"><?php echo $node['title']; ?></a>
	          <?php endforeach; ?></div>		
		</td>
        </tr>      
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="14%" align="left" valign="top" class="font10bold">Status </td>
              <td width="86%" align="left" valign="top" class="font10bold">Parent Node</td>
            </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                <option value="2" <?php if($tpl['node']['status'] == 2) echo 'selected="selected"'; ?>>active</option>
                <option value="1" <?php if($tpl['node']['status'] == 1) echo 'selected="selected"'; ?>>inactive</option>
              </select></td>
              <td align="left" valign="top"><select name="node_id_parent" size="1" id="node_id_parent" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $tpl['node']['id_parent'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
              </select></td>
            </tr>
          </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font10bold">Title</td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="title" type="text" id="title" size="90" maxlength="1024" value="<?php echo $tpl['node']['title']; ?>"></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Short Description</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><textarea name="short_text" cols="90" rows="3" id="short_text"><?php echo $tpl['node']['short_text']; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">Body</td>
      </tr>
      <tr>
        <td align="left" valign="top"><textarea name="body" cols="90" rows="15" id="body"><?php echo $tpl['node']['body']; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right"><input name="delete" type="button" id="delete" value="Delete this node" onclick="deletenode(this.form, 'Delete this node?');">
          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
      </tr>
      <tr>
        <td align="left" valign="top">Use text format:
          <input type="hidden" name="switchformat" value="0">
          <input type="radio" name="format" value="2" <?php if($tpl['format']==2) echo "checked"; ?> onclick="switch_format(document.forms['edituser'])">
Wysiwyg &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="format" value="1" <?php if($tpl['format']==1) echo "checked"; ?> onclick="switch_format(document.forms['edituser'])">
Wikki </td>
      </tr>
      <tr>
        <td align="left" valign="top"><hr>
          <table width="200" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Files</td>
            </tr>
            <tr>
              <td align="center" valign="top">
                <input type="file" name="ufile" id="ufile" size="10" class="fileform">
                <input name="uploadfile" type="hidden" value="">
                <input name="updatef" type="button" id="updatef" value="Submit" onclick="uploadufile(this.form);">
              </td>
            </tr>
            <tr>
              <td height="28" align="left" valign="top">
                <input name="fileID2del" type="hidden" value="">
                <input name="fileIDmoveUp" type="hidden" value="">
                <input name="fileIDmoveDown" type="hidden" value="">
                <?php foreach($tpl['user']['file'] as $file): ?>
                <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td colspan="2" class="font12bold"><?php echo $file['file']; ?></td>
                        </tr>
                        <tr>
                          <td align="right" valign="top"><a href="javascript:insertFile('<?php echo $tpl['user']['media_folder']; ?>','<?php echo $file['file']; ?>','<?php echo $file['id_file']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_file','','modules/common/media/pics/rewindover.png',0)"><img name="Insert<?php echo $file['id_file']; ?>" src="modules/common/media/pics/rewind.png" title="Insert <?php echo $file['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0"></a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletefile(document.forms['edituser'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_file','','modules/common/media/pics/deleteover.png',0)"> <img name="File<?php echo $file['id_file']; ?>" src="modules/common/media/pics/delete.png" title="Delete <?php echo $file['file']; ?>" alt="Delete <?php echo $file['file']; ?>" width="30" height="29" border="0"></a> </td>
                          <td align="left" valign="top"> <a href="javascript:movefileup(document.forms['edituser'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_file','','modules/common/media/pics/upover.png',0)"><img src="./modules/common/media/pics/up.png" title="Move <?php echo $file['file']; ?> up" alt="Move <?php echo $file['file']; ?> up" name="up<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a> <a href="javascript:movefiledown(document.forms['edituser'], <?php echo $file['id_file']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_file','','modules/common/media/pics/downover.png',0)"><img src="./modules/common/media/pics/down.png" title="Move <?php echo $file['file']; ?> down" alt="Move <?php echo $file['file']; ?> down" name="down<?php echo $file['id_file']; ?>" width="21" height="21" border="0" align="right"></a></td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <input name="fid[]" type="hidden" value="<?php echo $file['id_file']; ?>">
                    <td align="center" valign="top"> <a href="javascript:insertFileDesc('<?php echo $file['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_file','','modules/common/media/pics/rewindsover.png',0)"> <img name="Insertfdesc<?php echo $file['id_file']; ?>" src="modules/common/media/pics/rewinds.png" title="Insert <?php echo $file['file']; ?> description in cursor text position" alt="Insert <?php echo $file['file']; ?> description in cursor text position" width="21" height="21" border="0"></a>
                        <textarea name="filedesc[]" cols="20" rows="3" class="font10" title="Picture <?php echo $file['file']; ?> description"><?php echo stripslashes($file['description']); ?></textarea>
                    </td>
                  </tr>
                </table>
                <hr>
                <?php endforeach; ?>
              </td>
            </tr>
          </table></td>
      </tr>
    </table>
    </td>
    <td width="20%" align="left" valign="top" class="font10bold"><table width="200" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Logo Picture</td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <?php if(empty($tpl['node']['logo'])): ?>
          <input type="file" name="logo" id="logo" size="10" class="fileform">
          <input name="uploadlogo" type="hidden" value="">
          <input name="update" type="button" id="update" value="Submit" onclick="uploadlogofile(this.form);">
          <?php else: ?>
          <img name="userlogo" src="<?php echo SMART_RELATIVE_PATH.'data/navigation/'.$tpl['node']['media_folder'].'/'.$tpl['user']['logo']; ?>" alt="Node Logo"> <br>
          <input name="deletelogo" type="hidden" value="">
          <input type="button" name="eraselogo" value="delete" onclick="dellogo(this.form, 'Delete node logo Picture?');">
          <?php endif; ?>
        </td>
      </tr>
    </table>
      
      <table width="200" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td align="center" valign="middle" bgcolor="#6699FF" class="font10bold">Pictures</td>
        </tr>
        <tr>
          <td align="center" valign="top">
            <input type="file" name="picture" id="picture" size="10" class="fileform">
            <input name="uploadpicture" type="hidden" value="">
            <input name="updatep" type="button" id="updatep" value="Submit" onclick="uploadpicfile(this.form);">
          </td>
        </tr>
        <tr>
          <td height="28" align="left" valign="top">
            <input name="imageID2del" type="hidden" value="">
            <input name="imageIDmoveUp" type="hidden" value="">
            <input name="imageIDmoveDown" type="hidden" value="">
            <?php foreach($tpl['node']['thumb'] as $thumb): ?>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="right" valign="top"><img src="./data/navigation/<?php echo $tpl['node']['media_folder']; ?>/thumb/<?php echo $thumb['file']; ?>" alt="<?php echo $thumb['description']; ?>" title="<?php echo $thumb['file']; ?>" name="<?php echo $thumb['file']; ?>" width="120"> </td>
                      <td align="left" valign="top"> <a href="javascript:moveup(document.forms['editnode'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','modules/common/media/pics/upover.png',0)"><img src="./modules/common/media/pics/up.png" title="Move <?php echo $thumb['file']; ?> up" alt="Move <?php echo $thumb['file']; ?> up" name="up<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a><br/>
                          <br/>
                        <a href="javascript:movedown(document.forms['edituser'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','modules/common/media/pics/downover.png',0)"><img src="./modules/common/media/pics/down.png" title="Move <?php echo $thumb['file']; ?> down" alt="Move <?php echo $thumb['file']; ?> down" name="down<?php echo $thumb['id_pic']; ?>" width="21" height="21" border="0" align="right"></a></td>
                    </tr>
                    <tr>
                      <td align="right" valign="top">
					  <a href="javascript:insertImage('<?php echo $tpl['node']['media_folder']; ?>','<?php echo $thumb['file']; ?>','<?php echo $thumb['id_pic']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','modules/common/media/pics/rewindover.png',0)">
					  <img name="Insert<?php echo $thumb['id_pic']; ?>" src="modules/common/media/pics/rewind.png" title="Insert <?php echo $thumb['file']; ?> in cursor text position" alt="Insert this picture in texte" width="30" height="29" border="0">
					  </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:deletepic(document.forms['edituser'], <?php echo $thumb['id_pic']; ?>)" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','modules/common/media/pics/deleteover.png',0)">
					  <img id="Image6" name="Image<?php echo $thumb['id_pic']; ?>" src="modules/common/media/pics/delete.png" title="Delete <?php echo $thumb['file']; ?>" alt="Delete <?php echo $thumb['file']; ?>" width="30" height="29" border="0"></a>
			          </td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <input name="pid[]" type="hidden" value="<?php echo $thumb['id_pic']; ?>">
                <td align="center" valign="top"> <a href="javascript:insertImgDesc('<?php echo $thumb['description']; ?>');" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('id_pic','','modules/common/media/pics/rewindsover.png',0)"><img name="Insertpdesc<?php echo $thumb['id_pic']; ?>" src="modules/common/media/pics/rewinds.png" title="Insert <?php echo $thumb['file']; ?> description in cursor text position" alt="Insert <?php echo $thumb['file']; ?> description in cursor text position" width="21" height="21" border="0"></a>
                    <textarea name="picdesc[]" cols="20" rows="3" class="font10" title="Picture <?php echo $thumb['file']; ?> description"><?php echo stripslashes($thumb['description']); ?></textarea>
                </td>
              </tr>
            </table>
            <hr>
            <?php endforeach; ?>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</form>