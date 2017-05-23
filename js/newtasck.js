$(document).ready(function(){
    $(".button").click(function(){
        di=$(this).attr("data-id");
        if(di=="close"){
            document.location="?do=tascks";
            return
        }
        if(di=="close"){
            lid=$("form").attr("data-id");
            lname=$("#tsk_name").val();
            ldescr=$("#tsk_descr").val();
            lgrp=$("#tsk_grp").val();
            
            param={
                "tpl":"addtasck",
                "id":lid,
                "name":lname,
                "descr":ldescr,
                "param":lpar
            }
            $.ajax({
                type: "POST",
                url: "command.php",
                data: param,
                cache: false,
                async: true,
                success: function(qstr){
                    ret=+qstr;
                    if(!ret){
                        lc=document.location;
                        document.location=lc.origin+lc.pathname+"?do=users";
                    }else{
                        $("#usr_resume").html("Ошибка! "+qstr);
                        $("#usr_resume").css("color","#f00");
                    }            
                }
            })
        }
    })
})