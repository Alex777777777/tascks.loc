var ic_folder_c="folder-close";
var ic_folder_o="folder-open";
var ic_file="file";
var lp_curdir=0;
var rp_curdir=0;
var CurPanel="";
var UPanel="";
function CreateSubMenu(){
    tmp=CreateIcon("step-backward");
    tmp+=CreateIcon("plus-sign");
    tmp+=CreateIcon("edit");
    tmp+=CreateIcon("remove-sign");
    $("div#submenu").html(tmp);
}
function GetItems(){
    if(UPanel=="rpanel")lid=rp_curdir;
    else lid=lp_curdir;
    param={
        "id":lid
    }
    $.ajax({
        type: "POST",
        url: "?cmd=getitem",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            tsys=JSON.parse(qstr);
            lstr=tsys[0];
            tsys.splice(0,1);
            if(UPanel=="rpanel"){
                rpanel= tsys;
                UpdateP($("#rpanel"),rpanel);
                $(".rpanel .path").html(lstr);
            }else{
                lpanel= tsys;
                UpdateP($("#lpanel"),lpanel);
                $(".lpanel .path").html(lstr);
            };
        }
    })
}
function TbIcon(par){
    lpar=par.trim();
    ppar=ic_file;
    if(lpar=="Y")ppar=ic_folder_c;
    return(CreateIcon(ppar));
}
function CreateIcon(par){
    return("<span class='glyphicon glyphicon-"+par+"'></span>");
}
function UpdateP(obj,lst){
    ltd=obj.find("td");
    ltd.html("");
    ltd.removeAttr("data-id");
    ltd.removeAttr("title");
    ltd=obj.find("tr");
    ltd.removeClass("st_file").removeClass("st_grp");
    ltd.removeAttr("data-id");
    lst_len=lst.length;
    row_cnt=15;
    if(lst_len<15)row_cnt=lst_len;
    lrows=obj.find("tr");
    for(var i=1;i<=row_cnt;i++){
        lrow=lrows.eq(i).children("td");
        ldt=JSON.parse(lst[i-1]);
        if(ldt.isgroup=="Y")lrow.parent().addClass("st_grp");
        else lrow.parent().addClass("st_file");
        lrow.parent().attr("data-id",ldt.id);
        lrow.eq(0).html(TbIcon(ldt.isgroup));
        lrow.eq(1).html(ldt.name);
        lrow.eq(1).attr("title",ldt.descr);
        lrow.eq(2).html(ldt.time);
        lrow.eq(3).html(ldt.tpln);
    }
}
function StartPanel(){
    lpanel= tsys;
    rpanel= tsys;
    UpdateP($("#lpanel"),lpanel);
    UpdateP($("#rpanel"),rpanel);
}
function SetSubPanel(){
    obj=$("#submenu");
    obt=$("#"+CurPanel);
    lt=obt[0].offsetTop;
    ll=obt[0].offsetLeft;
    lw=obt[0].offsetWidth;
    obj.css({
        "display":"flex",
        "top":lt,
        "left":ll,
        "width":lw
    });
}
$(window).resize(function(){
    SetSubPanel();
})
$(document).ready(function(){
    $("tr").click(function(e){
        if($(this).is(".active2"))return(true);
        if(!$(this).attr("data-id")){
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        $("tr").removeClass("active2");
        $(this).addClass("active2");
        CurPanel=$(this).parent().parent().attr("id");
        SetSubPanel();
    })
    $("tr").dblclick(function(e){
        if(!$(this).is(".active2"))return;
        lid=$(this).attr("data-id");
        if($(this).is(".st_grp")){
            $("tr").removeClass("active2");
            $("submenu").css("display","none");
            UPanel=CurPanel;
            if(UPanel=="rpanel")rp_curdir=lid;
            else lp_curdir=lid;
            GetItems();
        }else{
            
            window.location = "?do=newtasck&item="+lid;
        }
    })
    $(document).on("click",".it_path",function(){
        lid=$(this).attr("data-id");
        $("tr").removeClass("active2");
        $("submenu").css("display","none");
        UPanel=$(this).parent().attr("data-id");
        if(UPanel=="rpanel")rp_curdir=lid;
        else lp_curdir=lid;
        GetItems();
    })
    StartPanel();
    CreateSubMenu();
})