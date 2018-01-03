<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use app\common\model\Music as MusicModel;

class music extends Controller {
    public $objName = 'music';
    
    public function index(){
        $list = db('music')->order('id desc')->paginate(30);
        $this->assign('list', $list);
        $music = new MusicModel();
        $this->assign('Obj', $music);
        return view('list');
    }
    
    public function edit(){
        $id = input("param.id");
        $Obj = MusicModel::get($id);
        $this->assign('Obj',$Obj);
        return view('add');
    }
    
    public function add(){
        return view('add');
    }

    public function save(){
        $data = input("post.");
        $music = new MusicModel();
        $rows = $music->saveOrUpdate($data);
        $msg = empty($data['id']) ? "数据添加成功" : "数据已更新";
        if($rows){
            $this->success($msg, url("music/index"), null, 0);exit();
        }else{
            $this->error($msg, url("music/add"), null, 0);exit();
        }
    }

    //
    public function insertData(){
        $data = input('post.data/a');
        $newAry = array();
        $info = db("music")->order("insert_id desc")->limit(1)->find();
        $last = empty($info['insert_id']) ? 0 : $info['insert_id'];
        foreach ($data as $key => $item){
            if($item['id'] > $last) {
                $newAry[$key]['title'] = $item['title'];
                $newAry[$key]['tingUrl'] = $item['tingUrl'];
                $newAry[$key]['size'] = $item['size'];
                $newAry[$key]['insert_id'] = $item['id'];
                $newAry[$key]['lang_id'] = $item['lang_id'];
                $newAry[$key]['feeling_id'] = $item['feeling_id'];
                $newAry[$key]['scene_id'] = $item['scene_id'];
                $newAry[$key]['genre_id'] = $item['genre_id'];
                $newAry[$key]['length'] = str_replace("秒", '',str_replace("分", ":", $item['length']));
                $newAry[$key]['addtime'] = time();
                $newAry[$key]['uid'] = session('userId');
                $newAry[$key]['tags'] = getTags($item['title']);
                $newAry[$key]['downloadUrl'] = $item['downloadUrl'];
            }
        }
        $noAdd = count($data) - count($newAry);
        $rows = Db::name('music')->insertAll($newAry);
        $rows = empty($rows) ? 0 : $rows;
        $this->success("已成功添加{$rows}首，重复{$noAdd}首" , url("/admin/music/index"), null, 0);exit();
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = MusicModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/music/index"), '', 1);
    }

    /**
     * 复制音乐
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function copyMusic(){
        $list = db('music_copy')->order('id asc')->paginate(1500);
        $newAry = array();
        foreach ($list as $key => $item){
            $newAry[$key]['title'] = $item['title'];
            $newAry[$key]['tingUrl'] = "http://download.kekedj.com" .$item['url']. '/'  .$item['tingUrl'];
            $newAry[$key]['size'] = $item['size'];
            $newAry[$key]['insert_id'] = $item['id'];
            $newAry[$key]['lang_id'] = $item['lang_id'];
            $newAry[$key]['feeling_id'] = $item['feeling_id'];
            $newAry[$key]['scene_id'] = $item['scene_id'];
            $newAry[$key]['genre_id'] = $item['genre_id'];
            $newAry[$key]['length'] = str_replace("秒", '',str_replace("分", ":", $item['length']));
            $newAry[$key]['addtime'] = time();
            $newAry[$key]['uid'] = session('userId');
            $newAry[$key]['tags'] = $item['post_key'];
            $newAry[$key]['downloadUrl'] = "http://download.kekedj.com" .$item['url']. '/' .$item['downloadDir']. '/'  .$item['downloadUrl'];
            $newAry[$key]['downloadDir'] = $item['downloadDir'];
            $newAry[$key]['bitrate'] = str_replace("kbps", '', $item['bitrate']);
        }
        $rows = Db::name('music')->insertAll($newAry);
        $rows = empty($rows) ? 0 : $rows;
        echo "已添加{$rows}条<br >";
        echo $list->render();die;
    }
    //获取下载地址
    public function getDownLoadUrl($id){
        if(empty($id)){return false;}
        $musicObj = get('music',$id);
        $serversObj = get("servers",$musicObj->server_id);
        return $serversObj->downUrl.$musicObj->url.'/'.$musicObj->downloadDir.'/'.$musicObj->downloadUrl;
    }

    //获取试听地址
    public function getTingUrl($id){
        if(empty($id)){return false;}
        $musicObj = get('music',$id);
        $serversObj = get("servers",$musicObj->server_id);
        return $serversObj->downUrl.$musicObj->url.'/'.$musicObj->tingUrl;
    }
}
