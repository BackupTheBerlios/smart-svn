  // Notice: The simple theme does not use all options some of them are limited to the advanced theme
  tinyMCE.init({
    directionality : "ltr",
    remove_script_host : false,
    relative_urls : true,
    mode : "exact",
    elements : "body",
    theme : "advanced",
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",   
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,formatselect,fontselect,fontsizeselect",	 
    theme_advanced_buttons2 : "bullist, numlist,outdent,indent,separator,undo,redo,separator,link,unlink,cleanup,code,separator,table,hr,removeformat,sub,sup,forecolor",	 
    theme_advanced_buttons3 : "", 
    plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,flash,searchreplace,print,contextmenu"
    
  });
 function insertFile(folder,title,file,id_file)
{
    tinyMCE.execCommand('mceInsertContent',0,'<a href="data/navigation/'+folder+'/'+file+'" name="'+file+'" title="'+title+'">'+title+'</a>');
}
function insertFileDesc(desc)
{
    tinyMCE.execCommand('mceInsertContent',0,desc);
}
function insertImage(controller,path,file,title,id_pic,width,height)
{
    tinyMCE.execCommand('mceInsertContent',0,'<a href="javascript:showimage(\''+controller+'?view=nodePicture&id_pic='+id_pic+'\','+width+','+height+');"><img src="'+path+file+'" id="'+file+'" title="'+title+'" border="0" /></a>');
}
function insertImgDesc(desc)
{
    tinyMCE.execCommand('mceInsertContent',0,desc);
} 