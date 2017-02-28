<?php

 class GuestModel extends Model
 {
    var $theModel;
 	var $shopTable = "shoptable";
 	var $guestTable = "guesttable";
 	var $bookTable = "booktable";
 	var $styleTable = "styletable";
 	var $evaluationTable = "evaluationtable";
 	
    function GuestModel(){
        parent :: Model();
        
    }
 	function getAvailableShopsByGuestId($guestId, $page, $pagesize){
 		//通过客户id得到所有符合条件的店铺
 		$guest = $this->getGuestByGuestId($guestId);
 		$lng = $guest["lng"];
 		$lat = $guest["lat"];
       
 		$where = "lng < ".($lng+0.1)." and lng > ".($lng-10.1)." and lat < ".($lat+0.1)." and lat > ".($lat-0.1)." order by stars desc";
 		$result = $this->getAll($this->shopTable, $where, $page, $pagesize);
 		return $result;
 	}
     /*
 	function getAvailableShopsByLocation($lng, $lat){
 		//通过位置得到所有符合条件的店铺
 		$where = "lng < ".$lng+0.1." and lng > ".$lng-0.1." and lat < ".$lat+0.1." and lat > ".$lat-0.1;
 		$result = $this->getAll($this->shopTable, $where);
 		return $result;
 	}
 	*/
 	function getAllShops($page, $pagesize){
 		//得到所有店铺
 		$result = $this->getAll($this->shopTable, "1 order by stars desc", $page, $pagesize);
 		return $result;
 	}
 	
 	function getGuestByGuestId($guestId){
 		//通过客户id得到客户信息
 		$result = $this->getOne($this->guestTable, "guestid", $guestId);
 		return $result; 		
 	}
 	
 	function getShopByShopId($shopId){
 		//通过店铺id得到店铺信息
 		$result = $this->getOne($this->shopTable, "shopid", $shopId);
 		return $result;
 	}
 	
 	function getStylesByShopId($shopId, $page, $pagesize){
 		//通过店铺id得到店铺中发型样式
 		$result = $this->getAll($this->styleTable, "shopid = ".$shopId, $page, $pagesize);
 		return $result;
 	}
 	
 	function getDetailsByStyleId($styleId){
 		//通过发型id得到发型细节
 		$result = $this->getOne($this->styleTable, "styleid", $styleId);
 		return $result;
 	}
 	
 	function addBook($bookInfo){
 		//添加预约
 		$result = $this->insert($this->bookTable, $bookInfo);
 		return $result;
 	}
 	
 	function deleteBook($bookId){
 		//删除预约
 		$result = $this->delete($this->bookTable, "bookid = ".$bookId);
 		return $result;
 	}
 	
 	function getAllBooksByGuestId($guestId){
 		//通过客户id得到其全部预约
 		$where = "guestid = ".$guestId." order by time desc";
 		$result = $this->getAll($this->bookTable, $where);
 		return $result;
 	}
 	
 	function getDetailsByBookId($bookId){
 		//通过预约id得到预约细节
 		$result = $this->getOne($this->bookTable, "bookid", $bookId);
 		return $result;
 	}
     
    function getEvaluation($bookId){
    	$result = $this->getOne($this->evaluationTable, "bookid", $bookId);
 		return $result;     
    }
 	
 	function addEvaluation($evaluationInfo){
 		//添加评论
 		$result = $this->insert($this->evaluationTable, $evaluationInfo);
 		return $result;
 	}
     
    function getAllEvaluationsByShopId($shopId, $page, $pagesize){
    	$result = $this->getAll($this->evaluationTable, "shopid = ".$shopId, $page, $pagesize);
 		return $result;
    }
 	
    function getLastBookId(){
 		//得到最新的预约id
 		$result = $this->getAll($this->bookTable, " 1 order by bookid desc limit 0,1");
 		return $result['data'][0]["bookid"];
 	} 
     
    function getGuestIdByOpenid($openid){
        //通过客户openid得到客户id
 		$result = $this->getOne($this->guestTable, "openid", $openid);
 		return $result["guestid"];
 	}
     
    function getShopPicByShopId($shopId){
        //通过店铺id得到店铺图片
        $result = $this->getOne($this->shopTable, "shopid", $shopId);
        return $result["pic"];
    }
     
    function getStylePicByStyleId($styleId){
    
        $result = $this->getOne($this->styleTable, "styleid", $styleId);
        return $result["pic"];
    }
     
    function getLastEvaluationId(){
    	
        $result = $this->getAll($this->evaluationTable, " 1 order by evaluationid desc limit 0,1");
 		return $result['data'][0]["evaluationid"];
    }
     
    function updateShopByShopId($data, $shopId){
     	
        $result = $this->update($this->shopTable, $data, "shopid", $shopId);
        return $result;
    }
     
    function getShopNameByShopId($shopId){
    	
        $result = $this->getOne($this->shopTable, "shopid", $shopId);
        return $result["name"];
    }
    
 }
?>
