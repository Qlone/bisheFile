function check(){
    
    if($("#picture").val() == ""){
        $("#errorPicture").text("请上传样式图片！");
        return false;
    }else{
        $("#errorPicture").text("");
    }
    
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
    
    $("#form").submit();
    
}