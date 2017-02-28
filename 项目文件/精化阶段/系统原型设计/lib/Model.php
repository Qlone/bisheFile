<?php
class Model extends Base
{
    function Model() {
        include_once(LIB_DIR."MysqlDB.php");
        // 连接数据库
        $this->db = new MysqlDB(DB_HOST.":".DB_PORT,DB_USER,DB_PWD,DB_NAME);
          
        //if(empty($this->table)) die($this->table."miss table name");
    }
    
    function exec($sSql) {
        return $this->db->exec($sSql);
    }
    
    // insert 操作
    function insert($table, $aData) {
        $aKey = array();
        $aVal = array();
        foreach($aData as $key=> $val) {
            $aKey[] = $key;
            $aVal[] = $val;
        }
        
        $sSql = "INSERT INTO `".$table."`(".implode(",",$aKey).") VALUES('".implode("','",$aVal)."')";
        
        return $this->db->exec($sSql);
    }
    
    function update($table, $aData, $id, $iID) {
        $aSet = array();
        if(is_array($aData)){
            foreach($aData as $key=> $val) {
                $aSet[] = $key."='".$val."'";
            }
        }
        $sSql = "UPDATE `".$table."` SET ".implode(",",$aSet)." WHERE ".$id."='".$iID."'";
        return $this->db->exec($sSql);
    }
    
    function delete($table, $where) {
        $sSql = "DELETE FROM `".$table."` WHERE ".$where;
        return $this->db->exec($sSql);
    }
    
    function getAll($table, $where, $page=null, $pagesize=null){
        
        $sSql = "SELECT count(*) AS num FROM `".$table."` where  ".$where;
        $aTemp = $this->db->fetchOne($sSql);
        $aResult['count'] = $aTemp['num'];
        
        $sLimit = "";
        if($page && is_numeric($page) && is_numeric($pagesize)) {
            $sLimit = " LIMIT ".$pagesize * ($page - 1).",".$pagesize;
        }
        
    	$sSql = "SELECT * FROM `".$table."` where ".$where.$sLimit;
      
        $aResult['data'] = $this->db->fetchAll($sSql);
       
        return $aResult;
    }
    
    function getOne($table, $id, $iID) {
        $sSql = "SELECT * FROM `".$table."` WHERE ".$id." = '".$iID."'";
        return $this->db->fetchOne($sSql);
    }
    /*
    function getCount($table, $where){
        $sSql = "SELECT count(*) AS num FROM `".$table."` where ".$where;
        $aTemp = $this->db->fetchOne($sSql);
        $count = $aTemp['num'];
        return $count;
    }
    */
    /*
    function getAll($table, $page = null,$pagesize = null,$where = array(), $order = '') {
        $sLimit = "";
        if($page && is_numeric($page) && is_numeric($pagesize)) {
            $sLimit = " LIMIT ".$pagesize * ($page - 1).",".$pagesize;
        }
        
        $sWHERE = "";
        if($where) {
            $sWHERE = " WHERE ".implode(' AND ',$where);
        }
        
        $sOrder = "";
        if($order) {
            $sOrder = " ORDER BY ".$order;
        }
        
        $sSql = "SELECT count(*) AS num FROM `".$table."` ".$sWHERE;
        $aTemp = $this->db->fetchOne($sSql);
        $aResult['count'] = $aTemp['num'];
        
        $sSql = "SELECT * FROM `".$table."` ".$sWHERE.$sOrder.$sLimit;
        $aResult['data'] = $this->db->fetchAll($sSql);
 
        return $aResult;
    }
    */
}
