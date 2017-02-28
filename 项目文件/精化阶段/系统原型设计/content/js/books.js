function accept(num){
    
    if($("#accept"+num).text() != "" || $("#refuse"+num).text() != ""){
    	$("#accept"+num).text("已处理");
        return false;
    }else{
    
        if($("#accept"+num).val() != ""){
            return false;
        }
        if($("#refuse"+num).val() != ""){
            return false;   
        }
        
        $.post("?ctl=manager&act=updateBookState", {
            bookId: num,
            state: "accept"
        }, function (data) {
            if(!data){
                $("#refuse"+num).text("操作失败！");
                return false;
            }
        });
        
        $.post("?ctl=msg&act=responBook", {
            bookId: num,
            state: "accept"
        }, function (data) {
            var message = jQuery.parseJSON(data);
            if(message.errmsg == "ok"){   
                $("#accept"+num).text("已接受");
            }else{
                $("#refuse"+num).text("通知客户失败！");
                return false;
            }
        });
    }
}

function refuse(num){
    
    if($("#accept"+num).text() != "" || $("#refuse"+num).text() != ""){
    	$("#accept"+num).text("已处理");
        return false;
    }else{
        var str=prompt("请输入拒绝理由：","");
        if(str == ""){
            alert("请输入拒绝理由！");
            $("#resp"+num).text("操作失败！");
            return false;
        }
        if($("#accept"+num).val() != ""){
            return false;
        }
        if($("#refuse"+num).val() != ""){
            return false;   
        }
        
        $.post("?ctl=manager&act=updateBookState", {
            bookId: num,
            state: "refuse"
        }, function (data) {
            if(!data){
                $("#refuse"+num).text("操作失败！");
                return false;
            }
        });
        
        $.post("?ctl=msg&act=responBook", {
            bookId: num,
            state: str
        }, function (data) {
            var message = jQuery.parseJSON(data);
            if(message.errmsg == "ok"){    
                $("#refuse"+num).text("已拒绝");
            }else{
                $("#refuse"+num).text("通知客户失败！");
                return false;
            }
        });
    }
}

$(function(){
    var $container = $('#books'); 
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