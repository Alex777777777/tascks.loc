function UpdateInfo(){
    obj=$(".gpb_body > ul > .active2");
    $lid=obj.attr("data-id");
    param={
        "tpl":"userinfo",
        "id":$lid
    }
    $.ajax({
        type: "POST",
        url: "?cmd=adpanel",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            ret=qstr;
            if(ret=="ER"){
                alert("Ошибка получения данных!");
                return;
            }else{
                $(".usb_body").html(ret);
            }
        }
    })          
}
$(document).ready(function(){
    $(".gpb_body > ul > li").click(function(){
        if($(this).hasClass("active2")==true)return;
        $(".gpb_body > ul > li").removeClass("active2");
        $(this).addClass("active2");
        UpdateInfo();
        
    })
    $(".usb_body").on("click","td > .tbl_btn",function(){
        type=$(this).attr("data-id");
        lid=$(this).parent().attr("data-id");
        wkp=$(this).parent().attr("data-wkp");
        lstr="Перераспределить задание?";
        if(type=="rem")lstr="Снять задание?";
        if(!confirm(lstr))return;
        param={
        "type":type,
        "id":lid,
        "wkp":wkp
        }
    $.ajax({
        type: "POST",
        url: "?cmd=maina",
        data: param,
        cache: false,
        async: true,
        success: function(qstr){
            ret=qstr;
            if(ret=="ER"){
                alert("Ошибка получения данных!");
                return;
            }else{
                UpdateInfo();
            }
        }
    })
    })
    $(".gpb_body > ul > li").eq(0).addClass("active2");
    UpdateInfo();
})