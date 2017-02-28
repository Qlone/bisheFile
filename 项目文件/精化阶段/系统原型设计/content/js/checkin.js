    function checkin(){
        
        var username = $("#username").val();
        var password = $("#password").val();
        var repassword = $("#repassword").val();
        
        if(username == ""){
        	$("#errorMessage1").text("请输入用户名！");
            return false;
        }else{
            if(username[0] > 'z' || username[0] < 'a' || username.length < 6 || username.length > 12){
                $("#errorMessage1").text("用户名需英文字母开头，且6-12位！");
                return false;
            }else{
                $("#errorMessage1").text("");
            }
        }
        
        if(password == ""){
        	$("#errorMessage2").text("请输入密码！");
            return false;
        }else{
            if(password.length < 6 || password.length > 12){
                $("#errorMessage2").text("密码长度为6-12位！");
                return false;
            }else{
                $("#errorMessage2").text("");
            }
        }
        
        if(repassword == ""){
            $("#errorMessag3").text("请再次输入密码！");
            return false;
        }else{
            if(password == repassword){
                $("#errorMessag3").text("");
            }else{
                $("#errorMessag3").text("两次密码不一致");
                return false;
            }
        }
        
        $.post("?ctl=manager&act=checkUsername", {
            username:username
        }, function (data) {
            if(data){
                $("#form").submit();
            }else{
                $("#errorMessage1").text("用户名已存在！");
                return false;
            }
        });
   	}
