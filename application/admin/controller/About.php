<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\About as Abouts;
class About extends Controller
{
    public function index(){
        $ret = new Abouts();
        $keyword = input("keyword");
        $where = "1=1";
        if($keyword){
            $where .= " and title like '%{$keyword}%' or id = '{$keyword}'";
        }
        $list = db("about")->where($where)->order("id asc")->paginate(20);
        return view('about/list', ["list"=>$list]);
    }

    public function add(){
        return view('about/add');
    }

    public function save(){
        $data = input("post.");
        $about = new Abouts();
        $rows = $about->saveOrUpdate($data);
        $msg = empty($data['id']) ? "数据添加成功" : "数据已更新";
        if($rows){
            $this->success($msg, url("about/index"), null, 0);exit();
        }else{
            $this->error($msg, url("about/add"), null, 0);exit();
        }
    }

    public function edit(){
        $id = input("param.id");
        if(empty($id)){
            $this->error("参数不能为空，请重试", url("about/index"), null, 0);
        }
        $Obj = Abouts::get($id);
        return view('about/add', ["Obj"=>$Obj]);
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = LinkModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/about/index"), '', 0);
    }
}
