function GetGpUsers(){
    if(cur_gp==0){$(".gpusers").html("");return;};
    param={
        "tpl":"getgpuser",
        "id":cur_gp
    }
    $.ajax({
        type: "POST",
        url: "?cmd=grupscmd",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            $(".gpusers").html(qstr);
        }
    });
}
$(document).ready(function(){
    $("button.add_grp").click(function(){
        $("#ext-wrp").css("display","block");
        $(".frm_add").css("display","block");
    })
    $("button.frm_cls").click(function(){
        $("#ext-wrp").css("display","none");
        $(".frm_add").css("display","none");
        $(".frm_add input").val("");
    })
    $("button.frm_save").click(function(){
        lname=$("#frm_name").val();
        if(lname==""){alert("Пустое наименование");return;}
        param={
            "tpl":"addgrp",
            "name":lname
        }
        $.ajax({
            type: "POST",
            url: "?cmd=grupscmd",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                if(qstr!="OK")alert("Ошибка добавления гуппы!");
                $("#ext-wrp").css("display","none");
                $(".frm_add").css("display","none");
                $(".frm_add input").val("");
                document.location.reload();
            }
        });
    })
    $(document).on("click",".user_item>input",function(e){
            fl=0;
            if($(this).prop("checked"))fl=1;
            lusr=$(this).parent().attr("data-id");
            param={
            "tpl":"chggrp",
            "user":lusr,
            "fl":fl,
            "grp":cur_gp
        }
        $.ajax({
            type: "POST",
            url: "?cmd=grupscmd",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
            }
        });
    })        
    $(".gnav>li").click(function(){
        cur_gp=$(this).attr("data-id");
        $(".gnav>li").removeClass("active2");
        $(this).addClass("active2");
        GetGpUsers();
    })
    GetGpUsers();
})