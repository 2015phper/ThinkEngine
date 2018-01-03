<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Group as GroupModel;

class group extends Controller{

    public function index(){
        $list = db("group")->paginate(10);
        $this->assign('list',$list);
        return view('list');
    }

    public function add(){
        $name = input('post.name');
        $result = db("group")->insert(['name'=>$name]);
        return $result;
    }

    public function edit(){
        $id = input('post.id');
        $name = input('post.name');
        $Obj = GroupModel::get($id);
        $Obj->name = $name;
        return $Obj->isUpdate(true)->save();
    }

    /**
     * 删除功能已完善 支持POST GET删除
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = GroupModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/group/index"), '', 0);
    }
}
