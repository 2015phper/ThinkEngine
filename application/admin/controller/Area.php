<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\Area as AreaModel;
class Area extends Controller
{

    public function saveOrUpdate(){
        $data = input('post.');
        $id = $data['id'];
        $Obj = new AreaModel();
        if(empty($id)){
            $data['pid'] = empty($data['pid']) ? 0 : $data['pid'];
        }
        if($id){
            $Obj = $Obj->get($id);
        }
        $rows = $Obj->allowField(true)->save($data);
        if($rows){
            $this->success(empty($id) ? '添加成功':'更新成功', url("about/index"), null, 0);exit();
        }else{
            $this->error('操作失败，请重试', null, '', 0);die;
        }
    }

    public function index(){
        $Obj = new AreaModel();
        $where = "pid = 0";
        $pid = input("param.pid");
        if($pid){
            $where = "pid = {$pid}";
        }
        $list = $Obj->where($where)->select();
        $this->assign("list", $list);
        return view("list");
    }

    public function add(){
        $Obj = new AreaModel();
        $this->assign("Obj",$Obj);
        return view("add");
    }

    public function edit(){
        $id = input("param.id");
        $Obj = AreaModel::get($id);
        $this->assign("Obj",$Obj);
        return view("edit");
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = LinkModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/area/index"), '', 0);
    }

}
