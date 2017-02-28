function check(){
    
 	var password = $("#password").val();
    var newpassword = $("#newpassword").val();
    var renewpassword = $("#renewpassword").val();
    
    if(password == ""){
        $("#errorPassword").text("请输入旧密码！");
        return false;
    }else{
        $("#errorPassword").text("");
    }
    
    if(newpassword == ""){
        $("#errorNewpassword").text("请输入新密码！");
        return false;
    }else{
        if(newpassword.length < 6 || newpassword.length > 12){
            $("#errorNewpassword").text("密码长度为6-12位");
            return false;
        }else{
        	$("#errorNewpassword").text("");
        }
    }
    
    if(renewpassword == ""){
        $("#errorRenewpassword").text("请确认新密码！");
        return false;
    }else{
        $("#errorRenewpassword").text("");
    }
    
    $.post("?ctl=manager&act=updatePassword", {
        password: password,
        newpassword: newpassword
    }, function (data) {
    	if(data){   
            $("#success").text("修改成功，请牢记新密码！");
    	}else{
            $("#errorPassword").text("密码错误！");
            $("#success").text("");
            return false;
    	}
    });
    
}