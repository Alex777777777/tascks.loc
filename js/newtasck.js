$(document).ready(function(){
    $(".button").click(function(){
        di=$(this).attr("data-id");
        if(di=="close"){
            document.location="?do=tascks";//+"&lp="+lp+"&rp="+rp
            return
        }
        if(di=="save"){
            lid=$("form").attr("data-id");
            lname=$("#tsk_name").val();
            ldescr=$("#tsk_descr").val();
            if(lid=="0"){
                ltpl=$("#tsk_tpl").val();
            }else{
                ltpl=$("#tsk_tpl").attr("data-id");
            }
            lgrp=$("#tsk_grp").attr("data-id");
            lpar="";
            if(lid!=0){
                tpars=$("input[data-id='param']");
                apars=new Array();
                for(var i=0;i<tpars.length;i++){
                    obj=tpars.eq(i);
                    apars[i]=[obj.attr("data-param"),obj.val()];
                }
                lpar=JSON.stringify(apars);
            }
            param={
                "tpl":"addtasck",
                "id":lid,
                "ltpl":ltpl,
                "lgrp":lgrp,
                "name":lname,
                "descr":ldescr,
                "param":lpar,
            }
            $.ajax({
                type: "POST",
                url: "?cmd=addtasck",
                data: param,
                cache: false,
                async: true,
                success: function(qstr){
                    ret=qstr;
                    if(ret=="tasck"){
                        document.location="?do=tascks";
                    }else{
                    if(ret!="0"){
                        document.location="?do=newtasck&item="+ret;
                    }else{
                        alert("Ошибка записи нового задания.");
                    }}            
                }
            })
        }
    })
})