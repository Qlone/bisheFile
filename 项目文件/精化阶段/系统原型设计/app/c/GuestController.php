<?php

 class GuestController extends Controller
 {
 	var $theGuest;
    var $pagesize = 8;
 	function GuestController(){
 		parent :: Controller();
 		$this->theGuest = $this->getModel("Guest");
 	}
 	
 	function index(){
        
        Session_start();
        $_SESSION["guestId"] = "00000010";
        $data = $this->theGuest->getStylesByShopId("00006", 1, $this->pagesize);
        $this->view->assign('shopId', "00006");
 		$this->view->assign('data', $data["data"]);
        $this->view->assign('count', ceil(intval($data['count'])/$this->pagesize));
 		$this->view->assign('data', $data['data']);
        $this->view->display("guest/test.html");
        //$this->view->display("guest/test.php");
        
 	}
 	
    function chooseShopFromAll(){
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $guest = $this->theGuest->getGuestByGuestId($guestId);
        $data = $this->theGuest->getAllShops(1, $this->pagesize);
        $shop = $data["data"];
        foreach($shop as $key => $value){
            $a = abs($guest["lng"] - $value["lng"]);
            $b = abs($guest["lat"] - $value["lat"]) * cos($guest["lat"]);
           
        	$shop[$key]["distance"] = sprintf("%.2f", 111 * sqrt($a*$a + $b*$b));
        }
        $this->view->assign('count', ceil(intval($data['count'])/$this->pagesize));
 		$this->view->assign('data', $shop);
 		$this->view->display("guest/chooseShopFromAll.html");
    }
     
    function getMoreShopsForAll(){
        Session_start();
        $page = $_POST["page"];
        $guestId = $_SESSION["guestId"];
        $guest = $this->theGuest->getGuestByGuestId($guestId);
        $data = $this->theGuest->getAllShops($page, $this->pagesize);
        $shop = $data["data"];
        foreach($shop as $key => $value){
            $a = abs($guest["lng"] - $value["lng"]);
            $b = abs($guest["lat"] - $value["lat"]) * cos($guest["lat"]);
           
        	$shop[$key]["distance"] = sprintf("%.2f", 111 * sqrt($a*$a + $b*$b));
        }
        echo json_encode($shop);
    }
     
 	function chooseShop(){
 		//选择店铺
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $data = $this->theGuest->getAvailableShopsByGuestId($guestId, 1, $this->pagesize);
        $guest = $this->theGuest->getGuestByGuestId($guestId);
        $shop = $data["data"];
        foreach($shop as $key => $value){
            $a = abs($guest["lng"] - $value["lng"]);
            $b = abs($guest["lat"] - $value["lat"]) * cos($guest["lat"]);
           
        	$shop[$key]["distance"] = sprintf("%.2f", 111 * sqrt($a*$a + $b*$b));
        }
        $this->view->assign('count', ceil(intval($data['count'])/$this->pagesize));
 		$this->view->assign('data', $shop);
 		$this->view->display("guest/chooseShop.html");
       
 	}
     
    function getMoreShops(){
    	Session_start();
        $guestId = $_SESSION["guestId"];
        $page = $_POST["page"];
        $guest = $this->theGuest->getGuestByGuestId($guestId);
        $data = $this->theGuest->getAvailableShopsByGuestId($guestId, $page, $this->pagesize);
        $shop = $data["data"];
        foreach($shop as $key => $value){
            $a = abs($guest["lng"] - $value["lng"]);
            $b = abs($guest["lat"] - $value["lat"]) * cos($guest["lat"]);
           
        	$shop[$key]["distance"] = sprintf("%.2f", 111 * sqrt($a*$a + $b*$b));
        }
        echo json_encode($shop);
    }
     
    function getShopPic(){
        
		$shopId = $_GET["shopId"];
        
        $pic = $this->theGuest->getShopPicByShopId($shopId);
        echo $pic;     
    }
 	
 	function chooseStyle(){
 		//选择样式
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $shopId = $_GET["shopId"];
 		$data = $this->theGuest->getStylesByShopId($shopId, 1, $this->pagesize);
        $this->view->assign('shopId', $shopId);
        $this->view->assign('count', ceil(intval($data['count'])/$this->pagesize));
 		$this->view->assign('data', $data['data']);
 		$this->view->display("guest/chooseStyle.html");
 	}
     
    function getMoreStyles(){
    	$page = $_POST["page"];
        $shopId = $_POST["shopId"];
        $data = $this->theGuest->getStylesByShopId($shopId, $page, $this->pagesize);
        echo json_encode($data["data"]);
    }
     
    function getStylePic(){
        //得到样式图片
        $styleId = $_GET["styleId"];
        
        $pic = $this->theGuest->getStylePicByStyleId($styleId);
        echo $pic;
    }
 	
    function visitShop(){
    	Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $shopId = $_GET["shopId"]; 
        $data = $this->theGuest->getShopByShopId($shopId);
        $evaluation = $this->theGuest->getAllEvaluationsByShopId($shopId, 1, $this->pagesize);
        $this->view->assign('shopId', $shopId);
 		$this->view->assign('data', $data);
        $this->view->assign('count', ceil(intval($evaluation['count'])/$this->pagesize));
        $this->view->assign('evaluation', $evaluation["data"]);
 		$this->view->display("guest/shopDetail.html");
    }
     
    function visitBook(){
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $bookId = $_GET["bookId"]; 
        $data = $this->theGuest->getDetailsByBookId($bookId);
        $data["shopname"] = $this->theGuest->getShopNameByShopId($data["shopid"]);
        $this->view->assign('bookId', $bookId);
 		$this->view->assign('data', $data);
 		$this->view->display("guest/bookDetail.html");
    }
     
 	function detail(){
 		//查看细节
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $styleId = $_GET["styleId"];
  		$data = $this->theGuest->getDetailsByStyleId($styleId);
 		$this->view->assign('data', $data);
 		$this->view->display("guest/detail.html");
 	}
 	
 	function toBook(){
 		//预约
 		Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
		$guestId = $_SESSION["guestId"];
        $styleId = $_GET["styleId"];
 		$data = $this->theGuest->getDetailsByStyleId($styleId);
 		$this->view->assign('data', $data);
 		$this->view->display("guest/book.html");
 	}
 	
 	function book(){
 		//判断预约结果
        Session_start();
 		if(empty($_POST)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
 		
        $lastId = $this->theGuest->getLastBookId();
        if(!empty($lastId)){
           	$lastBookId = intval($lastId);
            $bookId = sprintf("%010d",$lastBookId+1);
        }else{
           	$bookId = "0000000000";   
        }
            
        $data = $_POST["data"];
            
        $data["bookid"] = $bookId;
        $data["guestid"] = $guestId;
        $this->theGuest->addBook($data);
 		$this->view->display("guest/bookSucceed.html");
 	}
 	
 	function bookList(){
 		//预约列表
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $data = $this->theGuest->getAllBooksByGuestId($guestId);
        foreach($data["data"] as $key => $item){
        	$book[$key]["bookid"] = $item["bookid"];
            $book[$key]["time"] = $item["time"];
            $book[$key]["name"] = $this->theGuest->getShopNameByShopId($item["shopid"]);
        }
        $this->view->assign('data', $book);
        $this->view->display("guest/bookList.html");
 	}
 	 	
 	function toEvaluate(){
 		//评价
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $bookId = $_GET["bookId"];
 		$evaluation = $this->theGuest->getEvaluation($bookId);
        $book = $this->theGuest->getDetailsByBookId($bookId);
        if($evaluation["stars"] == 0){
            if($book["state"] == ""){
                $data = "店家还未答复你的预约<br/>暂时无法评价";
                $this->view->assign('data', $data);
                $this->view->display("guest/evaluate.html");
            }else{
                $data = $this->theGuest->getDetailsByBookId($bookId);
                $this->view->assign('data', $data);
                $this->view->display("guest/toEvaluate.html");
            }
        }else{
            $data = "你已经对此次预约进行了评价<br/>请不要重复评价";
 			$this->view->assign('data', $data);
 			$this->view->display("guest/evaluate.html");
        }
 	}
 	
 	function evaluate(){
 		//判断评价结果
 		if(empty($_POST)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        
        $lastId = $this->theGuest->getLastEvaluationId();
        if(!empty($lastId)){
           	$lastEvaluationId = intval($lastId);
            $evaluationId = sprintf("%010d",$lastEvaluationId+1);
        }else{
           	$evaluationId = "0000000000";   
        }
        $data = $_POST["data"];
        $data["evaluationid"] = $evaluationId;
        $result = $this->theGuest->getAllEvaluationsByShopId($data["shopid"]);
        $shop = $this->theGuest->getShopByShopId($data["shopid"]);
        $star["stars"] = ($shop["stars"] * $result["count"] + $data["stars"])/($result["count"] + 1);
        $this->theGuest->updateShopByShopId($star, $data["shopid"]);
        
 		$result = $this->theGuest->addEvaluation($data);
 		if($result){
 			$data = "评价成功！感谢光临！";
 		}else{
 			$data = "评价失败！";
 		}
 		$this->view->assign('data', $data);
 		$this->view->display("guest/evaluate.html");
 	}
     
    function about(){
    	$this->view->display("guest/about.html");
    }
 	
 	function route(){
 		//导航
        Session_start();
        if(empty($_SESSION)){
 			$this->view->display("guest/error.html");
 		}
        $guestId = $_SESSION["guestId"];
        $shopId = $_GET["shopId"];
 		$guest = $this->theGuest->getGuestByGuestId($guestId);
 		$shop = $this->theGuest->getShopByShopId($shopId);
 		$this->view->assign('fromlng', $guest["lng"]);
 		$this->view->assign('fromlat', $guest["lat"]);
 		$this->view->assign('tolng', $shop["lng"]);
 		$this->view->assign('tolat', $shop["lat"]);
 		$this->view->display("guest/route.html");
 	}
     
    function getStyles(){
    	$page = $_GET["page"];
        $shopId = $_GET["shopId"];
        $data = $this->theGuest->getStylesByShopId($shopId, $page, $this->pagesize); 
        $temp = "<div id='styles'>";
        foreach($data["data"] as $key => $item){
            $temp = $temp."<div class=\"box\" role=\"main\" onclick=\"this.style.backgroundColor='#B0C4DE'; location.href='?ctl=guest&act=detail&styleId=".$item["styleid"]."'\">";
            $temp = $temp."<img src=\"?ctl=guest&act=getStylePic&styleId=".$item['styleid']."\">";
            $temp = $temp."<li><b>".$item["time"]."&nbsp;分钟；RMB&nbsp;". $item["price"]."元</b></li></div>";
        }
        $temp = $temp."</div>";
        echo $temp;
    }
 }
?>
