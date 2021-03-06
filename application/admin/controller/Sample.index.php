<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Sample as SampleModel;

class Sample extends Controller {

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
        $list = db('Sample')->where($where)->order('id desc')->paginate(20);
        $this->assign('list', $list);
        return view('list');
    }

    /**
     * 编辑页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function edit(){
        $id = input("id");
        $Obj = SampleModel::get($id);
        $this->assign('Obj',$Obj);
        return view('add');
    }

    /**
     * 添加页
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function add(){
        return view('add');
    }

    /**
     * 新增或更新接口
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function save(){
        $data = input('post.');
        $id = $data['id'];
        $Obj = new SampleModel();
        if($id){
            $Obj = $Obj->get(input('post.id'));
        }
        $rows = $Obj->allowField(true)->save($data);
        if($rows){
            $this->success(empty($id) ? '添加成功':'更新成功', url('/admin/Sample/index'), [], 0);
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
        $rows = SampleModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/Sample/index"), '', 0);
    }
}
