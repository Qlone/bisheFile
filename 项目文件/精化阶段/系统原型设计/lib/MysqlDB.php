<?php
// mysql数据库存操作类
class MysqlDB{
    var $_link;
    function MysqlDB($host,$user,$pwd,$dbname,$charset = 'utf8') {
        if(!($this->_link = mysql_connect($host,$user,$pwd))) die("database connects error");
        mysql_select_db($dbname,$this->_link);
        mysql_query("SET NAMES ".$charset,$this->_link);
    }
    
    function _query($sql) {
        return mysql_query($sql,$this->_link);
    }
    
    function exec($sql) {
        $res = $this->_query($sql);
        if(empty($res)) return false;
        return mysql_affected_rows($this->_link); // 返回影响行数
    }
    
    function getLastID() {
        return mysql_insert_id($this->_link);
    }
    
    function fetchOne($sql) {
         $res = $this->_query($sql);
         return mysql_fetch_array($res);
    }
    
    function fetchAll($sql) {
        $res = $this->_query($sql);
        //echo $res;
        $aResult = array();
        if(is_resource($res))
        	while($row = mysql_fetch_array($res)) {
            //echo $row[0];
           	 	$aResult[] = $row;
        	}
        //echo $aResult[0]['shopid'];
        return $aResult;
    }
    
}
