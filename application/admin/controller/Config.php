<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Config as ConfigModel;

class Config extends Controller{

    public function index(){
        $Obj = ConfigModel::get(1);
        return view("config/index", ['Obj'=>$Obj]);
    }

    public function save(){
        $data = input("post.");
        $Obj = new ConfigModel();
        $Obj->allowField(true)->save($data, $data['id']);
        delFile(ROOT_PATH . "config/extra");
        $this->success("配置已更新", null, '', 0);
    }

}