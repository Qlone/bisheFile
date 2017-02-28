<?php
class About{
    // 构造函数
    function About(){
        $this->dispatch();
    }
    
    function dispatch() {
        include_once(LIB_DIR."Base.php");
        include_once(LIB_DIR."Controller.php");
        
        $ctl = isset($_GET['ctl'])? $_GET['ctl'] : 'Guest';
        $act = isset($_GET['act'])? $_GET['act'] : 'about';
        
        $ctl = ucwords(strtolower($ctl));
        $act = strtolower($act);
        
        $ctl_file = APP_DIR."c/".$ctl."Controller.php";
        
        // 判断控制器文件是否存在
        if(!file_exists($ctl_file)) die("controller not exists");
        include_once($ctl_file);
        $ctl = $ctl."Controller";
        $obj_ctl = new $ctl;
        
        // 判断方法是存在
        if(!method_exists($ctl,$act)) die("method not exists");
        $obj_ctl->$act();
    }
}
