function GetTasckItem(){
    $(".itembar>ul").html("");
    obj=$(".tasckbar > ul > li.active2");
    if(!obj.length){
        tit_arr=new Array();
        return;
    }
    lid=obj.eq(0).attr("data-id");
    param={
        "tpl":"itget",
        "id":lid
    }
    $.ajax({
        type: "POST",
        url: "?cmd=tasckcmd",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            ret=qstr;
            if(ret=="ER"){
                alert("Ошибка!");
                tit_arr=[];
            }else{
                tit_arr=JSON.parse(ret);
                UpdateTasckItem();
            }            
        }
    })
}
function UpdateTasckItem(){
    if(!("gen" in tit_arr))return;
    agen=tit_arr["gen"];
    apar=tit_arr["par"];
    ams=tit_arr["ms"];
    $(".itembar > .pb_header >span").eq(1).html("Автор: "+agen["user"]+" от "+agen["time"]);
    $(".itembar > .pb_caps >span").eq(1).html("Задание № "+agen["id"]);
    $(".itembar > .dobar >span").html("Состояние: "+agen['state']);
    lstr="";
    lstr+="<div class='it_str'><div class='its_caps'>Шаблон:</div><div class='its_val'>"+agen['tpl']+"</div></div>";
    lstr+="<div class='it_str'><div class='its_caps'>Наименование:</div><div class='its_val'>"+agen['name']+"</div></div>";
    lstr+="<div class='it_str'><div class='its_caps'>Описание:</div><textarea  class='its_val'>"+agen['descr']+"</textarea></div>";
    lstr+="<div class='separ'>Дополнительные параметры</div>";
    for(i=0;i<apar.length;i++){
        val=apar[i];
        if(!val[1])val[1]="NaN";
        pstr="<div class='it_str'><div class='its_caps'>"+val[2]+":</div><div class='its_val'>"+val[1]+"</div></div>";
        lstr+=pstr;
    }
    lstr+="<div class='separ'>Результаты работы</div>";
    lstr+="<table class='table'><thead><th>#</th><th>Дата</th><th>Юзер</th><th>Результат</th></thead><tbody>";
    i=1;
    while(ams[i]){
        val=ams[i];
        i++;
        lstr+="<tr><td>"+val.cod+"</td><td>"+val.date+"</td><td>"+val.user+"</td><td>"+val.reason+"</td>";
    }
    lstr+="</tbody></table>"
    $(".itembar > .nav").html(lstr);
}
function GetTsk(){   
    obj=$(".projbar > ul > li.active2");
    if(!obj.length){
        tsk_arr=new Array();
        return;
    }
    lid=obj.eq(0).attr("data-id");
    param={
        "tpl":"tget",
        "grp":lid
    }
    $.ajax({
        type: "POST",
        url: "?cmd=tasckcmd",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            ret=qstr;
            if(ret=="ER"){
                alert("Ошибка!");
            }else{
                tsk_arr=JSON.parse(ret);
                UpdateTsk();
            }            
        }
    })
}
function UpdateTsk(){
    n=1;
    ret="";
    fl=$(".tasckbar>.pb_header").attr("data-id");
    $(".tasckbar > ul").html(ret);
    icon=[];
    icon["20"]="trash";
    icon["0"]="unchecked";
    icon["10"]="edit";
    icon["11"]="check";
    for(i=0;i<tsk_arr.length;i++){
        lobj=tsk_arr[i];
        if(fl!="*"){
            if(lobj.state!=fl)continue;
        }
        n=Math.abs(n-1);
        lstr="<li class='task-item n"+n+"' data-id='"+lobj.id+"'>";
        lstr+="<input class ='ti-b1' type='checkbox' value='"+lobj.id+"'>";
        lstr+="<div class='ti-b2'>";
        lstr+="<div class='ti-name'>#"+lobj.id+"; "+lobj.name+" ["+lobj.time+"]</div>";
        lstr+="<div class='ti-descr'>"+lobj.descr;
        lstr+="</div></div><div class=\"ti-b3\">";
        lstr+="<span class='glyphicon glyphicon-"+icon[lobj.state]+" icon'></span></div></li>"
        ret+=lstr;
    }
    $(".tasckbar > ul").html(ret);
    obj=$(".projbar > ul > li.active2");
    lgrp=obj.attr("data-name");
    $(".tasckbar > .pb_caps >span").eq(2).html("Задания проэкта "+lgrp);
}
function UpdateGrp(){
    n=1;
    ret="";
    fl=$(".projbar>.pb_header").attr("data-id");
    $(".projbar > ul").html(ret);
    icon=[];
    icon["20"]="trash";
    icon["0"]="home";
    for(i=0;i<grp_arr.length;i++){
        lobj=grp_arr[i];
        if(fl!="*"){
            if(lobj.state!=fl)continue;
        }
        n=Math.abs(n-1);
        lstr="<li class='n"+n+"' data-id='"+lobj.id+"' data-name='"+lobj.name+"'><div class='cont_col1'><input type='checkbox' value='"+lobj.id+"'></div> <div class='cont_col2'><div class='cont_r1'><span class='glyphicon glyphicon-"+icon[lobj.state]+"' style='float: left;'></span>"+lobj.name+"</div><div class='cont_r2'><span>"+lobj.descr+"</span></div></div</li>";
        ret+=lstr;
    }
    $(".projbar > ul").html(ret);
}
$(document).ready(function(){
    $(".dobar>a").click(function(e){
        e.preventDefault();
        doid=$(this).attr("data-id");
        if(doid=="1")$(".nav-sidebar>li>.cont_col1>input").prop("checked", true);
        else $(".nav-sidebar>li>.cont_col1>input").prop("checked", false);
    })
    $(".pb_caps>.extdo").click(function(){
        obj=$(this).children(".proj_menu");
        obj.slideDown("slow");
    })
    $(".pb_caps>.extdo").mouseleave(function(){
        var obj=$(this).children(".proj_menu");
        obj.slideUp("slow");
    })
    $(".pb_header").click(function(){
        var lobj=$(this).children("ul");
        if(!lobj.length)return;
        bobj=$(this)[0];
        par={
            "top":bobj.offsetTop+20,
            "left":bobj.offsetLeft+10
        }
        lobj.css(par);
        lobj.slideDown("slow");
    })
    $(".pb_header").mouseleave(function(){
        var obj=$(this).children("ul");
        if(!lobj.length)return;
        if(obj.css("display")=="block")obj.slideUp("slow");
    })
    $(".projbar").on("click","ul.nav.nav-sidebar > li",function(){
        $(".projbar > ul > li").removeClass("active2");
        $(this).addClass("active2");
        GetTsk();
    })
    $(".tasckbar").on("click","ul > li",function(){
        $(".tasckbar > ul > li").removeClass("active2");
        $(this).addClass("active2");
        GetTasckItem();
    })
    $(".view_menu>li").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        lid=$(this).attr("data-id");
        ltxt=$(this).html();
        $(this).parent().parent().children(".pbh_text").html(ltxt);
        $(this).parent().parent().attr("data-id",lid);
        lname=$(this).parent().parent().attr("data-name");
        $(".pb_header>ul").css("display","none");
        if(lname =="grp"){
            UpdateGrp();
        }
        if(lname =="tsk"){
            UpdateTsk();
        }

    })
    
    $(".proj_menu>li").click(function(e){
        e.stopPropagation();
        e.preventDefault();
        mid=$(this).attr("data-id");
        if(mid=="gnew"){
            $("#ext-wrp").css("display","block");
            $(".ngfrm").css("display","block");
        }
        if(mid=="gdowrk"){
            $("#ext-wrp").css("display","block");
            $(".dofrm").attr("data-id","grp");
            lobj=$('.projbar>ul>li>div>input:checked');
            kvo=lobj.length;
            if(!kvo){
                alert("Нет выделенных групп заданий.");
                $("#ext-wrp").css("display","none");
                $("#dofrm").css("display","none");
                return;
            }
            $(".dofrm").css("display","block");
        }
        if(mid=="tdowrk"){
            $("#ext-wrp").css("display","block");
            $(".dofrm").attr("data-id","tsk");
            lobj=$('.tasckbar>ul>li>input:checked');
            kvo=lobj.length;
            if(!kvo){
                alert("Нет выделенных заданий.");
                $("#ext-wrp").css("display","none");
                $("#dofrm").css("display","none");
                return;
            }
            $(".dofrm").css("display","block");
        }
        if(mid=="tnew"){
            obj=$(".projbar > ul > li.active2");
            if(!obj.length){
                alert("Не выбран проект");
                return;
            }
            lgrp=obj.attr("data-id");
            window.location = "?do=newtasck&grp="+lgrp;
        }
        if(mid=="tedit"){
            obj=$(".tasckbar > ul > li.active2");
            if(!obj.length){
                alert("Не выбран проект");
                return;
            }
            ltsk=obj.attr("data-id");
            window.location = "?do=newtasck&item="+ltsk;
        }
        if(mid=="tdel"){
            $("#ext-wrp").css("display","block");
            $(".tasckbar>.pb_header>ul").slideUp("slow");
            lobj=$('.tasckbar>ul>li>input:checked');
            kvo=lobj.length;
            if(!kvo){
                alert("Нет выделенных заданий.");
                $("#ext-wrp").css("display","none");
                return;
            }
            if(!confirm("Действительно удалить "+kvo+"  элементов?"))return;
            obj=$(".projbar > ul > li.active2");
            lgrp=obj.attr("data-id");
            lid=""
            for(i=0;i<kvo;i++){
                mobj=lobj.eq(i);
                lid+=mobj.val()+",";
            }
            param={
                "tpl":"tdel",
                "grp":lgrp,
                "ids":lid
            }
            $.ajax({
            type: "POST",
            url: "?cmd=tasckcmd",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка!");
                }else{
                    tsk_arr=JSON.parse(ret);
                    UpdateTsk();
                }
                $("#ext-wrp").css("display","none");            
            }
            })
        }
        if(mid=="gdel"){
            $("#ext-wrp").css("display","block");
            $(".projbar>.pb_header>ul").slideUp("slow");
            lobj=$('.projbar>ul>li>div>input:checked');
            kvo=lobj.length;
            if(!kvo){
                alert("Нет выделенных проэктов.");
                $("#ext-wrp").css("display","none");
                return;
            }
            if(!confirm("Действительно удалить "+kvo+"  элементов?"))return;
            lid=""
            for(i=0;i<kvo;i++){
                mobj=lobj.eq(i);
                lid+=mobj.val()+",";
            }
            param={
                "tpl":"gdel",
                "ids":lid
            }
            $.ajax({
            type: "POST",
            url: "?cmd=tasckcmd",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка!");
                }else{
                    grp_arr=JSON.parse(ret);
                    UpdateGrp();
                }
                $("#ext-wrp").css("display","none");            
            }
            })
        }
        if(mid=="ghide"){
            $("#ext-wrp").css("display","block");
            $(".projbar>.pb_header>ul").slideUp("slow");
            lobj=$('.projbar>ul>li>div>input:checked');
            kvo=lobj.length;
            if(!kvo){
                alert("Нет выделенных проэктов.");
                $("#ext-wrp").css("display","none");
                return;
            }
            if(!confirm("Действительно удалить видимость для "+kvo+" ' элементов?"))return;
            lid=""
            for(i=0;i<kvo;i++){
                mobj=lobj.eq(i);
                lid+=mobj.val()+",";
            }
            param={
                "tpl":"ghide",
                "ids":lid
            }
            $.ajax({
            type: "POST",
            url: "?cmd=tasckcmd",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка!");
                }else{
                    grp_arr=JSON.parse(ret);
                    UpdateGrp();
                }
                $("#ext-wrp").css("display","none");            
            }
            })
        }
        if(mid=="gview"){
            $("#ext-wrp").css("display","block");
            $(".projbar>.pb_header>ul").slideUp("slow");
            lobj=$('.projbar>ul>li>div>input:checked');
            kvo=lobj.length;
            if(!kvo){
                alert("Нет выделенных проэктов.");
                $("#ext-wrp").css("display","none");
                return;
            }
            if(!confirm("Действительно установить видимость для "+kvo+" ' элементов?"))return;
            lid=""
            for(i=0;i<kvo;i++){
                mobj=lobj.eq(i);
                lid+=mobj.val()+",";
            }
            param={
                "tpl":"gview",
                "ids":lid
            }
            $.ajax({
            type: "POST",
            url: "?cmd=tasckcmd",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка!");
                }else{
                    grp_arr=JSON.parse(ret);
                    UpdateGrp();
                }
                $("#ext-wrp").css("display","none");
            }
            })
        }
    })
    $(".btn-mini.frm").click(function(){
        lid=$(this).attr("data-id");
        if(lid=="fcl"){
            $("#ext-wrp").css("display","none");
            $(".ngfrm").css("display","none");
        }
        if(lid=="dwr"){
            lname=$("#frm_name").val();
            ldescr=$("#frm_descr").val();
            if(!lname.length){alert("Пустое наименование");return;}
            param={
                "tpl":"ng",
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
                if(ret=="ER"){
                    alert("Ошибка создания группы!");
                }else{
                    $(".ngfrm").css("display","none");
                    $("#ext-wrp").css("display","none");
                    grp_arr=JSON.parse(ret);
                    UpdateGrp();
                }            
            }
            })
        }
        if(lid=="docl"){
            $("#ext-wrp").css("display","none");
            $(".dofrm").css("display","none");
        }
        if(lid=="dowr"){
            ltp=$(".dofrm").attr("data-id");
            if(ltp=="grp"){
                lobj=$('.projbar>ul>li>div>input:checked');
                kvo=lobj.length;
                if(!confirm("Действительно запустить "+kvo+"  элементов?"))return;
                $("#ext-wrp").css("display","none"); 
                lsps="";
                for(i=0;i<kvo;i++){
                    mobj=lobj.eq(i);
                    lsps+=mobj.val()+",";
                }
                lgrp=$("#togrp").val();
                lusr=$("#tousr").val();
                param={
                    "tpl":"do",
                    "togrp":lgrp,
                    "tousr":lusr,
                    "sps":lsps
                }
                $.ajax({
                type: "POST",
                url: "?cmd=tasckcmd",
                data: param,
                cache: false,
                async: true,
                success: function(qstr){
                    ret=qstr;
                    if(ret=="ER"){
                        alert("Ошибка!");
                    }else{
                        GetTsk();
                    }
                }
                })
            }else{
                lobj=$('.tasckbar>ul>li>input:checked');
                kvo=lobj.length;
                if(!confirm("Действительно запустить "+kvo+"  элементов?"))return;
                lsps="";
                for(i=0;i<kvo;i++){
                    mobj=lobj.eq(i);
                    lsps+=mobj.val()+",";
                }
                lgrp=$("#togrp").val();
                lusr=$("#tousr").val();
                param={
                    "tpl":"do",
                    "togrp":lgrp,
                    "tousr":lusr,
                    "sps":lsps
                }
                $.ajax({
                type: "POST",
                url: "?cmd=tasckcmd",
                data: param,
                cache: false,
                async: true,
                success: function(qstr){
                    ret=qstr;
                    if(ret=="ER"){
                        alert("Ошибка!");
                    }else{
                        GetTsk();
                    }
                    $("#ext-wrp").css("display","none");            
                }
                })
            }
            $(".dofrm").css("display","none");            
        }
    })
    UpdateGrp();
    if(!cur_grp) $(".projbar > ul > li").eq(0).addClass("active2");
    else $(".projbar > ul > li[data-id='"+cur_grp+"']").eq(0).addClass("active2");
    GetTsk();
})