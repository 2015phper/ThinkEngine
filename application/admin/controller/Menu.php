<?php
namespace app\admin\controller;

use think\console\command\optimize\Config;
use think\Controller;
use think\Data;
use think\Db;
use think\Request;
use app\common\model\Menu as MenuModel;

class menu extends Controller {
    public $objName = 'menu';
    
    public function index(){
        $list = Db::name('menu')->select();
        $list = Data::tree($list,'name','id','pid');
        $this->assign('list', $list);
        return view('list');
    }
    
    public function edit(){
        $id = Request::instance()->param('id');
        $info = db('menu')->find($id);

        $Obj = model("menu");
        $data = db("menu")->select();
        $selectStr = $Obj->makeSelectOption($data);

        $this->assign('select',$selectStr);
        $this->assign('data',$info);
        return view('edit');
    }
    
    public function add(){
        $Obj = model("menu");
        $data = db("menu")->select();
        $selectStr = $Obj->makeSelectOption($data);

        $this->assign('select',$selectStr);

        return view('add');
    }
    
    public function saveOrUpdate(){
        $data = input('post.data/a');
        if(empty($data['url'])){
            $data['url'] = 'JavaScript:void(0)';
        }
        $Obj = model("menu");
        if($data['id']){
            $insert_id = $Obj->isUpdate(true)->save($data,$data['id']);
        }else{
            $insert_id = $Obj->isUpdate(false)->save($data);
        }
        if ($insert_id) {
            $msg = empty($data['id']) ? '添加成功' : '修改成功';
            CacheClear();
            return $this->success($msg, 'menu/index', $insert_id, 0);
        } else {
            $msg = empty($data['id']) ? '添加失败' : '修改失败';
            return $this->error($msg,'','',0);
        }
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = MenuModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/menu/index"), '', 0);
    }

    /**
     * 列表快捷更新
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function update(){
        $name = input('post.field');
        $value = input('post.value');
        $id = input('post.id');
        $link = MenuModel::get($id);
        $link->$name = $value;
        $rows = $link->isUpdate(true)->save();
        if($rows){
            CacheClear();
            $this->result($rows, 1, "{$name}字段已更新为：". $value);
        }
        $this->result($rows, 0, "更新失败请稍后重试.");
    }
}
