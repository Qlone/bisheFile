function respon(num){
    if($("#resp"+num).text() == "回复成功！"){
        return false;
    }else{
    	var str=prompt("回复客户","谢谢您的支持，我们会继续努力！");    
        if(str == ""){
            alert("请输入要回复的话！");
            $("#resp"+num).text("回复失败！");
            return false;
        }else{
            $.post("?ctl=manager&act=responEvaluation", {
                evaluationId: num,
                respon: str
            }, function (data) {
                if(!data){
                    $("#resp"+num).text("回复失败！");
                    return false;
                }
            });
            
            $.post("?ctl=msg&act=responEvaluation",{
                evaluationId: num,
                message: str
            }, function(data) {
                if(data){
                    $("#resp"+num).text("回复成功！");
                }else{
                    $("#resp"+num).text("回复失败！");
                }
            });
        }
    }
}

$(function(){
    var $container = $('#evaluations'); 
    $container.infinitescroll({
        navSelector : "#npage",
        nextSelector : "#npage a",
        itemSelector : "#box",
        pixelsFromNavToBottom: 200,   
        loading: {
            msgText: "正在加载中...",
            finishedMsg: "已加载完毕"
        }
    },function( newElements ) {
        var $newElems = $(newElements);
        $container.append($newElems);
    });
});