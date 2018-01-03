<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Link as linkModel;
class Link extends Controller
{
    public function index(){
        $ret = new linkModel();
        $name = input("param.name");
        $where = "1=1";
        if($name){
            $where .= " and name like '%{$name}%'";
        }
        $list = db("link")->where($where)->order("id asc")->paginate(10);

        $cate = db("link_cate")->select();

        $Obj = new LinkModel();
        $this->assign('link', $Obj);
        return view('link/list', ["list"=>$list,'cate'=>$cate]);
    }

    public function add(){
        $cate = db("link_cate")->select();
        return view('link/add', ['cate'=>$cate]);
    }

    public function save(){
        $data = input("post.");
        $model = new LinkModel();
        $rows = $model->saveOrUpdate($data);
        $msg = empty($data['id']) ? "数据添加成功，缓存已清除." : "数据已更新，缓存已清除.";
        if($rows){
            CacheClear();
            $this->success($msg, url("link/index"), null, 0);exit();
        }else{
            $this->error($msg, url("link/add"), null, 0);exit();
        }
    }

    public function edit(){
        $id = input("param.id");
        if(empty($id)){
            $this->error("参数不能为空，请重试", url("link/index"), null, 0);
        }
        $Obj = LinkModel::get($id);
        $cate = db("link_cate")->select();
        return view('link/add', ["Obj"=>$Obj,'cate'=>$cate]);
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = LinkModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/link/index"), '', 0);
    }

    /**
     * 列表快捷更新
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function update(){
        $name = input('post.field');
        $value = input('post.value');
        $id = input('post.id');
        $link = LinkModel::get($id);
        $link->$name = $value;
        $rows = $link->isUpdate(true)->save();
        if($rows){
            $this->result($rows, 1, "{$name}字段已更新为：". $value);
        }
        $this->result($rows, 0, "更新失败请稍后重试.");
    }
}
