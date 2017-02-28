<?php

 class ManagerController extends Controller
 {
 	var $theManager;
    var $pagesize = 10;
 	function ManagerController(){
 		parent :: Controller();
 		$this->theManager = $this->getModel("Manager");
 	}
	
	function index(){
        //默认访问登录页面
        $this->view->display("manager/login.html");
        //$this->view->display("manager/location.html");
	}
     
	function login(){
        //验证密码是否正确
		$username = $_POST["username"];
		$password = $this->theManager->getPasswordByUsername($username);
		if($password == $_POST["password"]){
			$shop = $this->theManager->getShopByUsername($username);
			Session_start();
			$_SESSION["shopId"] = $shop["shopid"];
			echo true;
		}else{
			echo false;
		}		
	}
     
    function logout(){
        //注销登录
        Session_start();
        session_destroy();
        $this->view->display("manager/login.html");
    }
     
    function home(){
         
    	Session_start();
		if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
		$shopId = $_SESSION["shopId"]; 
        
        $this->theManager->clearListByShopId($shopId);
        
        $this->view->display("manager/home.html");
    }
     
    function getStyle(){
         
    	$styleId = $_POST["styleId"];
        $result = $this->theManager->getStyleByStyleId($styleId);
        echo json_encode($result);
    }
	
	function shop(){
        //得到店铺信息，访问主页
        Session_start();
		if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
		$shopId = $_SESSION["shopId"];
		
		$data = $this->theManager->getShopByShopId($shopId);
        
        if(empty($data["name"])){
            $this->view->display("manager/addShop.html");
        }else{
            if($data["lng"] == 0 && $data["lat"] == 0){
                $this->view->display("manager/location.html");
            }else{
                $this->view->assign("data", $data);
                $this->view->display("manager/shop.html");
            }
        }
	}
     
    function updateShopInfo(){
    	
        Session_start();
		$shopId = $_SESSION["shopId"];
        $data["owner"] = $_POST["owner"];
        $data["tel"] = $_POST["tel"];
        $data["address"] = $_POST["address"];
        $result = $this->theManager->updateShopInfoByShopId($data, $shopId);
        echo $result;
    }
	
	function checkin(){
        //跳转到注册页面
		$this->view->display("manager/checkin.html");
	}
     
    function checkUsername(){
        //核对用户名是否被使用
        $username = $_POST["username"];
        $result = $this->theManager->getShopByUsername($username);
        if(empty($result)){
            echo true;
        }else{
            echo false;
        }
         
    }
	
	function addShop(){
        //将用户账号密码店铺id存入数据库，跳转到完善信息 
		if(empty($_POST)){
			$this->view->display("manager/error.html");
            die;
		}
		$shopInfo["username"] = $_POST["username"];
		$shopInfo["password"] = $_POST["password"];
		$lastId = $this->theManager->getLastShopId();
		if(!empty($lastId)){
			$lastShopId = intval($lastId);
			$shopId = sprintf("%05d",$lastShopId+1);
		}else{
			$shopId = "00000";
		}
		$shopInfo["shopid"] = $shopId;
		
		$this->theManager->addShop($shopInfo);
		
		Session_start();
		$_SESSION["shopId"] = $shopId;
		
		$this->view->display("manager/addShop.html");
	}
	
	function toHome(){
        //将信息完善存入数据库，跳转到主页面
		if(empty($_POST)){
			$this->view->display("manager/error.html");
            die;
		}
		Session_start();
		$shopId = $_SESSION["shopId"];
        $data = $_POST["shop"];
        
        $file = $_FILES['file'];
        $picture = addslashes(fread(fopen($file['tmp_name'], "r"), $file['size']));
        
        $data['pic'] = $picture;
        
		$result = $this->theManager->updateShopInfoByShopId($data, $shopId);
		if($result){
			$this->view->display("manager/location.html");
		}else{
			$this->view->display("manager/error.html");
		}
	}
     
    function addShopStyle(){
        //添加样式到数据库，跳转页面
        if(empty($_POST)){
			$this->view->display("manager/error.html");
            die;
		}
		Session_start();
		$shopId = $_SESSION["shopId"];
        $data = $_POST["data"];
        $data["shopid"] = $shopId;
        
        $file = $_FILES['file'];
        $picture = addslashes(fread(fopen($file['tmp_name'], "r"), $file['size']));
        
        $data["pic"] = $picture;
        
        $lastId = $this->theManager->getLastStyleId();
		if(!empty($lastId)){
			$lastStyleId = intval($lastId);
			$styleId = sprintf("%05d",$lastStyleId+1);
		}else{
			$styleId = "00000";
		}
        
        $data["styleid"] = $styleId;
        
        $result = $this->theManager->addStyle($data);
        
        $this->view->assign("message", "success");
        $this->view->assign("data", $data);
        $this->view->display("manager/updateStyle.html");
         
    }
     
    function submitLocation(){
    	if(empty($_POST)){
			$this->view->display("manager/error.html");
            die;
		}  
        Session_start();
		$shopId = $_SESSION["shopId"];
        $data = $_POST["data"];
        
        $this->theManager->updateShopInfoByShopId($data, $shopId);
        $data = $this->theManager->getShopByShopId($shopId);
		$this->view->assign("data", $data);
		$this->view->display("manager/home.html");
    }
     
    function updateStyle(){
        
        $styleId = $_POST["styleId"];
        $data["price"] = $_POST["price"];
        $data["time"] = $_POST["time"];
        $data["info"] = $_POST["info"];
        $result = $this->theManager->updateStyleByStyleId($data, $styleId);
        
        echo $result;
    }
     
    function deleteStyle(){
        Session_start();
        if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
        $styleId = $_GET["styleId"];
        $result = $this->theManager->deleteStyleByStyleId($styleId);
        if($result){
            $this->view->display("manager/deleteStyleSuccess.html");
        }
    }
     
    function visitStyle(){
    	Session_start();
        if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
        $styleId = $_GET["styleId"];
        $data = $this->theManager->getStyleByStyleId($styleId);
        $this->view->assign("data", $data);
        $this->view->display("manager/updateStyle.html");
    }
     
    function getShopPic(){
        //得到店铺图片
        Session_start();
		$shopId = $_SESSION["shopId"];
        
        $pic = $this->theManager->getShopPicByShopId($shopId);
        echo $pic;
    }
     
    function getStylePic(){
        //得到样式图片
        $styleId = $_GET["styleId"];
        
        $pic = $this->theManager->getStylePicByStyleId($styleId);
        echo $pic;
    }
     
    function security(){
        //跳转到安全中心界面
        Session_start();
        if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
        $this->view->display("manager/security.html");
    }
     
    function updatePassword(){
        //修改密码
        Session_start();
        if(empty($_SESSION)){
			echo false;
		}
        $password = $_POST["password"];
        $newPassword = $_POST["newpassword"];
        $shopId = $_SESSION["shopId"];
        $shop = $this->theManager->getShopByShopId($shopId);
        if($shop["password"] ==$password){
            $shopInfo['password'] = $newPassword;
            $result = $this->theManager->updateShopInfoByShopId($shopInfo, $shopId);
            echo $result;
        }else{
         	echo false;   
        }
    }
     
    function handleStyles(){
        //跳转到处理样式界面
        Session_start();
        if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
        $shopId = $_SESSION["shopId"];
        $data = $this->theManager->getStylesByShopId($shopId, 1, $this->pagesize);
        $this->view->assign("data", $data['data']);
		$this->view->display("manager/styles.html");
    }
     
    function getMoreStyles(){
         
    	Session_start();
        $shopId = $_SESSION["shopId"];
        $page = $_GET["page"];
        $data = $this->theManager->getStylesByShopId($shopId, $page, $this->pagesize);
        $temp = "<div id=\"styles\"";
        foreach($data["data"] as $key => $item){
        	$temp = $temp."<div id=\"box\"><li><a style=\"font-size:15px\">样式ID".$item['styleid']."</a>";
            $temp = $temp."<a href=\"?ctl=manager&act=visitStyle&styleId=".$item['styleid']."\" style=\"color:White; font-size:15px\">查看/修改</a></li>";
            $temp = $temp."<li style=\"margin-top:10px;\"><img class=\"img\" src=\"?ctl=manager&act=getStylePic&styleId=".$item['styleid']."\">";
            $temp = $temp."<pa>价格</pa><input type=\"text\" value=\"".$item['price']."&nbsp;元\" disabled><br/>";
            $temp = $temp."<pa>用时</pa><input type=\"text\" value=\"".$item['time']."&nbsp;分钟\" disabled><br/>";
            $temp = $temp."<pa style=\"vertical-align: top\">简介</pa><textarea disabled>".$item['info']."</textarea><br/></li><br/></div>";    
        }
        $temp = $temp."</div>";
        echo $temp;
    }
     
    function addStyle(){
        //跳转到添加样式页面
        Session_start();
        if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
        $this->view->display("manager/addStyle.html");
    }
     
    function handleList(){
        //跳转到处理队列界面
        Session_start();
        if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
        $shopId = $_SESSION["shopId"];
		$this->view->display("manager/list.html");
        
    }
     
    function getList(){
    	
        Session_start();
        $shopId = $_SESSION["shopId"];
        $list = $this->theManager->getListByShopId($shopId);
        echo json_encode($list["data"]);
    }
	
	function handleBooks(){
        //跳转到处理预约界面
        Session_start();
		if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
		$shopId = $_SESSION["shopId"];
		$data = $this->theManager->getBooksByShopId($shopId, 1, $this->pagesize);
		$this->view->assign("data", $data["data"]);
		$this->view->display("manager/books.html");
	}
     
    function getMoreBooks(){
    	
        Session_start();
        $shopId = $_SESSION["shopId"];
        $page = $_GET["page"];
        $data = $this->theManager->getBooksByShopId($shopId, $page, $this->pagesize);
        $temp = "<ul id=\"books\">";
        foreach($data["data"] as $key => $item){
            if($item['state'] != ''){
                $r = "已处理";
            }else{
                $r = "";
            }
            $temp = $temp."<div id=\"box\"><li style=\"margin-top:10px;\">";
            $temp = $temp."<img class=\"img\" src=\"?ctl=manager&act=getStylePic&styleId=".$item["styleid"]."\">";
            $temp = $temp."<div style=\"float:right; margin-right:5%\">";
            $temp = $temp."<com type=\"button\" onclick=\"accept('".$item['bookid']."')\" style=\"color:Green\">接受</com>";
            $temp = $temp."<label id=\"accept".$item['bookid']."\" style=\"color:Green\">".$r."</label>";
            $temp = $temp."<label id=\"refuse".$item['bookid']."\" style=\"color:Red\"></label><br/>";
            $temp = $temp."<com type=\"button\" onclick=\"refuse('".$item['bookid']."')\" style=\"color:Red\">拒绝</com></div>";
            $temp = $temp."<div><pa>时间</pa><input type=\"text\" value=\"".$item['time']."\" disabled><br/>";
            $temp = $temp."<pa>姓名</pa><input type=\"text\" value=\"".$item['name']."\" disabled><br/>";
            $temp = $temp."<pa>电话</pa><input type=\"text\" value=\"".$item['tel']."\" disabled><br/>";
            $temp = $temp."<pa style=\"vertical-align: top\">留言</pa><textarea disabled>".$item['message']."</textarea><br/></div></li><br/></div>";
        }
        $temp = $temp."</div>";
        echo $temp;
    }
	
    function updateBookState(){
        //更改预约状态
        Session_start();
        $shopId = $_SESSION["shopId"];
        $bookId = $_POST['bookId'];
        $state = $_POST['state'];
        $data['state'] = $state;
        $result = $this->theManager->updateBookStateByBookId($data, $bookId);
        if($state == "accept"){
            $book = $this->theManager->getBookByBookId($bookId);
            $style = $this->theManager->getStyleByStyleId($book["styleid"]);
            $list["bookid"] = $book["bookid"];
            $list["guestid"] = $book["guestid"];
            $list["shopid"] = $book["shopid"];
            $list["styleid"] = $book["styleid"];
            $list["name"] = $book["name"];
            $list["date"] = $book["time"];
            $list["tel"] = $book["tel"];
            $list["cost"] = $style["time"];
            $list["state"] = "unconfirmed";
            $lastItem = $this->theManager->getLastItem($shopId, $book["time"]);
            if(empty($lastItem)){
                $list["time"] = strtotime($list["date"])+36000; 
            }else{
                $lastItemStyle = $this->theManager->getStyleByStyleId($lastItem["styleid"]);
                $list["time"] = $lastItem["time"]+60*$lastItemStyle["time"]; 
            }
            $result = $this->theManager->addItemToList($list);
        }
        echo $result;
    }
     
    function updateList(){
        if(empty($_POST)){
            echo false;
        }else{
            
            $list = $_POST["list"];
            foreach($list as $key => $item){
                $bookId = $item["bookid"];
                if($item["state"] == "unconfirmed"){
                 	$item["state"] = "confirmed";   
                }else{
                	if($item["state"] == "confirmed"){
                    	$item["state"] = "already";
                    }
                }
                $temp["time"] = $item["time"];
                $temp["state"] = $item["state"];
                $result = $this->theManager->updateList($bookId, $temp);
            }  
            echo $result;
        }
    }
     
	function handleEvaluations(){
        //跳转到处理评论页面
        Session_start();
		if(empty($_SESSION)){
			$this->view->display("manager/error.html");
            die;
		}
		$shopId = $_SESSION["shopId"];
		$data = $this->theManager->getEvaluationsByShopId($shopId, 1, $this->pagesize);
		$this->view->assign("data", $data["data"]);
		$this->view->display("manager/evaluations.html");
	}
     
    function getMoreEvaluations(){
    	
        Session_start();
        $shopId = $_SESSION["shopId"];
        $page = $_GET["page"];
        $data = $this->theManager->getEvaluationsByShopId($shopId, $page, $this->pagesize);
        $temp = "<ul id=\"evaluations\">";
        foreach($data["data"] as $key => $item){
            if($item["respon"] != ""){
            	$r = "回复成功！";    
            }else{
             	$r = "";   
            }
        	$temp = $temp."<div id=\"box\"><li style=\"margin-top:10px;\"><img class=\"img\"src=\"?ctl=manager&act=getStylePic&styleId=".$item["styleid"]."\">";
            $temp = $temp."<div style=\"float:right; margin-right:5%\">";
            $temp = $temp."<com id=\"res\" type=\"button\" onclick=\"respon('".$item['evaluationid']."')\" >回复</com>";
            $temp = $temp."<label id=\"resp".$item['evaluationid']."\" value=\"1\" style=\"color:Green\">".$r."</label></div>";
            $temp = $temp."<pa>时间</pa><input type=\"text\" value=\"".$item["time"]."\" disabled><br/>";
            $temp = $temp."<pa>姓名</pa><input type=\"text\" value=\"".$item["name"]."\" disabled><br/>";
            $temp = $temp."<pa>评论</pa><input type=\"text\" value=\"".$item["stars"]."&nbsp;颗星\" disabled><br/>";
            $temp = $temp."<pa style=\"vertical-align: top\">留言</pa><textarea disabled>".$item["message"]."</textarea><br/></li></div>";
        }
        $temp = $temp."</ul>";
        echo $temp;
    }
     
    function responEvaluation(){
         
    	$data['respon'] = $_POST["respon"];   
        $evaluationId = $_POST["evaluationId"];
        $result = $this->theManager->updateEvaluationByEvaluationId($data, $evaluationId);
        echo $result;
    }
     
    function getBook(){
         
    	$bookId = $_POST["bookId"];
        $result = $this->theManager->getBookByBookId($bookId);
        echo json_encode($result);
    }
     
    function getNews(){
    	
        Session_start();
        $shopId = $_SESSION["shopId"];
        $news = $this->theManager->getNewsByShopId($shopId);
        echo json_encode($news);
    }
	
 }
 
?>
