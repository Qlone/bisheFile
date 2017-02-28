<?php

 class HomeController extends Controller
 {
 	var $theGuest;
    var $theManager;
 	function HomeController(){
 		parent :: Controller();
 		$this->theGuest = $this->getModel("Guest");
        //$this->theManager = $this->getModel("Manager");
 	}
 	
 	function index(){
        if(empty($_GET)){
 			$this->view->display("manager/login.html");
        }else{
            $openid = $this->getUserInfo($_GET["code"]);
            $guestId = $this->theGuest->getGuestIdByOpenid($openid);
            Session_start();
            $_SESSION["guestId"] = $guestId;
            $this->view->display("guest/index.html");
        }
 	}
   
     
    function getUserInfo($code)
    {
        $appid = "wx037273ef859d63d5";
        $appsecret = "2ce1c10bc43830d44a1cc086789d8295";
    
        $access_token = "";
    
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
        $access_token_json = $this->https_request($access_token_url);
        $access_token_array = json_decode($access_token_json, true);
        $access_token = $access_token_array['access_token'];
        $openid = $access_token_array['openid'];
        
        return $openid;
    }

	function https_request($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
        curl_close($curl);
        return $data;
    }
 	
 }

?>