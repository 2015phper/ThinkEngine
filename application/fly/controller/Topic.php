<?php
namespace app\home\controller;

use app\common\model\Music;
use think\Controller;
use app\common\model\User;
use app\common\model\Topic as TopicModel;

class Topic extends Controller {

    /**
     * 专辑列表
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function index(){
        $topic = new TopicModel();
        //热门专辑
        $hot = db("topic")->where("status = 1")->order("hits desc")->limit(3)->select();
        foreach ($hot as $key => $item){
            $hot[$key]['count'] = $topic->musicCount($item['id']);
        }
        $this->assign("hot", $hot);

        //所有专辑
        $list = db("topic")->where("status = 1")->order("create_time desc")->paginate(20);
        $this->assign("list", $list);
        return view("/topic_list");
    }

    /**
     * 专辑详情
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function show(){
        $id = input("id");
        if (!$id){
            $this->error('专辑暂未开放或已丢失在火星上！');
        }
        $topic = TopicModel::get($id);
        $list = $topic->topicMusic($id);
        $music = new Music();
        $music->formatMusic($list->getCollection());
        $this->assign("topic", $topic);

        //相似专辑
        $where = "status = 1";
        if($topic->tags){
            $where .= "status = 1 and title like '%{$topic->tags}%'";
        }
        $objAry = db("topic")->where($where)->select();
        $this->assign('objAry', $objAry);
        return view("/topic_show");
    }

    //我喜欢的音乐
    public function favorite(){
        return view("/music_list_2");
    }

    /**
     * 首页专辑请求
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function getAllMusic(){
        $id = input("post.id");
        if(empty($id)){
            $this->result(null, "FAILED", "错误请求");
        }
        $music = new Music();
        $list = TopicModel::topicMusic($id);
        if(empty($list)){
            $isFile = false;
        }
        $list = $music->formatMusic($list, $isFile);
        if(empty($list)){
            $this->result(array(), "FAILED", "该专辑还没有歌曲！");
        }
        $this->result($list, "SUCCESS", "已添加到播放列表！");
    }
}
