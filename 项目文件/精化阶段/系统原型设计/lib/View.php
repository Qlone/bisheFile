<?php
// 一个简单的模板类
class View
{
    var $template_dir;
    var $data = array();
    
    public function assign($key,$data) {
        $this->data[$key] = $data;
    } 

    public function display($template) {
        foreach($this->data as $key=>$row) {
            $$key = $row;
        }
        include_once($this->template_dir."/".$template);
    }
}
