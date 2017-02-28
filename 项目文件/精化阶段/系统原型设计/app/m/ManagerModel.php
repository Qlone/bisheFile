<?php

 class ManagerModel extends Model
 {
    var $theModel;
 	var $shopTable = "shoptable";
 	var $guestTable = "guesttable";
 	var $bookTable = "booktable";
 	var $styleTable = "styletable";
 	var $evaluationTable = "evaluationtable";
    var $listTable = "listtable";
 	
    function ManagerModel(){
        parent :: Model();
        
    }
	
	function getPasswordByUsername($username){
        //通过用户账户名得到密码
		$result = $this->getOne($this->shopTable, "username", $username);
 		return $result["password"]; 		
	}
	
	function getShopByUsername($username){
        //通过用户账户名得到店铺信息
		$result = $this->getOne($this->shopTable, "username", $username);
		return $result;
	}
	
    function clearListByShopId($shopId){
   		
        $time = time();
        return $this->delete($this->listTable, "time < ".$time);
        
        return $result;
    }
     
	function getShopByShopId($shopId){
        //通过店铺id得到店铺信息
		$result = $this->getOne($this->shopTable, "shopid", $shopId);
		return $result;
	}
	
	function addShop($shopInfo){
        //添加店铺
		$result = $this->insert($this->shopTable, $shopInfo);
		return $result;
	}
     
    function addStyle($styleInfo){
        //添加样式
        $result = $this->insert($this->styleTable, $styleInfo);
		return $result;
    }
     
	function updateShopInfoByShopId($shopInfo, $shopId){
		//通过店铺id更新店铺信息
		$result = $this->update($this->shopTable, $shopInfo, "shopid", $shopId);
		return $result;
	}
     
    function getShopPicByShopId($shopId){
        //通过店铺id得到店铺图片
        $result = $this->getOne($this->shopTable, "shopid", $shopId);
        return $result["pic"];
    }
     
    function getStylePicByStyleId($styleId){
        //通过样式id得到样式图片
        $result = $this->getOne($this->styleTable, "styleid", $styleId);
        return $result["pic"];
    }
     
    function getStyleByStyleId($styleId){
    	
        $result = $this->getOne($this->styleTable, "styleid", $styleId);
        return $result;
    }
     
    function getStylesByShopId($shopId, $page, $pagesize){
        //通过店铺id得到店铺内样式
       	$result = $this->getAll($this->styleTable, "shopid = ".$shopId, $page, $pagesize);
        return $result;
    }
	
	function getBooksByShopId($shopId, $page, $pagesize){
        //通过店铺id得到店铺内预约
		$result = $this->getAll($this->bookTable, "shopid =".$shopId." order by time desc ", $page, $pagesize);
		return $result;
	}
     
    function getBookByBookId($bookId){
         
    	$result = $this->getOne($this->bookTable, "bookid", $bookId);
        return $result;
    }
     
    function updateBookStateByBookId($data, $bookId){
        //通过预约id更改预约状态
        $result = $this->update($this->bookTable, $data, "bookid", $bookId);
        return $result;
    }
	
	function getEvaluationsByShopId($shopId, $page, $pagesize){
        //通过店铺id得到店铺内评论
		$result = $this->getAll($this->evaluationTable, "shopid = ".$shopId, $page, $pagesize);
		return $result;
	}
	
	function updateEvaluationByEvaluationId($evaluationInfo, $evaluationId){
        //通过评论id修改评论内容
		$result = $this->update($this->evaluationTable, $evaluationInfo, "evaluationid", $evaluationId);
		return $result;
	}
	
	function getLastShopId(){
        //得到最新的店铺id
		$result = $this->getAll($this->shopTable, " 1 order by shopid desc limit 0,1");
 		return $result["data"][0]["shopid"];
	}
     
    function getLastStyleId(){
        //得到最新的样式id
       	$result = $this->getAll($this->styleTable, " 1 order by styleid desc limit 0,1");
 		return $result["data"][0]["styleid"];
    }
     
    function updateStyleByStyleId($data, $styleId){
    	
        $result = $this->update($this->styleTable, $data, "styleid", $styleId);
        return $result;
    }
     
    function deleteStyleByStyleId($styleId){
    	
        $result = $this->delete($this->styleTable, "styleid = ".$styleId);
        return $result;
    }
     
    function getListByShopId($shopId){
    	$result = $this->getAll($this->listTable, "shopid = ".$shopId." order by time");
        return $result;
    }
     
    function addItemToList($info){
         
    	$result = $this->insert($this->listTable, $info);
        return $result;
    }
     
    function getLastItem($shopId, $date){
    	
        $result = $this->getAll($this->listTable, "shopid = ".$shopId." and date = '".$date."' order by time desc limit 0,1");
        return $result["data"][0];
    }
     
    function updateList($bookId, $data){
    	
        $result = $this->update($this->listTable, $data, "bookid", $bookId);
        return $result;
    }
     
    function getNewsByShopId($shopId){
    	
        $book = $this->getAll($this->bookTable, "shopid = ".$shopId." and state = ''");
        $evaluation = $this->getAll($this->evaluationTable, "shopid = ".$shopId." and respon = ''");
        $news["book"] = $book["count"];
        $news["evaluation"] = $evaluation["count"];
        return $news;
    }
	
 }
 
?>
