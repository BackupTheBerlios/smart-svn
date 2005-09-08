<script language="JavaScript" type="text/JavaScript">
// unlock a node and forward to the node with id x. use this for links
function gotonode(f,x){
        f.gotonode.value=x;
        with(f){
        submit();
        }
}
var flag = 0;
function enablechangedate(f)
{
	if(flag==0)
	{
		flag = 1;
	    f.changedate_year.disabled="";
		f.changedate_month.disabled="";
		f.changedate_day.disabled="";
		f.changedate_hour.disabled="";
		f.changedate_minute.disabled="";
		f.changedatebutton.value="disable";
	    f.ch1.disabled="";
		f.ch2.disabled="";
		f.ch3.disabled="";
	}
	else
	{
	    flag = 0;
	    f.changedate_year.disabled="disabled";
		f.changedate_month.disabled="disabled";
		f.changedate_day.disabled="disabled";
		f.changedate_hour.disabled="disabled";
		f.changedate_minute.disabled="disabled";
		f.ch1.disabled="disabled";
		f.ch2.disabled="disabled";
		f.ch3.disabled="disabled";
		f.changedatebutton.value="enable";	
	}
}
function cancel_edit(f)
{
        f.canceledit.value="1";
        with(f){
        submit();
        }
}
</script>
<style type="text/css">
<!--
.optsel {
  background-color: #CCCCCC;
}
.jj {
  font-family: "Courier New", Courier, mono;
  padding-top: 0px;
  padding-right: 0px;
  padding-bottom: 5px;
  padding-left: 0px;
  font-size: 100%;
}
-->
</style>
<form accept-charset="<?php echo $tpl['charset']; ?>" action="<?php echo SMART_CONTROLLER; ?>?mod=article&view=editArticle" method="post" enctype="multipart/form-data" name="editarticle" id="editarticle">
<input name="id_article" type="hidden" value="<?php echo $tpl['article']['id_article']; ?>">
<input name="id_node" type="hidden" value="<?php echo $tpl['id_node']; ?>">
<input name="gotonode" type="hidden" value="">
<input name="canceledit" type="hidden" id="canceledit" value="">
<input name="modifyarticledata" type="hidden" value="true">
<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td class="moduleheader2">Edit Article ID: <?php echo $tpl['article']['id_article']; ?></td>
    </tr>
  <tr>
    <td class="font16bold">
         <div class="font12 indent5">
            <a href="javascript:gotonode(document.forms['editarticle'],0);">Top</a>
            <?php foreach($tpl['branch'] as $node): ?>
             / <a href="javascript:gotonode(document.forms['editarticle'],<?php echo $node['id_node']; ?>);"><?php echo $node['title']; ?></a>
            <?php endforeach; ?>
            <?php if($tpl['id_node']!=0): ?>
               / <a href="javascript:gotonode(document.forms['editarticle'],<?php echo $tpl['node']['id_node']; ?>);"><?php echo $tpl['node']['title']; ?></a>
            <?php endif; ?>			
		 </div>  	
	</td>
  </tr>
  <tr>
    <td class="font16bold"><?php echo $tpl['article']['title']; ?><hr></td>
  </tr>
  <tr>
    <td width="94%" align="left" valign="top">      <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <?php if(count($tpl['error'])>0): ?>
      <tr>
        <td height="25" align="left" valign="top" class="itemerror">
    <?php foreach($tpl['error'] as $err): ?>
        <?php echo $err; ?><br />
      <?php endforeach; ?> 
    </td>
      </tr>
      <?php endif; ?>   
      <tr>
        <td align="left" valign="top"><table width="100%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="35%" align="left" valign="top" class="font12bold">Status </td>
              <td width="53%" align="left" valign="top" class="font12bold"><a href="<?php echo SMART_CONTROLLER; ?>?mod=article&view=modArticle&disableMainMenu=1">Modify article content</a></td>
              <td width="12%" align="right" valign="top" class="font10bold"><input type="button" name="cancel" value="cancel" onclick="cancel_edit(this.form);"></td>
              </tr>
            <tr>
              <td align="left" valign="top"><select name="status" size="1" id="status" class="treeselectbox">
                <option value="4" <?php if($tpl['article']['status'] == 4) echo 'selected="selected"'; ?>>publish</option>
                <option value="3" <?php if($tpl['article']['status'] == 3) echo 'selected="selected"'; ?>>edit</option>
                <option value="2" <?php if($tpl['article']['status'] == 2) echo 'selected="selected"'; ?>>propose</option>
                <option value="1" <?php if($tpl['article']['status'] == 1) echo 'selected="selected"'; ?>>cancel</option>
                <option value="0" <?php if($tpl['article']['status'] == 0) echo 'selected="selected"'; ?>>delete</option>
              </select></td>
              <td align="right" valign="top">&nbsp;</td>
              <td align="right" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
            </tr>
          </table></td>
      </tr>   
      <tr>
        <td align="left" valign="top" class="font12bold">Navigation Node of this Article</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold">
		    <select name="article_id_node" size="1" id="article_id_node" class="treeselectbox">
                <option value="0">Top</option>
                <?php foreach($tpl['tree'] as $val):  ?>
                <option value="<?php echo $val['id_node']; ?>" <?php if($val['id_node'] == $tpl['id_node'] ){ echo 'selected="selected"'; echo 'class="optsel"'; }?>><?php echo str_repeat('-',$val['level'] * 3); echo $val['title']; ?></option>
                <?php endforeach; ?>
			</select></td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font12bold">Publish Date</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10"><table width="300" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top" class="font10">Year</td>
            <td align="left" valign="top" class="font10">Month</td>
            <td align="left" valign="top" class="font10">Day</td>
            <td align="left" valign="top" class="font10">Hour</td>
            <td align="left" valign="top" class="font10">Minute</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="pubdate_year" type="text" size="5" maxlength="4" value="<?php echo $tpl['article']['pubdate']['year']; ?>"></td>
            <td align="left" valign="top"><select name="pubdate_month">
			  <?php for($month=1;$month<13;$month++): ?>
              <option value="<?php echo $month; ?>" <?php if($tpl['article']['pubdate']['month'] == $month) echo 'selected="selected"'; ?>><?php echo $month; ?></option>
              <?php endfor; ?>
            </select>
              </td>
            <td align="left" valign="top"><select name="pubdate_day">
			  <?php for($day=1;$day<32;$day++): ?>
              <option value="<?php echo $day; ?>" <?php if($tpl['article']['pubdate']['day'] == $day) echo 'selected="selected"'; ?>><?php echo $day; ?></option>
              <?php endfor; ?>			  			  
            </select>
              </td>
            <td align="left" valign="top"><select name="pubdate_hour">
			  <?php for($hour=0;$hour<24;$hour++): ?>
              <option value="<?php echo $hour; ?>" <?php if($tpl['article']['pubdate']['hour'] == $hour) echo 'selected="selected"'; ?>><?php echo $hour; ?></option>
              <?php endfor; ?>
            </select>
              </td>
            <td align="left" valign="top"><select name="pubdate_minute">
			  <?php for($minute=0;$minute<60;$minute++): ?>
              <option value="<?php echo $minute; ?>" <?php if($tpl['article']['pubdate']['minute'] == $minute) echo 'selected="selected"'; ?>><?php echo $minute; ?></option>
              <?php endfor; ?>
            </select>
              </td>
          </tr>
        </table></td>
      </tr>
	  <?php if($tpl['use_changedate']==1): ?>
      <tr>
        <td align="left" valign="top" class="font12bold">Change Status Date</td>
      </tr>
      <tr>
        <td align="left" valign="top" class="font10bold"><table width="372" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td width="36" align="left" valign="top" class="font10">Year</td>
            <td width="53" align="left" valign="top" class="font10">Month</td>
            <td width="53" align="left" valign="top" class="font10">Day</td>
            <td width="53" align="left" valign="top" class="font10">Hour</td>
            <td width="53" align="left" valign="top" class="font10">Minute</td>
            <td width="29" align="left" valign="top" class="font10">&nbsp;</td>
            <td width="51" align="left" valign="top" class="font10">&nbsp;</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="changedate_year" type="text" size="5" maxlength="4" value="<?php echo $tpl['article']['changedate']['year']; ?>"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
            </td>
            <td align="left" valign="top"><select name="changedate_month"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($month=1;$month<13;$month++): ?>
                <option value="<?php echo $month; ?>" <?php if($tpl['article']['changedate']['month'] == $month) echo 'selected="selected"'; ?>><?php echo $month; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="changedate_day"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($day=1;$day<32;$day++): ?>
                <option value="<?php echo $day; ?>" <?php if($tpl['article']['changedate']['day'] == $day) echo 'selected="selected"'; ?>><?php echo $day; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="changedate_hour"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($hour=0;$hour<24;$hour++): ?>
                <option value="<?php echo $hour; ?>" <?php if($tpl['article']['changedate']['hour'] == $hour) echo 'selected="selected"'; ?>><?php echo $hour; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="changedate_minute"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?>>
                <?php for($minute=0;$minute<61;$minute++): ?>
                <option value="<?php echo $minute; ?>" <?php if($tpl['article']['changedate']['minute'] == $minute) echo 'selected="selected"'; ?>><?php echo $minute; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top">&nbsp;</td>
            <td align="left" valign="top">              <input type="button" name="changedatebutton" value="enable" onclick="enablechangedate(this.form);"></td>
          </tr>
        </table>
          <table width="428" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="46" align="left" valign="top" class="font10bold">&nbsp;</td>
              <td width="66" align="left" valign="top" class="font10bold">To status:</td>
              <td width="66" align="left" valign="top" class="font10">delete
                  <input type="radio" name="changestatus" id="ch1" value="0"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==0) echo ' checked="checked"'; ?>>
              </td>
              <td width="72" align="left" valign="top" class="font10">cancel
                  <input type="radio" name="changestatus" id="ch2" value="1"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==1) echo ' checked="checked"'; ?>>
              </td>
              <td width="146" align="left" valign="top" class="font10">edit
                  <input type="radio" name="changestatus" id="ch3" value="3"<?php if($tpl['cd_disable']==TRUE) echo ' disabled="disabled"'; ?><?php if($tpl['article']['changestatus']==3) echo ' checked="checked"'; ?>>
              </td>
            </tr>
          </table></td>
      </tr>
	  <?php endif; ?>
	  <?php if($tpl['use_articledate']==1): ?>
      <tr>
        <td align="left" valign="top" class="font12bold">Original Date</td>
      </tr>
      <tr>
        <td align="left" valign="top"><table width="300" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td align="left" valign="top" class="font10">Year</td>
            <td align="left" valign="top" class="font10">Month</td>
            <td align="left" valign="top" class="font10">Day</td>
            <td align="left" valign="top" class="font10">Hour</td>
            <td align="left" valign="top" class="font10">Minute</td>
          </tr>
          <tr>
            <td align="left" valign="top"><input name="articledate_year" type="text" size="5" maxlength="4" value="<?php echo $tpl['article']['articledate']['year']; ?>">
            </td>
            <td align="left" valign="top"><select name="articledate_month">
                <?php for($month=1;$month<13;$month++): ?>
                <option value="<?php echo $month; ?>" <?php if($tpl['article']['articledate']['month'] == $month) echo 'selected="selected"'; ?>><?php echo $month; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="articledate_day">
                <?php for($day=1;$day<32;$day++): ?>
                <option value="<?php echo $day; ?>" <?php if($tpl['article']['pubdate']['day'] == $day) echo 'selected="selected"'; ?>><?php echo $day; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="articledate_hour">
                <?php for($hour=0;$hour<13;$hour++): ?>
                <option value="<?php echo $hour; ?>" <?php if($tpl['article']['pubdate']['hour'] == $hour) echo 'selected="selected"'; ?>><?php echo $hour; ?></option>
                <?php endfor; ?>
              </select>
            </td>
            <td align="left" valign="top"><select name="articledate_minute">
                <?php for($minute=0;$minute<61;$minute++): ?>
                <option value="<?php echo $minute; ?>" <?php if($tpl['article']['pubdate']['minute'] == $minute) echo 'selected="selected"'; ?>><?php echo $minute; ?></option>
                <?php endfor; ?>
              </select>
            </td>
          </tr>
        </table>
          </td>
      </tr>
	  <?php endif; ?>
      <tr>
        <td align="left" valign="top" class="font9"> 
          <div align="right">          </div></td>
      </tr>
      <tr>
        <td align="left" valign="top"><input name="finishupdate" type="submit" id="finishupdate" value="Submit"></td>
      </tr>
    </table>
    </td>
    </tr>
</table>
</form>