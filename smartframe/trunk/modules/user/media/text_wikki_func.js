function insertFile(folder,file,id_file)
{
    smartAddText('<a href="data/user/'+folder+'/'+file+'" name="'+file+'" title="'+file+'">'+file+'</a>','',document.edituser.description);
}
function insertFileDesc(desc)
{
    smartAddText(desc,'',document.edituser.description);
}
function insertImage(folder,file,id_pic)
{
    smartAddText('<a href="?view=picture&id_pic='+id_pic+'"><img src="./data/user/'+folder+'/thumb/'+file+'" name="'+file+'" title="'+file+'" border="0" /></a>','',document.edituser.description);
}
function insertImgDesc(desc)
{
    smartAddText(desc,'',document.edituser.description);
}
