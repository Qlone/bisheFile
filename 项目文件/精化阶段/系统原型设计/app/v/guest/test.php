<?php
	
	echo $data[0]["name"]."<br/>";
foreach($data as $key=>$value){
        echo $key."=>".$value;
		echo $value['distance']."<br/>";
}
    
?>