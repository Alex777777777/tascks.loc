var ic_folder_c="folder-close";
var ic_folder_o="folder-open";
var ic_file="file";
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
    param={
        "lp":lp_curdir,
        "rp":rp_curdir
    }   
    $.ajax({
        type: "POST",
        url: "?cmd=getitem",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            arr=JSON.parse(qstr);
            if(arr){
                tsys=JSON.parse(arr["lp"]);
                lstr=tsys[0];
                tsys.splice(0,1);
                lpanel= tsys;
                UpdateP($("#lpanel"),lpanel);
                $(".lpanel .path").html(lstr);
                tsys=JSON.parse(arr["rp"]);
                lstr=tsys[0];
                tsys.splice(0,1);
                rpanel= tsys;
                UpdateP($("#rpanel"),rpanel);
                $(".rpanel .path").html(lstr);
            }
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
function UpdatePanel(){
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
            if(CurPanel=='rpanel') rp_curdir=lid;
            else lp_curdir=lid;
            $("tr").removeClass("active2");
            $("#submenu").css("display","none");
            GetItems();
            UpdatePanel();
        }else{            
            window.location = "?do=newtasck&item="+lid+"&lp="+lp_curdir+"&rp="+rp_curdir;
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
        UpdatePanel();
    })
    $("button.btn-mini").click(function(){
        di=$(this).attr("data-id");
        ext="&lp="+lp_curdir+"&rp="+rp_curdir;
        switch(di){
            case "nz":
                cgp=0;
                if(CurPanel=="rpanel")cgp=rp_curdir;
                else cgp=lp_curdir;
                window.location="?do=newtasck&gp="+cgp+ext;
            break;
            case "ng":
                $(".ngfrm input").val("");
                $(".ngfrm textarea").val("");
                $(".ngfrm").css("display","block");
                $("#ext-wrp").css("display","block");
            break;
            case "fcl":
                $(".ngfrm").css("display","none");
                $("#ext-wrp").css("display","none");
            break;
            case "fwr":
                if(CurPanel="rpanel"){
                    lfrom=rp_curdir;
                    lto=lp_curdir;
                }else{
                    lfrom=lp_curdir;
                    lto=rp_curdir;
                }
                lname=$("#frm_name").val();
                ldescr=$("#frm_descr").val();
                if(!lname.length){alert("Пустое наименование");return;}
                param={
                    "tpl":"ng",
                    "from":lfrom,
                    "to":lto,
                    "name":lname,
                    "descr":ldescr
                }
                $.ajax({
                type: "POST",
                url: "?cmd=tasckcmd",
                data: param,
                cache: false,
                async: true,
                success: function(qstr){
                    ret=qstr;
                    if(ret=="OK"){
                        GetItems();
                        $(".ngfrm").css("display","none");
                        $("#ext-wrp").css("display","none");
                        UpdatePanel();
                    }
                    if(ret=="ER"){
                        alert("Ошибка создания группы!");
                    }            
                }
                })
                break;
            case "mv":
                lid=$(".active2").attr("data-id");
                param={
                    "tpl":"mv",
                    "id":lid
                }
                $.ajax({
                    type: "POST",
                    url: "?cmd=tasckcmd"+ext,
                    data: param,
                    cache: false,
                    async: true,
                    success: function(qstr){
                        ret=qstr;
                        if(ret=="OK"){
                            GetItems();
                            UpdatePanel();
                            return;
                        }
                        if(ret=="ER"){
                            alert("Ошибка удаления группы!");
                            return;
                        }
                        alert(ret);
                    }
                })
            break;
            case "cp":
                lid=$(".active2").attr("data-id");
                param={
                    "tpl":"cp",
                    "id":lid
                }
                $.ajax({
                    type: "POST",
                    url: "?cmd=tasckcmd"+ext,
                    data: param,
                    cache: false,
                    async: true,
                    success: function(qstr){
                        ret=qstr;
                        if(ret=="OK"){
                            GetItems();
                            UpdatePanel();
                            return;
                        }
                        if(ret=="ER"){
                            alert("Ошибка копирования группы!");
                            return;
                        }
                    }
                })
            break;
            case "dl":
                if(!confirm("Действительно Удалить?"))return;
                lid=$(".active2").attr("data-id");
                param={
                    "tpl":"dl",
                    "id":lid
                }
                $.ajax({
                    type: "POST",
                    url: "?cmd=tasckcmd"+ext,
                    data: param,
                    cache: false,
                    async: true,
                    success: function(qstr){
                        ret=qstr;
                        if(ret=="OK"){
                            GetItems();
                            UpdatePanel();
                            return;
                        }
                        if(ret=="ER"){
                            alert("Ошибка удаления группы!");
                            return;
                        }
                        alert(ret);
                    }
                })
            break;
        }
    })
    GetItems();
    UpdatePanel();
})