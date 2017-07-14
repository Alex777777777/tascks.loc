function GetTempl(){
        lid=$(".active2").attr("data-id");
        param={
            "id":lid
        }
        $.ajax({
            type: "POST",
            url: "?cmd=templates",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка получения данных!");
                }else{
                    $(".templ").html(ret);
                }
            }
        })
} 
function NewTempl(){
        lname=$(".ngfrm #frm_name").val();
        ldescr=$(".ngfrm #frm_descr").val();
        param={
            "tpl":"newtpl",
            "name":lname,
            "descr":ldescr
        }
        $.ajax({
            type: "POST",
            url: "?cmd=templ2",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                document.location.reload();
            }
        })
}
function NewParam(){
        lid=$(".active2").attr("data-id");
        lname=$(".npfrm #frm_name").val();
        lkey=$(".npfrm #frm_key").val();
        param={
            "tpl":"newpar",
            "id":lid,
            "name":lname,
            "key":lkey
        }
        $.ajax({
            type: "POST",
            url: "?cmd=templ2",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                GetTempl();
            }
        })
}

$(document).ready(function(){
    $(".btn.new").click(function(){
        $("#ext-wrp").css("display","block");
        $(".ngfrm").css("display","block");
    })
    $(document).on("click",".btn.newp",function(){
        $("#ext-wrp").css("display","block");
        $(".npfrm").css("display","block");
    })
    $(".btn-mini.frm").click(function(){
        $par=$(this).attr("data-id");
        if($par=="fcl"){
            $(".ngfrm").css("display","none");
            $(".npfrm").css("display","none");
        }
        if($par=="fwr"){
            NewTempl();
            return;
        }
        if($par=="pwr"){
            $(".npfrm").css("display","none");
            NewParam();
        }
        $("#ext-wrp").css("display","none");
        
    })
    $(".nav>li").click(function(){
        $(".active2").removeClass("active2");
        $(this).addClass("active2");
        GetTempl();
    })
    GetTempl();
})