<?php
namespace app\home\controller;

use app\common\model\Comment;
use think\Controller;
use think\Request;
use app\common\model\User;
use app\common\model\Music as MusicModel;

class Music extends Controller {
    public function index(){
        $lang = db("lang")->order("rank desc")->select();
        $genre = db("genre")->order("rank desc")->select();
        $feel = db("feeling")->order("rank desc")->select();
        $scene = db("scene")->order("rank desc")->select();
        $where = "1 = 1";
        if(input("?lang")){
            $where .= " and lang_id = " . input('lang');
            $name = db("lang")->find(input('lang'));
        }
        if(input("?genre")){
            $where .= " and genre_id = " . input('genre');
            $name = db("genre")->find(input('genre'));
        }
        if(input("?scene")){
            $where .= " and scene_id = " . input('scene');
            $name = db("scene")->find(input('scene'));
        }
        if(input("?feel")){
            $where .= " and feeling_id = " . input('feel');
            $name = db("feeling")->find(input('feel'));
        }
        $list = db("music")->where($where)->paginate(24);
        $newAry = array();
        foreach ($list as $key => $value){
            $newAry[$key]['id'] = $value['id'];
            $newAry[$key]['title'] = $value['title'];
            $newAry[$key]['mp3'] = $value['tingUrl'];
            $user = User::get($value['uid']);
            $newAry[$key]['artist'] = $user->username;
        };
        $music = new MusicModel();
        $music->musicFile(json_encode($newAry));
        $this->assign('list', $list);
        return view('/music_list', ['lang'=>$lang, 'genre'=>$genre, 'feel'=>$feel, 'scene'=>$scene, 'name'=>$name['name']]);
    }

    public function show(){
        $id = input("id");
        if(empty($id)){
            $this->error("暂无待播放的歌曲！", url('/home/music'), null, 1);
        }
        $music = new MusicModel();
        if(is_array($id)){
            $id = implode(",", $id);
        }
        $list = $music->where("id in ({$id})")->select();
        $this->assign("list", $list);
        $this->assign("music", $music);

        //单首歌曲评论
        $comment = new Comment();
        $comList = $comment->where("aid in ({$id})")->order("addtime desc")->paginate(20);

        $this->assign("comList", getTree($comList->getCollection()));

        return view("/music_show");
    }

    //我喜欢的音乐
    public function favorite(){
        return view("/music_list_2");
    }

    /**
     * 评论单首歌曲
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function comment(){
        $uid = session("userId");
        if(empty($uid)){
            $this->error("请登录后操作！");
        }
        $content = Request::instance()->post('content','','htmlspecialchars');
        if(!input("?post.aid")){
            $this->error("你要评论什么呢？我不知道你要评论什么呀~");
        }
        if(!$content){
            $this->error("你要评论什么呢？");
        }
        $comment = new Comment();
        $data['aid'] = input("post.aid");
        $data['pid'] = empty(input("post.pid")) ? 0 : input("post.pid");
        $data['uid'] = $uid;
        $data['mid'] = 4;
        $data['addtime'] = time();
        $data['content'] = $content;
        $rows = $comment->save($data);
        if($rows){
            $this->success("评论成功，赶紧分享给朋友获取点赞吧！");
        }else{
            $this->error("评论失败，请重试哦~！");
        }
    }

    public function hotMusic(){
        $music = new MusicModel();
        $type = input("type");
        $keyword = input("keyword");
        $where = "1 = 1";
        if($keyword){
            $where .= " and title like '%{$keyword}%'";
        }
        if($type == "listen"){
            $order = "update_time desc";
        }else if($type == 'hits'){
            $order = "hits desc";
        }else{
            $order = "addtime desc";
        }
        $list = $music->where($where)->order($order)->paginate(15);
        $music->formatMusic($list);
        $this->assign("list", $list);
        return view("/music_list_2");
    }

    /**
     * 增加播放的次数与更新时间
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function viewAdd(){
        $id = input("post.id");
        if(!$id){
            $this->apiCallback("FAILED", array(), "参数缺少");
        }
        if (! stripos ( $id, ',' )) {
            $music = MusicModel::get($id);
            $music->hits += 1;
            $music->update_time = time();
            $rows = $music->save();
        } else {
            $id = explode ( ',', $id );
            foreach ( $id as $value ) {
                $rows = db("music")->where("id", $value)->setDec('hits');
            }
        }
        $this->apiCallback("SUCCESS", $rows, "已增加点击数");
    }


    /**
     * 歌曲播放，支持单曲与多首歌曲
     * 传数组则清空当前播放列表
     * 传ID则添加到当前播放列表
     * 需要用到的文件 jplayer.playlist.js jplayer.init.js
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     */
    public function getMusic(){
        $Obj = new MusicModel();
        $id = $_REQUEST['id'];
        if(!stripos($id,',')) {
            $playList = $Obj->musicFile('',false);
            $playList = str_replace("var jPlayList = ", '', $playList); //去掉前面的变量名
            $playList = json_decode($playList, true); //解码
            foreach ($playList as $key => $value) {
                if ($value['id'] == $id) {
                    $newAry = $value;
                    $current = $key; //列表中存在将要播放的歌曲,并获得当前的下标
                }
            }

            //如果列表中不存在当前播放歌曲，则添加歌曲到当前播放列表中，并返回最后播放的下标 -1
            if (!$current) {
                $Obj = MusicModel::get($id);
                $newAry['id'] = $Obj->id;
                $newAry['title'] = $Obj->title;
                $newAry['mp3'] = $Obj->tingUrl;
                $user = User::get($Obj->uid);
                $newAry['artist'] = $user->username;
                array_push($playList, $newAry);
            }

            //写进当前播放JS中
            $music = new MusicModel();
            $music->musicFile(json_encode($playList));

            //返回单首的数据
            $this->apiCallback("001", $newAry, '单曲播放');
        }else{
            $list = db("music")->where("id in ({$id})")->select();
            $newAry = array();
            foreach ($list as $key => $value){
                $newAry[$key]['id'] = $value['id'];
                $newAry[$key]['title'] = $value['title'];
                $newAry[$key]['mp3'] = $value['tingUrl'];
                $user = User::get($value['uid']);
                $newAry[$key]['artist'] = $user->username;
            };
            $music = new MusicModel();
            $music->musicFile(json_encode($newAry));
            $this->apiCallback("000",$newAry,"刷新列表");
        }
    }
}
