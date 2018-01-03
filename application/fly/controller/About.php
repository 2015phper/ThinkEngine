<?php
namespace app\home\controller;

use think\Controller;
use app\common\model\About as AboutModel;

class About extends Controller {
    public function show(){
        $id = input('id');
        if(empty($id)){
            $this->error("错误，信息不存在");
        }
        $about = AboutModel::get($id);
        $this->assign('about', $about);
        return view('/about_show');
    }
}
