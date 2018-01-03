<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\common\model\Topic as TopicModel;
class Topic extends Controller
{
    public function index(){
        $title = input("param.title");
        $where = "1=1";
        if($title){
            $where .= " and title like '%{$title}%'";
        }
        $list = db("topic")->where($where)->order("id desc")->paginate(10);
        return view('topic/list', ["list"=>$list]);
    }

    public function add(){
        $id = input("id");
        $list = Db::table('tc_topic')
            ->alias('t')
            ->join('tc_topic_rel r','t.id = r.topic_id')
            ->join('tc_music m','r.music_id = m.id')
            ->paginate(10);
        $this->assign("list", $list);
        return view('topic/add');
    }

    public function save(){
        $data = input("post.");
        $topic = new TopicModel();
        $rows = $topic->saveOrUpdate($data);
        $msg = empty($data['id']) ? "数据添加成功" : "数据已更新";
        if($rows){
            $this->success($msg, url("topic/index"), null, 0);exit();
        }else{
            $this->error($msg, url("topic/add"), null, 0);exit();
        }
    }

    public function edit(){
        $id = input("param.id");
        if(empty($id)){
            $this->error("参数不能为空，请重试", url("topic/index"), null, 0);
        }
        $Obj = TopicModel::get($id);
        $list = $Obj->topicMusic($id); //歌单上的歌曲
        $this->assign("list", $list);
        return view('topic/edit', ["Obj"=>$Obj]);
    }

    public function delete(){
        $id = input("param.id");
        $rows = TopicModel::destroy($id);
        if($rows){
            $this->success("删除成功！", url("topic/index"), null, 0);
        }else{
            $this->error("删除失败！", url("topic/index"), null, 2);
        }
    }

    /**
     * 添加歌曲到歌单中
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function setMusic(){
        $keyword = input("keyword");
        $where = "1 = 1";
        if($keyword){
            $where .= " and id = '{$keyword}' or title like '%{$keyword}%'";
        }
        $list = db("music")->where($where)->order("id desc")->paginate(15);
        $this->assign("list", $list);
        return view("topic/setMusic");
    }

    /**
     * 设置歌单下的歌曲
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function saveMusic(){
        $id = input("post.id");
        $topic = input("post.topic");
        if(empty($topic) && empty($id)){
            $this->apiCallback("FAILED", array(), "没有添加任何歌曲");
        }
        $id = explode(",", $id);
        $data = array();
        foreach ($id as $key => $value){
            $data[$key]['topic_id'] = $topic;
            $data[$key]['music_id'] = $value;
            $data[$key]['create_time'] = time();
        }
        //db("topic_rel")->where("topic_id", $topic)->delete();
        $rows = db("topic_rel")->insertAll($data);
        $this->result($rows, "SUCCESS", "歌单已更新成功！此歌单已新增 {$rows} 首歌曲！");
    }

    /**
     * 删除歌单内的歌曲
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delMusic(){
        $id = input("id");
        if(empty($id)){
            $this->error("请选择要移除的歌曲！", null, '', 0);
        }
        $rows = db("topic_rel")->delete($id);
        if($rows){
            $this->success("已从歌单中移除该歌曲", null, '', 0);
        }else{
            $this->error("移除该歌曲失败，请重试！", null, '', 0);
        }
    }
}
