$(function () {
    $(".nano").nanoScroller();
    x = 10;
    y = 22;
    list = null;
    getList();
    var handle = null; 
})

function Detail(){
    $("tr").mouseover(function (e) {
        if(this.id == ""){
         	return false;   
        }
        bookId = this.id;
        handle = setTimeout(function(){
            detail = "";
            $.post("?ctl=manager&act=getBook", {
                bookId: bookId
            }, function(data){
                var book = jQuery.parseJSON(data);
                detail = "<img src=\"?ctl=manager&act=getStylePic&styleId="+ book.styleid + "\" style=\"height:120px;width:120px\"><br/>";
                detail += "顾客姓名：" + book.name + "<br/>";
                detail += "预约日期：" + book.time + "<br/>";
                detail += "客户电话：" + book.tel + "<br/>";
                detail += "预约留言：" + book.message + "<br/>";
                $("#detail").html(detail);
                $("#detail")
                .css({
                    "top": (e.pageY + y) + "px",
                    "left": (e.pageX + x) + "px"
                }).show("fast");
            });
        },1000);
    }).mouseout(function (e) {
        if(this.id == ""){
         	return false;   
        }
        clearTimeout(handle);
        $("#detail").html("");
    }).mousemove(function (e) {
        if(this.id == ""){
         	return false;   
        }
        $("#detail").css({
            "top": (e.pageY + y) + "px",
            "left": (e.pageX + x) + "px"
        });
    });
}

function deleteList(id){
    var temp;
    var mark = 0;
    for(var i=0; i<list.length-1; i++){
        if(id == i){
            mark = 1;
            temp = list[i];
            temp.state = "delete";
            temp.name = "待删除";
        }
        if(mark == 0){
            list[i] = list[i];
        }else{
            list[i] = list[i+1];
        }
    }
    if(mark == 0){
        list[list.length-1].state = "delete";
        list[list.length-1].name = "待删除";
    }else{
        list[list.length-1] = temp;
    }
    refreshList();
}

function check(){
    
	var now = new Date();
    if(list[0].state == "理发中"){
        if(list[1].state == "等待中"){
            var time = new Date(list[1].time*1000);
            if(time < now){
                var temp = list[0];
                for(var i=0; i<list.length-1; i++){
                    list[i] = list[i+1]    
                }
                list[list.length-1] = temp;
                list[list.length-1].state = "已理完";
                list[0].state = "理发中";
                refreshList();
            }
        }else{
            var time = new Date(1000*(parseInt(list[0].time) + 60*list[0].cost));
            if(time < now){
            	list[0].state = "已理完";
                refreshList();
            }
        }
    }
    if(list[0].state == "等待中"){
        var time = new Date(list[0].time*1000);
        if(time < now){
            list[0].state = "理发中";
            refreshList();
        }
    }
	   
}

function getList(){
    
    $.post("?ctl=manager&act=getList", {
        
    }, function(data) {
        list = jQuery.parseJSON(data);
        for(var i=0; i<list.length; i++){
        	list[i].state = "等待中";    
        }
        if(list == ""){
        	$("#empty").text("（目前队伍中没有顾客！）");  
        }else{
            refreshList();
            window.setInterval(check, 10000);
        }
    });   
}

function refreshList(){
	
	var temp = "";
	temp += "<tr height=40><td width=\"10%\">编号</td><td width=\"30%\">时间</td><td width=\"15%\">姓名</td><td width=\"25%\">电话</td><td width=\"20%\">状态</td>";
    for(var i=0; i<list.length; i++){
        var time = new Date(list[i].time*1000);
        strTime = time.toLocaleDateString()+""+time.toLocaleTimeString();
        strTime = strTime.substring(0,strTime.length-3)
        temp += "<tr id=\"" + list[i].bookid + "\" height=35>" + "<td>" + (i+1) + "</td>";
        temp += "<td>" + strTime +　"</td>" + "<td>" + list[i].name + "</td>" + "<td>" + list[i].tel + "</td>";
        temp += "<td>" + list[i].state + "</td></tr>";
    }
    $("#list").html(temp);
    
    var tempStyle="";
    $.post("?ctl=manager&act=getStyle", {
        styleId: list[0].styleid
    }, function(data){
        var style = jQuery.parseJSON(data);
        tempStyle += "<img src=\"?ctl=manager&act=getStylePic&styleId="+ style.styleid + "\" style=\"float:left; height:120px; width:120px\">";
        tempStyle += "<label>发型价格：" + style.price + "元</label><br/><label>大体用时：" + style.time + "分</label><br/><label>发型简介：" + style.info + "</label>";
        $("#style").html(tempStyle);
    });
    
    var tempBook="";
    $.post("?ctl=manager&act=getBook", {
        bookId: list[0].bookid
    }, function(data){
        var time = new Date(list[0].time*1000);
        strTime = time.toLocaleDateString()+""+time.toLocaleTimeString();
        strTime = strTime.substring(0,strTime.length-3)
        var book = jQuery.parseJSON(data);
        tempBook += "<label>顾客姓名：" + book.name + "</label><br/><label>顾客电话：" + book.tel + "</label><br/>";
        tempBook += "<label>预约时间：" + book.time + "</label><br/><label>理发时间：" + strTime + "</label><br/><label>顾客留言：" + book.message + "</label>";
        $("#book").html(tempBook);
    });
    Detail();
}