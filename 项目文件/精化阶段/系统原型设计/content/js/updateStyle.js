function changeStyle(){
    
    $("#price").removeAttr("disabled");
    $("#time").removeAttr("disabled");
    $("#info").removeAttr("disabled");
    
    $("#submit").css("display","block");
    return false;
}

function deleteStyle(styleId){
    
    if(confirm("确认删除该样式吗？")){
    	window.location.href="?ctl=manager&act=deleteStyle&styleId="+styleId;    
    }
    
}

function check(styleId){
    
 	if($("#price").val() == ""){
        $("#errorPrice").text("请输入价格！");
        return false;
    }else{
        $("#errorPrice").text("");
    }
    
    if($("#time").val() == ""){
        $("#errorTime").text("请输入所用时间！");
        return false;
    }else{
        $("#errorTime").text("");
    }
    
    if($("#info").val() == ""){
        $("#errorInfo").text("请填写简介！");
        return false;
    }else{
        $("#errorInfo").text("");
    }
    
    $.post("?ctl=manager&act=updateStyle",{
        styleId: styleId,
        price: $("#price").val(),
        time: $("#time").val(),
        info:$("#info").val()
    }, function(data) {
        if(data){
            $("#success").text("修改成功！");
            $("#failed").text("");
        }else{
            $("#success").text("");
            $("#failed").text("修改失败！");
        }
    });
            
}
