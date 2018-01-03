<?php
namespace app\fly\controller;

use app\common\model\Quest;
use think\Controller;
use think\View;

class Index extends Controller {
    public function index(){
        $questCate = db("quest_cate")->order("update_time desc")->paginate(10);
        View::share("questCate", $questCate);
        //置顶
        $stick = db("quest")->where("status = 1 and cid = 6")->order("create_time desc")->paginate(10);
        $this->assign("stick", $stick);
        $quest = new Quest();
        $where = "cid in (1,2,3,4)";
        if(input('status') == 1){
            $where .= " and status = 1";
        }elseif(input('status') == 2){
            $where .= " and status = 2";
        }elseif(input('status') == 3){
            $where .= " and serum > 0";
        }else{
            $where .= " and status = 1";
        }
        $order = "create_time desc";
        if(input('time') == 1){
            $order = "reply desc";
        }
        $list = db("quest")->where($where)->order($order)->paginate(15);
        $this->assign("list", $list);

        $this->assign("quest", $quest);
        return view('/index');
    }

    public function topic(){
        if(request()->isPjax()){
            return view("/pjax_topic");
        }
    }

    public function music(){
        return view("/pjax_music");
    }
}
