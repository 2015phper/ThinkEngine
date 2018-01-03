<?php
namespace app\fly\controller;

use app\common\model\Answer;
use app\common\model\User;
use think\Controller;
use think\View;
use app\common\model\Quest as QuestModel;

class Quest extends Controller {

    /**
     * 列表页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function index(){
        $keyword = input("keyword");
        $status = input('status');
        $where = "status = 1";
        if($keyword){
            $where .= " and title like '%{$keyword}%' or id = '{$keyword}'";
        }
        $cid = input('cid');
        if($cid){
            $where .= " and cid = {$cid}";
        }
        if($status == 1 || $status == 2){
            $where .= " and status = {$status}";
        }
        if($status == 3){
            $where .= " and serum > 0";
        }
        $list = db('Quest')->where($where)->order('create_time desc')->paginate(20);
        $this->assign('list', $list);
        $this->assign('quest', new QuestModel());

        $questCate = db("quest_cate")->order("update_time desc")->paginate(10);
        View::share("questCate", $questCate);
        return view('index');
    }

    /**
     * 详情页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function show(){
        $id = input("id");
        $Obj = QuestModel::get($id);
        $Obj->setInc('view'); //自增+1
        $this->assign('Obj',$Obj);
        $questCate = db("quest_cate")->order("update_time desc")->paginate(10);
        View::share("questCate", $questCate);

        $Answer = new Answer();
        $answerList = $Answer->where("qid = {$id}")->order("accept desc, create_time desc")->paginate(15);
        $this->assign('answerList',$answerList);
        return view('detail');
    }

    public function zan(){
        $id = input('post.id');
        if(!$id){
            $this->error("参数设置错误");
        }
        $Obj = Answer::get($id);
        $Obj->setInc('zan');
        $this->success("已赞");
    }

    public function reply(){
        $data['qid'] = input('post.id');
        $data['content'] = input('post.content');
        if(empty($data['qid']) || !$data['content']){
            $this->error("参数设置错误");
        }
        $uid = session("userId");
        if(empty($uid)){
            $this->error("请登录后操作");
        }
        $quest = QuestModel::get($data['qid']);
        $quest->setInc("reply");
        $data['uid'] = $uid;
        $Obj = new Answer();
        $rows = $Obj->save($data);
        if(!$rows){
            $this->error("参数设置错误");
        }
        $this->success("已回复", null, $rows);
    }

    /**
     * 编辑页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function edit(){
        $id = input("id");
        $Obj = QuestModel::get($id);
        $this->assign('Obj',$Obj);
        $cate = db("quest_cate")->cache(86400)->select();
        $this->assign('cate', $cate);
        return view('add');
    }

    /**
     * 添加页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function add(){
        $cate = db("quest_cate")->cache(86400)->select();
        $this->assign('cate', $cate);
        return view('add');
    }

    /**
     * 新增或更新接口
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function save(){
        $data = input('post.');
        $id = $data['id'];
        $Obj = new QuestModel();
        if($id){
            $Obj = $Obj->get(input('post.id'));
        }
        $data['uid'] = session('userId');
        $data['stick'] = empty($data['stick']) ? 0 : 1;
        $data['serum'] = empty($data['serum']) ? 0 : 1;
        $rows = $Obj->allowField(true)->save($data);
        if($rows){
            $this->success(empty($id) ? '添加成功':'更新成功', url('/admin/Quest/index'), [], 0);
        }else{
            $this->error('操作失败，请重试');die;
        }
    }

    /**
     * 支持POST GET删除、支持逗号隔开的ID
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = QuestModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/Quest/index"), '', 1);
    }
}
