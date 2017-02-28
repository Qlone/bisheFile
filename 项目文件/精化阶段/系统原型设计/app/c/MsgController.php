<?php

 class MsgController extends Controller
 {
 	var $theMsg;
    function MsgController(){
        parent :: Controller();
 		$this->theMsg = $this->getModel("Msg");
    }
 	
	public function responseMsg($postObj)
    {
        $RX_TYPE = trim($postObj->MsgType);

                switch($RX_TYPE)
                {
                    case "text":
                        $resultStr = $this->handleText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->handleEvent($postObj);
                        break;
                    default:
                        $resultStr = "Unknow msg type: ".$RX_TYPE;
                        break;
                }
                echo $resultStr;
       
    }
	
	public function handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $pic1Url="http://images-fast.digu.com/b37236f46da943b8aa3203f6eab34c7e0004.jpg";
        $pic2Url="http://img4.duitang.com/uploads/item/201110/24/20111024192143_NJN3K.thumb.600_0.jpg";
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        $newsTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[理发由你安排！]]></Title> 
                    <Description><![CDATA[picone]]></Description>
                    <PicUrl><![CDATA[http://img4.duitang.com/uploads/item/201110/24/20111024192143_NJN3K.thumb.600_0.jpg]]></PicUrl>
                    <Url><![CDATA[https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx037273ef859d63d5&redirect_uri=http://1.nealzh.sinaapp.com/OAuth2/BeforeCreateWay.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect]]></Url>
                    </item>
                    </Articles>
                    </xml>";
        if(!empty($keyword))
        {
            switch ($keyword)
            {   
                case "1":
                {
                    $msgType="news";
                    $resultStr=sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType);
                    return $resultStr;
                    break;
                }
                case "2":              
                {
                    $msgType = "text";
                    $contentStr = "程序员是个逗比，其实什么都没做！";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    break;
                }
                default:
                {
                    $msgType = "text";
                    $contentStr = "未识别的指令，回复“1”得到更多帮助。";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    break;
                }
            }
        }else{
            echo "Input something...";
        }
    }
	
	public function handleEvent($postObj)
    {
        
        switch ($postObj->Event)
        {
            case "subscribe"://将用户的openid存入数据库中
            {
                
                $contentStr = "理发由你安排，生活更加精彩！谢谢关注。";
                
                $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>"; 
                $msgType = "text";
                $resultStr = sprintf($textTpl, $postObj->FromUserName, $postObj->ToUserName, time(), $msgType, $contentStr);
                $guestInfo["openid"] = $postObj->FromUserName;
 				
                $this->theMsg->handleSubscribe($guestInfo);
                $this->createGuestId($postObj->FromUserName);
                return $resultStr;
                
				
                break;
            }
            case "unsubscribe"://将用户的openid从数据库中删除
            {
                $this->theMsg->handleUnsubscribe($postObj->FromUserName);
                break;
            }
			case "LOCATION":
            {
                $location["lng"] = $postObj->Longitude;
                $location["lat"] = $postObj->Latitude;
				$this->theMsg->handleLocation($location, $postObj->FromUserName);
                break;
            }
        }
        
    }
    
    function createGuestId($openid){
         $lastId = $this->theMsg->getLastGuestId();
         if(!empty($lastId)){
         	$lastGuestId = intval($lastId);
            $guestId = sprintf("%08d",$lastGuestId+1);
         }else{
         	$guestId = "00000000";   
         }
         $this->theMsg->addGuestIdByOpenid($openid, $guestId);
    }
    
    function responBook(){
    	
        $bookId = $_POST["bookId"];
        $state = $_POST["state"];
        
        $book = $this->theMsg->getBookByBookId($bookId);
        $shop = $this->theMsg->getShopByShopId($book["shopid"]);
        $openid = $this->theMsg->getOpenidByGuestId($book["guestid"]);
        
        if($state == "accept"){
            $contentStr = "店家“".$shop['name']."”已经接受了您于".$book['time']."的预约，请注意店家之后给的信息！";
        }else{
            $contentStr = "店家“".$shop['name']."”拒绝了您于".$book['time']."的预约，拒绝理由为：".$state."，您可以拨打店家电话（".$shop['tel'].")进行沟通确认。";
        }
        
        
        $txt = '{      
                    "touser":"'.$openid.'",
                    "msgtype":"text",      
                    "text":{
                           "content":"'.$contentStr.'"      
                    }  
                }';
        $result = $this->response($txt);
        echo $result;
         
    }
     
    function responEvaluation(){
    	
        $evaluationId = $_POST["evaluationId"];
        $message = $_POST["message"];
        
        $guestId = $this->theMsg->getGuestIdByEvaluationId($evaluationId);
        $openid = $this->theMsg->getOpenidByGuestId($guestId);
        
        $contentStr = "店家已经对您的评论做出了回复：".$message."。欢迎下次光临！";
        $txt = '{      
                    "touser":"'.$openid.'",
                    "msgtype":"text",      
                    "text":{
                           "content":"'.$contentStr.'"      
                    }  
                }';
        $result = $this->response($txt);
        echo $result;
    }
     
    function confirmTime(){
        

        $shopId = $_POST["shopId"];
        
        $list = $this->theMsg->getListByShopId($shopId);
        foreach($list["data"] as $key => $item){
            if($item["state"] != "unconfirmed" && $item["state"] != "already"){
                $openid = $this->theMsg->getOpenidByGuestId($item["guestid"]);
                $shop = $this->theMsg->getShopByShopId($shopId);
                if($item["state"] == "changed" || $item["state"] == "confirmed"){
                    $contentStr = "店家“".$shop['name']."”（".$shop['tel'].")已经确认了您于".$item['date']."的预约，您的理发时间为：（".date("Y-m-d h:i:sa", $item['time'])."），请及时到店理发，有其他疑问，请联系店家。";
                	$temp["state"] = "already";
                    $this->theMsg->updateListItem($item["bookid"], $temp);
                }else{
                    $contentStr = "店家“".$shop['name']."”拒绝了您于".$item['date']."的预约，拒绝理由为：".$item['state']."，您可以拨打店家电话（".$shop['tel'].")进行沟通确认。";
                	$this->theMsg->deleteListItem($item["bookid"]);
                }
                $txt = '{      
                            "touser":"'.$openid.'",
                            "msgtype":"text",      
                            "text":{
                                   "content":"'.$contentStr.'"      
                            }  
                        }';
                $result = $this->response($txt);
            }
         	   
        }
        echo $result;
    }
     
     
    function getAccessToken(){
        $appid = "wx037273ef859d63d5";
        $appsecret = "2ce1c10bc43830d44a1cc086789d8295";
        
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;  
        $json = $this->http_request_json($url);//这个地方不能用file_get_contents  
        $data = json_decode($json,true);  
        if($data['access_token']){  
            return $data['access_token'];  
        }else{  
            return "获取access_token错误";  
        }         
    }
     
    function http_request_json($url){    
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL,$url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
        $result = curl_exec($ch);  
        curl_close($ch);  
        return $result;    
    } 
	
    function response($data){ 
        $last = $this->theMsg->getLast();
        if(time() - $last['time'] > 7000){
            $access_token = $this->getAccessToken();
            $info["token"] = $access_token;
            $info["time"] = time();
            $this->theMsg->updateToken($info);
        }else{
            $access_token = $last["token"];
        }
        
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
        
        $curl = curl_init();      
        curl_setopt($curl, CURLOPT_URL, $url);       
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);      
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);      
        curl_setopt($curl, CURLOPT_POST, 1);      
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);      
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        $result = curl_exec($curl); 
        if (curl_errno($curl)) { 
            return 'Errno'.curl_error($curl);      
        }      
        curl_close($curl); 
        return $result;  
    }
 }
 
?>