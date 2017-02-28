<?php

 class MsgModel extends Model
 {
    var $theModel;
	var $table = "guesttable";
    var $bookTable = "booktable";
    var $evaluationTable = "evaluationtable";
    var $tokenTable = "accesstoken";
    var $shopTable = "shoptable";
    var $listTable = "listtable";
 	
    function MsgModel(){
        parent :: Model();
        
    }
    
    function handleSubscribe($guestInfo){
		//用户关注后将openid添加到数据库
		$result = $this->insert($this->table, $guestInfo);
 		return $result;
	}
	
	function handleUnsubscribe($guestOpenid){
		//用户取消关注后将openid从数据库中删除
		$result = $this->delete($this->table, "openid = ".$guestOpenid);
 		return $result;
	}
     
    function handleLocation($location, $guestOpenid){
		//用户发来位置信息后更新数据库
		$result = $this->update($this->table, $location, "openid", $guestOpenid);
 		return $result;
	}
     /*
	function handleLocationLng($lng, $guestOpenid){
		//用户发来位置信息后更新数据库
		$result = $this->update($this->table, "lng", $lng, "openid", $guestOpenid);
 		return $result;
	}
    function handleLocationLat($lat, $guestOpenid){
		//用户发来位置信息后更新数据库
		$result = $this->update($this->table, "lat", $lat, "openid", $guestOpenid);
 		return $result;
	}*/
    
    function getLastGuestId(){
 		//得到最新的客户id
 		$result = $this->getAll($this->table, " 1 order by guestid desc limit 0,1");
 		return $result["data"][0]["guestid"];
 	}
     
    function addGuestIdByOpenid($openid, $guestId){
        //添加客户id
        $data["guestid"] = $guestId;
        $result = $this->update($this->table, $data, "openid", $openid);
        return $result;
         
    } 
     
    function getBookByBookId($bookId){
         
    	$result = $this->getOne($this->bookTable, "bookid", $bookId);
        return $result;
    }     
     
    function getShopByShopId($shopId){
    	
        $result = $this->getOne($this->shopTable, "shopid", $shopId);
        return $result;
    }
     
    function getGuestIdByEvaluationId($evaluationId){
    	
        $result = $this->getOne($this->evaluationTable, "evaluationid", $evaluationId);
        return $result["guestid"];
         
    }
     
    function getOpenidByGuestId($guestId){
    	
        $result = $this->getOne($this->table, "guestid", $guestId);
        return $result["openid"];
    }
     
    function getLast(){
   		
        $result = $this->getOne($this->tokenTable, "id", "1");
        return $result;
    }
     
    function updateToken($info){
    	
        $result = $this->update($this->tokenTable, $info, "id", "1");
        return $result;
    }
     
    function getListByShopId($shopId){
         
        $result = $this->getAll($this->listTable, "shopid = ".$shopId);
        return $result;
    }
     
    function updateListItem($bookId, $info){
    	
        $result = $this->update($this->listTable, $info, "bookid", $bookId);
        return $result;
    }
     
    function deleteListItem($bookId){
    	
        $result = $this->delete($this->listTable, "bookid = ".$bookId);
        return $result;
    }
 }

?>