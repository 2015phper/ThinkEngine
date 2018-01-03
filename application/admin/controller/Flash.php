<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Flash as FlashModel;

class Flash extends Controller {

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
        $list = db('Flash')->where($where)->order('id desc')->paginate(20);
        $this->assign('list', $list);
        return view('list');
    }

    /**
     * 编辑页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function edit(){
        $id = input("id");
        $Obj = FlashModel::get($id);
        $this->assign('Obj',$Obj);

        $list = db("flash_cate")->select();
        return view('add', ['cate'=>$list]);
    }

    /**
     * 添加页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function add(){
        $list = db("flash_cate")->select();
        return view('add', ['cate'=>$list]);
    }

    /**
     * 新增或更新接口
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function save(){
        $data = input('post.');
        $id = $data['id'];
        $Obj = new FlashModel();
        if($id){
            $Obj = $Obj->get(input('post.id'));
        }
        $data['start_time'] = strtotime($data['start_time']);
        $data['end_time'] = strtotime($data['end_time']);
        $rows = $Obj->allowField(true)->save($data);
        if($rows){
            $this->success(empty($id) ? '添加成功':'更新成功', url('/admin/Flash/index'), [], 0);
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
        $rows = FlashModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/Flash/index"), '', 0);
    }
}
