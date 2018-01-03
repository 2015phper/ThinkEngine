<?php
namespace app\admin\controller;

use app\common\model\Menu;
use app\common\model\News as NewsModel;
use think\console\command\optimize\Config;
use think\Controller;
use think\Data;
use think\Db;
use think\Request;
use think\Paginator;
use think\Loader;

class news extends Controller {
    public $objName = 'news';
    
    public function index(){
        $pid = input('pid');
        $keyword = input('keyword');
        $where = '1=1';
        if($keyword){
            $where .= " and title like '%{$keyword}%'";
        }
        if($pid){
            $where .= " and pid = {$pid}";
        }
        $Obj = new NewsModel();
        $ret = $Obj->getCateCount(1);
        $list = db('news')->where($where)->order('id desc')->paginate(30);
        $this->assign('list', $list);
        $cate = db("news_cate")->where("pid = 0")->order("update_time desc")->cache(86400)->select();
        $this->assign('cate', $cate);
        $this->assign('Obj', $Obj);
        return view('list');
    }
    
    public function edit(){
        $id = input("param.id");
        $Obj = NewsModel::get($id);
        $cate = db("news_cate")->select();
        $menu = new Menu();
        $cate = $menu->makeSelectOption($cate);
        $this->assign('cate',$cate);
        $this->assign('Obj',$Obj);
        return view('add');
    }
    
    public function add(){
        $cate = db("news_cate")->select();
        $menu = new Menu();
        $cate = $menu->makeSelectOption($cate);
        $this->assign('cate',$cate);
        return view('add');
    }

    public function saveOrUpdate(){
        $data = input('post.');
        $data['addtime'] = time();
        $data['uid'] = session('userId');
        $id = $data['id'];
        $Obj = new NewsModel();
        if($id){
            $Obj = $Obj->get(input('post.id'));
        }
        $data['refined'] = empty($data['refined']) ? 0 : 1;
        $data['levels'] = empty($data['levels']) ? 0 : 1;
        $data['top'] = empty($data['top']) ? 0 : 1;
        $rows = $Obj->allowField(true)->save($data);
        if($rows){
            $this->success(empty($id) ? '添加成功':'更新成功', url("/admin/news/index"), '', 0);
        }else{
            $this->error('操作失败，请重试');die;
        }
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = NewsModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/news/index"), '', 1);
    }
    
}
