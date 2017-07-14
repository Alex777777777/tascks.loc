$(document).ready(function(){
    $("#bt_load").click(function(){
        lid=$(".t_info select").val();
        param={
            "id":lid
        }
        $.ajax({
            type: "POST",
            url: "?cmd=deskbord",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    $("#deskbord").html("Ошибка загрузки задания!");
                }else{
                    $("#deskbord").html(ret);
                    ltxt="Задание №"+hdarr.id+" '"+hdarr.name+"' от "+hdarr.date;
                    $("#caps_txt").html(ltxt);
                }
            }
        })
    })
    $("#frm_net").click(function(){
        $("#ext-wrp").css("display","none");
        $(".addform").css("display","none");
    })
    $("#frm_save").click(function(){
        ldescr=$(".frm_descr").val();
        lres=$(".frm_resume").val();
        param={
            "tpl":"addresume",
            "descr":ldescr,
            "resume":lres,
            "id":hdarr["wpid"]
        }
        $.ajax({
            type: "POST",
            url: "?cmd=wkpanel",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка удаления группы!");
                    return;
                }
                document.location.reload();
            }
        })
    })
    $(document).on("click","#newmsg",function(){
        $("#ext-wrp").css("display","block");
        $(".addform").css("display","block");
    })
    setTimeout("$('.page-header>h1').hide('slow');",5000);
    if(tasck_count==0){
       // setTimeout("window.location.reload();",60000);
    }
    $("#bt_load").click();
})