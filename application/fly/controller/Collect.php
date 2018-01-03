<?php
namespace app\home\controller;

use think\Controller;
use app\common\model\Collect as CollectModel;

class Collect extends Controller {
    public function index(){
        $uid = session('userId');
        if(empty($uid)){
            $this->error("请登录后再操作！", url("/home/user/signIn"), null, 1);
        }
        $collect = new CollectModel();
        $list = $collect->table("tc_collect")->alias("c")
            ->join("tc_music m", "c.aid = m.id")
            ->where("c.uid = {$uid} and c.mid = 4")
            ->order("c.create_time desc")
            ->paginate();
        $this->assign("list", $list);
        return view("/collect_show");
    }

    /**
     * 收藏的专辑歌单
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function topic(){
        $uid = session('userId');
        if(empty($uid)){
            $this->error("请登录后再操作！", url("/home/user/signIn"), null, 1);
        }
        $ret = db("collect")->table("tc_collect")->alias("c")->join("tc_topic t", 't.id = c.aid')->where("c.uid = {$uid}")->order("c.create_time desc")->paginate(24);
        $this->assign("list", $ret);
        return view("/collect_topic");
    }

    /**
     * 添加到我的收藏
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function add(){
        $collect = new CollectModel();
        $data['uid'] = session("userId");
        if(empty($data['uid'])){$this->error("这么猴急干嘛，还没登陆呢~");}
        $data['aid'] = input("aid");
        $data['mid'] = input("mid");
        if(empty($data['aid']) || empty($data['mid'])){
            $this->error("收藏的对象都没有！");
        }
        $where = "uid = {$data['uid']} and aid = {$data['aid']} and mid = {$data['mid']}";
        $ret = db("collect")->where($where)->find();
        if($ret){
            CollectModel::destroy($ret['id']);
            $this->success("已取消收藏！");
        }else{
            $collect->save($data);
            $this->success("已添加到我的收藏！", '', $collect->id);
        }
    }

    /**
     * 删除功能已完善 支持POST GET删除
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = UserModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/user/index"), '', 1);
    }
}
