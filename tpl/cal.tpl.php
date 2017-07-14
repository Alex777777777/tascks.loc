<style>
.calendar{
width:140px;
font-size: 12px;
}
.calendar>input{
width: 140px;
}
.cal-mon{
}
.cal-mon>.larr{
    float:left;
}
.cal-mon>.rarr{
    float:right;
}
.cal-mon>span{
    display:block;
    margin:0 auto;
    text-align: center;
}
.cal-tb{
display:block;
width: 140px;
/*font-size: 12px;*/
border-bottom: 3px double  #4b89c6;
background: #f0f4f8;
}
.cal-tb tr{
display:flex;    
}
.cal-tb td{
display:block;
text-align: center;
cursor: pointer;
width: 20px;
}
.cal-caps{
display:block;
border-bottom: 3px double  #4b89c6;
font-weight: 600;
}
.cal-str td{
border-bottom: 1px solid  #fff;
}
.cal-str td:hover{
font-weight: 600;
}
</style>
<script>
function initCalendar(pobj,idt){
    var now;
    if (idt === undefined) now=new Date();
    else now=new Date(idt);
    var moon=now.getMonth();
    var ndt=new Date(now.getFullYear(),moon-1,1);
    var ldt=new Date(now.getFullYear(),moon,0);
    var maxDay=ldt.getDate();
    var dow=ndt.getDay();
    dCal= new Array();
    for(var i=0;i<6;i++){
        dCal[i]=[0,0,0,0,0,0,0];
    }
    var cd =1;
    var row=0;
    var cur=dow;
    var ln0="<tr class='cal-caps'><td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td></tr>";
    var ln1="<tr class='cal-str'><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    while(cd<=maxDay){
        dCal[row][cur-1]=cd;
        cd++;
        cur++;
        if(cur>7){cur=1; row++}
    }
    maxrow=row+1;
    ret=ln0;
    for(i=0;i<maxrow;i++){
        ret=ret+ln1;    
    }
    lobj=pobj.children(".calendar-ext");
    lobj.children("table").html(ret);
    pobj=lobj.children("table").children("tbody").children("tr");
    for(i=1;i<=maxrow;i++){
        lobj=pobj.eq(i).children("td");
        lrow=dCal[i-1];
        for(ii=0;ii<7;ii++){
            if(lrow[ii]){
                lobj.eq(ii).html(lrow[ii]);
            }
        }
    }
}
$(document).ready(function(){
    pobj=$(".calendar");
    var lobj=pobj.children("input");
    ldt=new Date();
    lstr=""+ldt.getFullYear();
    lstr+="-"+("0"+(ldt.getMonth()+1)).slice(-2);
    lstr+="-"+("0"+ldt.getDate()).slice(-2);
    lobj.val(lstr);
    initCalendar(pobj);
})
</script>
<div class="page-header">
    <h1>Результаты работы пользователей за &nbsp
<select>
<option selected>текущий день</option>
<option>неделю</option>
<option>месяц</option>
</select>.</h1>
</div>
<div class="container">
<div class="row">
<div class="calendar">
<input type="text">
<div class="calendar-ext">
<div class="cal-mon"><span class="glyphicon glyphicon-chevron-left larr"></span><span class="glyphicon glyphicon-chevron-right rarr"></span><span class="cm-text">МАРТ</span></div>
<table class="cal-tb">

</table>
</div>
</div>
</div>
</div>

<br><br>