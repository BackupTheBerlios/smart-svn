/* Import plugin specific language pack */
 tinyMCE.importPluginLanguagePack('paste','en,sv,cs,zh_cn,fr_ca');function TinyMCE_paste_getInfo(){return{longname:'Paste text/word',author:'Moxiecode Systems',authorurl:'http://tinymce.moxiecode.com',infourl:'http://tinymce.moxiecode.com/tinymce/docs/plugin_paste.html',version:tinyMCE.majorVersion+"."+tinyMCE.minorVersion};};function TinyMCE_paste_initInstance(inst){if(tinyMCE.isMSIE&&tinyMCE.getParam("paste_auto_cleanup_on_paste",false))tinyMCE.addEvent(inst.getBody(),"paste",TinyMCE_paste_handleEvent);}function TinyMCE_paste_handleEvent(e){switch(e.type){case "paste":var html=TinyMCE_paste__clipboardHTML();tinyMCE.execCommand('delete');if(html&&html.length>0)tinyMCE.execCommand('mcePasteWord',false,html);tinyMCE.cancelEvent(e);return false;}return true;}function TinyMCE_paste_getControlHTML(control_name){switch(control_name){case "pastetext":return '<a href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mcePasteText\', true);" onmousedown="return false;"><img id="{$editor_id}pastetext" src="{$pluginurl}/images/pastetext.gif" title="{$lang_paste_text_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreClass(this);" /></a>';case "pasteword":return '<a href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mcePasteWord\', true);" onmousedown="return false;"><img id="{$editor_id}pasteword" src="{$pluginurl}/images/pasteword.gif" title="{$lang_paste_word_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreClass(this);" /></a>';case "selectall":return '<a href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceSelectAll\');" onmousedown="return false;"><img id="{$editor_id}selectall" src="{$pluginurl}/images/selectall.gif" title="{$lang_selectall_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreClass(this);" /></a>';}return '';}function TinyMCE_paste_execCommand(editor_id,element,command,user_interface,value){switch(command){case "mcePasteText":if(user_interface){if(tinyMCE.isMSIE&&!tinyMCE.getParam('paste_use_dialog',false))TinyMCE_paste__insertText(clipboardData.getData("Text"),true);else{var template=new Array();template['file']='../../plugins/paste/pastetext.htm';template['width']=450;template['height']=400;var plain_text="";tinyMCE.openWindow(template,{editor_id:editor_id,plain_text:plain_text,resizable:"yes",scrollbars:"no",inline:"yes",mceDo:'insert'});}}else TinyMCE_paste__insertText(value['html'],value['linebreaks']);return true;case "mcePasteWord":if(user_interface){if(tinyMCE.isMSIE&&!tinyMCE.getParam('paste_use_dialog',false)){var html=TinyMCE_paste__clipboardHTML();if(html&&html.length>0)TinyMCE_paste__insertWordContent(html);}else{var template=new Array();template['file']='../../plugins/paste/pasteword.htm';template['width']=450;template['height']=400;var plain_text="";tinyMCE.openWindow(template,{editor_id:editor_id,plain_text:plain_text,resizable:"yes",scrollbars:"no",inline:"yes",mceDo:'insert'});}}else TinyMCE_paste__insertWordContent(value);return true;case "mceSelectAll":tinyMCE.execInstanceCommand(editor_id,'selectall');return true;}return false;}function TinyMCE_paste__insertText(content,bLinebreaks){if(content&&content.length>0){if(bLinebreaks){if(tinyMCE.getParam("plaintext_create_paragraphs",true)){content=tinyMCE.regexpReplace(content,"\r\n\r\n","</p><p>","gi");content=tinyMCE.regexpReplace(content,"\r\r","</p><p>","gi");content=tinyMCE.regexpReplace(content,"\n\n","</p><p>","gi");if((pos=content.indexOf('</p><p>'))!=-1){tinyMCE.execCommand("Delete");var node=tinyMCE.selectedInstance.getFocusElement();var breakElms=new Array();do{if(node.nodeType==1){if(node.nodeName=="TD"||node.nodeName=="BODY")break;breakElms[breakElms.length]=node;}}while(node=node.parentNode);var before="",after="</p>";before+=content.substring(0,pos);for(var i=0;i<breakElms.length;i++){before+="</"+breakElms[i].nodeName+">";after+="<"+breakElms[(breakElms.length-1)-i].nodeName+">";}before+="<p>";content=before+content.substring(pos+7)+after;}}content=tinyMCE.regexpReplace(content,"\r\n","<br />","gi");content=tinyMCE.regexpReplace(content,"\r","<br />","gi");content=tinyMCE.regexpReplace(content,"\n","<br />","gi");}tinyMCE.execCommand("mceInsertRawHTML",false,content);}}function TinyMCE_paste__insertWordContent(content){if(content&&content.length>0){var bull=String.fromCharCode(8226);var middot=String.fromCharCode(183);if(tinyMCE.getParam("paste_convert_headers_to_strong",false)){content=content.replace(new RegExp('<p class=MsoHeading.*?>(.*?)<\/p>','gi'),'<p><b>$1</b></p>');}content=content.replace(new RegExp('tab-stops: list [0-9]+.0pt">','gi'),'">'+"--list--");content=content.replace(new RegExp(bull+"(.*?)<BR>","gi"),"<p>"+middot+"$1</p>");content=content.replace(new RegExp('<SPAN style="mso-list: Ignore">','gi'),"<span>"+bull);content=content.replace(/<o:p><\/o:p>/gi,"");content=content.replace(new RegExp('<br style="page-break-before: always;.*>','gi'),'-- page break --');content=content.replace(new RegExp('<(!--)([^>]*)(--)>','g'),"");content=content.replace(/<\/?span[^>]*>/gi,"");content=content.replace(new RegExp('<(\w[^>]*) style="([^"]*)"([^>]*)','gi'),"<$1$3");content=content.replace(/<\/?font[^>]*>/gi,"");content=content.replace(/<(\w[^>]*)class=([^|>]*)([^>]*)/gi,"<$1$3");content=content.replace(/<(\w[^>]*)lang=([^|>]*)([^>]*)/gi,"<$1$3");content=content.replace(/<\\?\?xml[^>]*>/gi,"");content=content.replace(/<\/?\w+:[^>]*>/gi,"");content=content.replace(/--page break--\s*<p>&nbsp;<\/p>/gi,"");content=content.replace(/--page break--/gi,"");if(!tinyMCE.settings['force_p_newlines']){content=content.replace('','','gi');content=content.replace('</p>','<br /><br />','gi');}if(!tinyMCE.isMSIE&&!tinyMCE.settings['force_p_newlines']){content=content.replace(/<\/?p[^>]*>/gi,"");}content=content.replace(/<\/?div[^>]*>/gi,"");if(tinyMCE.getParam("paste_convert_middot_lists",true)){var div=document.createElement("div");div.innerHTML=content;var className=tinyMCE.getParam("paste_unindented_list_class","unIndentedList");while(TinyMCE_paste_convertMiddots(div,"--list--"));while(TinyMCE_paste_convertMiddots(div,middot,className));while(TinyMCE_paste_convertMiddots(div,bull));content=div.innerHTML;}if(tinyMCE.getParam("paste_convert_headers_to_strong",false)){content=content.replace(/<h[1-6]>&nbsp;<\/h[1-6]>/gi,'<p>&nbsp;&nbsp;</p>');content=content.replace(/<h[1-6]>/gi,'<p><b>');content=content.replace(/<\/h[1-6]>/gi,'</b></p>');content=content.replace(/<b>&nbsp;<\/b>/gi,'<b>&nbsp;&nbsp;</b>');content=content.replace(/^(&nbsp;)*/gi,'');}content=content.replace(/--list--/gi,"");tinyMCE.execCommand("mceInsertContent",false,content);tinyMCE.execCommand("mceCleanup");}}function TinyMCE_paste_convertMiddots(div,search,class_name){var mdot=String.fromCharCode(183);var bull=String.fromCharCode(8226);var nodes=div.getElementsByTagName("p");for(var i=0;i<nodes.length;i++){var p=nodes[i];if(p.innerHTML.indexOf(search)!=-1){var ul=document.createElement("ul");if(class_name)ul.className=class_name;var li=document.createElement("li");li.innerHTML=p.innerHTML.replace(new RegExp(''+mdot+'|'+bull+'|--list--|&nbsp;',"gi"),'');ul.appendChild(li);var np=p.nextSibling;while(np){if(np.nodeType!=1||np.innerHTML.indexOf(search)==-1)break;var cp=np.nextSibling;var li=document.createElement("li");li.innerHTML=np.innerHTML.replace(new RegExp(''+mdot+'|'+bull+'|--list--|&nbsp;',"gi"),'');np.parentNode.removeChild(np);ul.appendChild(li);np=cp;}p.parentNode.replaceChild(ul,p);return true;}}return false;}function TinyMCE_paste__clipboardHTML(){var div=document.getElementById('_TinyMCE_clipboardHTML');if(!div){var div=document.createElement('DIV');div.id='_TinyMCE_clipboardHTML';with(div.style){visibility='hidden';overflow='hidden';position='absolute';width=1;height=1;}document.body.appendChild(div);}div.innerHTML='';var rng=document.body.createTextRange();rng.moveToElementText(div);rng.execCommand('Paste');var html=div.innerHTML;div.innerHTML='';return html;}