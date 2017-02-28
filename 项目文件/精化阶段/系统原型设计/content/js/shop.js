    
function check(){
 	   
    if($("#owner").val() ==""){
        $("#errorOwner").text("请输入店铺联系人姓名！");
        return false;
    }else{
        $("#errorOwner").text("");
    }
        
    if($("#tel").val() ==""){
        $("#errorTel").text("请输入店铺电话！");
        return false;
    }else{
        $("#errorTel").text("");
    }
        
    if($("#address").val() ==""){
        $("#errorAddress").text("请输入店铺地址！");
        return false;
    }else{
        $("#errorAddress").text("");
    }
        
    $.post("?ctl=manager&act=updateShopInfo", {
        owner: $("#owner").val(),
        tel: $("#tel").val(),
        address: $("#address").val()
    }, function(data){
        if(data){
        	$("#success").text("修改成功");
    	}
    });
    
}

function updateInfo(){
    
    $("#owner").removeAttr("disabled");
    $("#tel").removeAttr("disabled");
    $("#address").removeAttr("disabled");
    
    $("#updateInfo").css("display","none");
    $("#submit").css("display","block");
    return false;
}
