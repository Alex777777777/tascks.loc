$(document).ready(function(){
    $("#btn_select").click(function(){
        dts=$("#dts").children("input").val();
        dtk=$("#dtk").children("input").val();
        param={
            "dts":dts,
            "dtk":dtk
        }
        $.ajax({
            type: "POST",
            url: "?cmd=result",
            data: param,
            cache: false,
            async: true,
            success: function(qstr){
                ret=qstr;
                if(ret=="ER"){
                    alert("Ошибка получения данных!");
                }else{
                    $(".res_tbl").html(ret);
                }

            }
        })
    })
    $("td.col2").click(function(){
        lid=$(this).attr("data-id");
        window.location="?do=newtasck&bkg=res&item="+lid
    })
    now=new Date();
    $('#dts').datetimepicker({pickTime: false, language: 'ru',defaultDate:now});
    $('#dtk').datetimepicker({pickTime: false, language: 'ru',defaultDate:now});
    $("#dts").on("dp.change",function (e) {
      $("#dtk").data("DateTimePicker").setMinDate(e.date);
    });
    $("#dtk").on("dp.change",function (e) {
      $("#dts").data("DateTimePicker").setMaxDate(e.date);
    });
    
})