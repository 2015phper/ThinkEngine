<?php
/**
 * Created by PhpStorm.
 * User: ZhiyuanLi < 956889120@qq.com >
 * Date: 2017/5/13
 * Time: 14:07
 */

namespace app\admin\controller;

use think\Controller;
use app\common\model\Config as ConfigModel;
use think\File;

class Cropper extends Controller{

    public function index(){
        $type = ConfigModel::PostFix(true);
        $size = ConfigModel::MaxSize();
        return view("index", ['type'=>$type,'size'=>$size]);
    }

    public function upload(){
        $file = input("post.data");
        if(empty($file)){
            $this->error("无文件资源");
        }
        $ret = upload($file);
        if($ret){
            $this->success("保存成功", '', $ret, 0);
        }else{
            $this->error("保存失败", null, '', 0);
        }
    }

    public function delete(){
        $name = input('post.name');
        $ret = db("attachment")->where("name", $name)->find();
        if($ret){
            db("attachment")->delete($ret['id']);
            unlink(ROOT_PATH. $ret['filepath']);
        }
        $this->success("已删除成功", null, $ret, 0);
        $this->apiCallback("SUCCESS", $name, $name . "已删除成功");
    }

}