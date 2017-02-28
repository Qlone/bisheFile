message();
window.setInterval(message, 10000);
function msgBox(){
    $("#message").slideToggle("slow");
    setTimeout(function(){
        $("#message").slideToggle("slow");
    }, 3000);
}

function message(){
    $.post("?ctl=manager&act=getNews", {
        
    }, function(data) {
        var news = jQuery.parseJSON(data);
        var temp = "";
        if(news.book != 0 || news.evaluation != 0){
            temp = "<center style=\"margin-top:10%\"><a href=\"?ctl=manager&act=handleBooks\" style=\"color:White\">" + news.book + "&nbsp;条新预约</a><br/>";
            temp += "<a href=\"?ctl=manager&act=handleEvaluations\" style=\"color:White\">" + news.evaluation + "&nbsp;条新评论</a><br/></center>";
        	$("#message").html(temp);
            $("#message").slideToggle("slow");
            $("#message").css("display","block");
            flash_title();
        }else{
            temp = "<center style=\"margin-top:10%\"><a style=\"color:White\">没有新消息</a></center>";
            $("#message").html(temp);
        }
    });
}
var step = 0;
var _title1 = "【　　　】" + document.title;
var _title2 = "【新消息】" + document.title;
for(var i=0;_title2.length<=_title1.length-1;i++) {_title2+='.';}
function flash_title()
{
  	step++;
  	if (step==3) {step=1;}
  	if (step==1) {document.title=_title2;}
  	if (step==2) {document.title=_title1;}
  	setTimeout("flash_title()",500);
}