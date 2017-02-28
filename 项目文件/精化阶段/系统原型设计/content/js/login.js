function login(){
            
    if($("#username").val() == ""){
        $("#errorMessage1").text("请输入用户名！");
        return false;
    }else{
    	$("#errorMessage1").text("");
    }
         
    if($("#password").val() == ""){
        $("#errorMessage2").text("请输入密码！");
        return false;
    }else{
    	$("#errorMessage2").text("");
    }
         
    $.post("?ctl=manager&act=login", {
        username:$("#username").val(),
        password:$("#password").val()
    }, function (data) {
    	if(data){   
    		$("#errorMessage2").text("");
    		window.location.href = "?ctl=manager&act=shop";
    	}else{
            $("#errorMessage2").text("用户名或密码错误！");
            return false;
    	}
    });
}

function keydownEvent() {
    var e = window.event || arguments.callee.caller.arguments[0];
    if (e && e.keyCode == 13 ) {
        login();
    }
}