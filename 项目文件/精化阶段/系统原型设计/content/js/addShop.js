
    function check(){
    	
        if($("#name").val() ==""){
            $("#errorName").text("请输入店铺名称！");
            return false;
        }else{
            $("#errorName").text("");
        }
        
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
        
        if($("#picture").val() ==""){
            $("#errorPicture").text("请上传店铺图片！");
            return false;
        }else{
            $("#errorPicture").text("");
        }
        
        $("#form").submit();
    }
    