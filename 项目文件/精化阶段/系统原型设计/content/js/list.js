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

function upList(id){

    if(id != 0){
        for(var i=0; i<list.length; i++){
            if(i == id-1){
                var temp = list[i];
                list[i] = list[i+1];
                list[i].time = temp.time;
                list[i+1] = temp;
                list[i+1].time = parseInt(list[i].time) + 60*list[i].cost;
                list[i].state = "changed";
                list[i+1].state = "changed";
            }        
        }
        refreshList();
    }
}

function downList(id){
    
    if(i+1 != list.length){
        for(var i=0; i<list.length; i++){
            if(i == id){
                var temp = list[i+1];
                temp.time = list[i].time;
                list[i+1] = list[i];
                list[i] = temp;
                list[i+1].time = parseInt(list[i].time) + 60*list[i].cost;
                list[i].state = "changed";
                list[i+1].state = "changed";
            }
        }
    	refreshList();
    }
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

function getList(){
    
    $.post("?ctl=manager&act=getList", {
        
    }, function(data) {
        list = jQuery.parseJSON(data);
        refreshList();
        for(var i=0; i<list.length; i++){
         	   
        }
    });       
   
}

function submitList(){
	
    $.post("?ctl=manager&act=updateList", {
        list: list
    }, function(data) {
        if(data){
            $.post("?ctl=msg&act=confirmTime", {
                shopId: list[0].shopid
            }, function(data) {
                
                if(data){
                    $("#success").text("操作成功！");
                }else{
                    $("#failed").text("操作失败！");
                }
            });
        }else{
            
            $("#failed").text("操作失败！");
            return false;
        }
    });
    
    
}

function refreshList(){
	
	var temp = "";
	temp += "<tr height=40><td width=\"10%\">编号</td><td width=\"25%\">时间</td><td width=\"15%\">姓名</td><td width=\"25%\">电话</td><td width=\"25%\">操作</td></tr> ";
    for(var i=0; i<list.length; i++){
        var time = new Date(list[i].time*1000);
        strTime = time.toLocaleDateString()+""+time.toLocaleTimeString();
        strTime = strTime.substring(0,strTime.length-3)
        temp += "<tr id=\"" + list[i].bookid + "\" height=35>" + "<td>" + (i+1) + "</td>";
        temp += "<td>" + strTime +　"</td>" + "<td>" + list[i].name + "</td>" + "<td>" + list[i].tel + "</td>";
        temp += "<td>" + "<img src=\"./content/images/icons-png/carat-u-white.png\" onclick=\"upList('" + i + "')\"/> ";
        temp += "<img src=\"./content/images/icons-png/carat-d-white.png\" onclick=\"downList('" + i+ "')\"/>";
        temp += "<img src=\"./content/images/icons-png/delete-white.png\" onclick=\"deleteList('" + i + "')\"/></td></tr>";
    }
    $("#table").html(temp);
    Detail();
}