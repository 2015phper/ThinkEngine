<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Quest as QuestModel;

class Quest extends Controller {

    /**
     * 列表页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function index(){
        $keyword = input("keyword");
        $where = "1=1";
        if($keyword){
            $where .= " and title like '%{$keyword}%' or id = '{$keyword}'";
        }
        $list = db('Quest')->where($where)->order('id desc')->paginate(20);
        $this->assign('list', $list);
        $this->assign('Obj', new QuestModel());
        return view('list');
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
