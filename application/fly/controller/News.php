<?php
namespace app\home\controller;

use app\common\model\Comment;
use app\common\model\NewsCate;
use think\Controller;
use think\Request;
use app\common\model\News as NewsModel;

class News extends Controller {
    public function index(){
        $Obj = new NewsModel();
        $cid = input("cid");
        $where = "status = 1";
        if($cid){
            $where .= " and pid = {$cid}";
        }
        /* 最新文章 */
        $news = db("news")->where($where)->order('addtime desc')->paginate(15);
        $newsCount = db("news")->where("status = 1")->count();
        $this->assign("newsCount", $newsCount);
        /* 热门推荐 */
        $hot = db("news")->where("status = 1")->order('top desc, refined desc, levels desc,is_host desc, id desc')->cache("3600")->paginate(10);
        /* 小编推荐 */
        $cateList = db("news_cate")->where("refined > 0")->limit(6)->order('update_time desc')->select();
        foreach ($cateList as $key=>$value){
            $count = db("news")->where("pid = {$value['id']} and is_host > 0")->count();
            $cateList[$key]['count'] = $count;
        }
        $this->assign("cateList", $cateList);
        /* 全部分类 */
        $cateAll = db("news_cate")->limit(6)->order('rank desc')->select();
        foreach ($cateAll as $key=>$value){
            $count = db("news")->where("pid = {$value['id']} and is_host > 0")->count();
            $cateAll[$key]['count'] = $count;
        }
        /* 文章轮播 */
        $time = date("Y-m-d", time());
        $where = "'{$time}' > start_time and '{$time}' < end_time";
        $flash = db("flash")->where($where)->order("rank desc")->select();
        $this->assign('flash',$flash);

        $this->assign("cateAll", $cateAll);
        $this->assign("news", $news);
        $this->assign("Obj", $Obj);
        $this->assign("hot", $hot);
        return view('/news_list');
    }

    public function show(){
        $id = input("id");
        if(empty($id)){
            $this->error("早已飞去火星了！", url('/home/news'), null, 1);
        }
        $news = NewsModel::get($id);
        $tags = explode(',',$news->tags);
        $this->assign("news", $news);
        $hot = db("news")->where("status = 1")->order('top desc, refined desc, levels desc,is_host desc, id desc')->cache("3600")->paginate(10);
        $this->assign("hot", $hot);
        // 评论
        $comment = new Comment();
        $comList = $comment->where("aid in ({$id}) and mid = 2")->order("addtime desc")->paginate(20);
        $this->assign("comList", getTree($comList->getCollection()));
        $btn = ['primary', 'info', 'warning', 'danger', 'success', 'dark', 'black', 'light', 'white'];
        shuffle($btn);
        return view("/news_show", ['tags'=>$tags, 'btn'=>$btn]);
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
}
