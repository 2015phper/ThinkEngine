<?php
namespace app\admin\controller;

use app\common\model\Group as GroupModel;
use think\Controller;
use think\Data;
use think\Auth;
use app\common\model\Rule as RuleModel;
use app\common\model\Menu;
use think\Db;

class Rule extends Controller{

    public function index(){
        $list = $this->getTreeData('tree','id');
        $this->assign('list',$list);
        return view('list');
    }

    public function add(){
        $data = db("rule")->select();
        $_Obj = new Menu();
        $select = $_Obj->makeSelectOption($data);
        $this->assign('select',$select);
        return view("add");
    }

    public function edit(){
        $id = input('param.id');
        $Obj = RuleModel::get($id);
        $data = db("rule")->select();
        $_Obj = new Menu();
        $select = $_Obj->makeSelectOption($data);
        $this->assign('select',$select);
        $this->assign('Obj',$Obj);
        return view("add");

    }

    public function saveOrUpdate(){
        $data = input('post.');
        $Obj = new RuleModel();
        if($data['id']){
            $insert_id = $Obj->isUpdate(true)->save($data,$data['id']);
        }else{
            $insert_id = $Obj->isUpdate(false)->save($data);
        }
        if ($insert_id) {
            $msg = empty($data['id']) ? '添加成功' : '修改成功';
            return $this->success($msg, url("admin/rule/index"), $insert_id, 0);
        } else {
            $msg = empty($data['id']) ? '添加失败' : '修改失败';
            return $this->error($msg,'','',1);
        }
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = RuleModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/rule/index"), '', 1);
    }

    /**
     * 获取全部菜单
     * @param  string $type tree获取树形结构 level获取层级结构
     * @return array        结构数据
     * @param string $order
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function getTreeData($type='tree',$order=''){
        // 判断是否需要排序
        if(empty($order)){
            $data= db("rule")->select();
        }else{
            $data= db("rule")->order("{$order} asc")->select();
        }
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=Data::tree($data,'name','id','pid');
        }elseif($type="level"){
            $data=Data::channelLevel($data,0,'&nbsp;','id');
            // 显示有权限的菜单
            $auth=new Auth();
            foreach ($data as $k => $v) {
                if ($auth->check($v['name'],session('userId'))) {
                    foreach ($v['_data'] as $m => $n) {
                        if(!$auth->check($n['name'],session('userId'))){
                            unset($data[$k]['_data'][$m]);
                        }
                    }
                }else{
                    // 删除无权限的菜单
                    //unset($data[$k]);
                }
            }
        }
        // p($data);die;
        return $data;
    }

    /**
     * 分配权限列表
     */
    public function distribution(){
        $id = input('param.id');
        // 获取用户组数据
        $group_data = db("group")->where(['id'=>$id])->find();
        // 获取规则数据
        $rule_data=$this->getTreeData('level','id');
        $assign=array(
            'group_data'=>$group_data,
            'rule_data'=>$rule_data
        );
        $this->assign($assign);
        return view("auth_list");
    }

    /**
     * 用户组权限保存接口
     * @author Zhiyuan Li
     * @version v1.0
     */
    public function ruleSave(){
        $data = input('post.rule/a');
        $id = input('post.id');
        $_Obj = GroupModel::get($id);
        $_Obj->rules = implode(",",$data);
        $rows = $_Obj->isUpdate(true)->save();
        if($rows){
            $this->success("权限保存成功，即时生效", null, '', 0);
        }else{
            $this->error("保存失败，请重试");exit();
        }
    }

    /**
     * 一键生成权限
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function ruleAuto(){
        $module = input('param.module');
        $module = empty($module) ? 'admin' : $module;
        $ret = self::setRule($module);
        $this->success("{$module}模块权限已重新生成，控制器有：{$ret['controller']} 个，方法有：{$ret['action']} 个", null, '', 0);
    }

    /**
     * 规则保存Rule
     * @author Zhiyuan Li
     * @version v1.0
     * @param $module 模块名：admin OR index
     * @return mixed pid与id
     */
    public function setRule($module){
        $path = APP_PATH. $module .'/controller';
        $files = glob($path . '/*.php');
        foreach ($files as $key => $value) {
            $methods = $this->file_get_class_methods($value);
            $class_name = substr(strrchr($value, "/"), 1);
            $class_name = strtolower(str_replace('.php', '', $class_name));
            if($methods){
                foreach ($methods as $kk => &$v) {
                    if ($v != '__construct') {
                        $data[$class_name][$kk]['name'] = $module. '/' .$class_name. '/' .strtolower($v);
                        $data[$class_name][$kk]['title'] = self::getMethodName($v);
                    }
                }
            }
        }
        $data = self::getModelName($data);
        Db::query("truncate table ".config("database.prefix")."rule");//清空表
        foreach ($data as $ke => $item){
            $Obj = new RuleModel();
            $Obj->title = $item['title'];
            $Obj->name = $ke;
            $Obj->pid = 0;
            $Obj->save();
            $id = $Obj->getLastInsID();
            $result['controller'][] = $Obj->getLastInsID();
            foreach ($item['info'] as $keys => $val){
                $_Obj = new RuleModel();
                $_Obj->title = $val['title'];
                $_Obj->name = $val['name'];
                $_Obj->pid = $id;
                $_Obj->save();
                $result['action'][] = $Obj->getLastInsID();
            }
        }
        $result['controller'] = count($result['controller']);
        $result['action'] = count($result['action']);
        return $result;
    }

    /**
     * 模块表中的名字替换
     * @author Zhiyuan Li
     * @version v1.0
     * @param $data
     * @return array
     */
    public function getModelName($data){
        $model = db("model")->field("modelname,tablename")->select();
        $pre = config('database.prefix');
        $Ary = array();
        foreach ($model as $key => $value){
            foreach ($data as $kk => $vv){
                $name = str_replace($pre,'',strtolower($value['tablename']));
                if($name == $kk){
                    $Ary[$kk]['title'] = $value['modelname'];
                    $Ary[$kk]['info'] = $vv;
                }else{
                    $Ary[$kk]['title'] = $kk;
                    $Ary[$kk]['info'] = $vv;
                }
            }
        }
        return $Ary;

    }

    /**
     * 匹配方法名
     * @author Zhiyuan Li
     * @version v1.0
     * @param $file
     * @return array
     */
    public static function file_get_class_methods ($file){
        $arr = file($file);
        foreach ($arr as $line) {
            if (ereg ('public function ([_A-Za-z0-9]+)', $line, $regs)){
                $arr_methods[] = $regs[1];
            }
        }
        return $arr_methods;
    }

    /**
     * 获取方法名的中文名称
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $method
     * @return string
     */
    public static function getMethodName($method) {
        $methodStr = strtolower($method);
        switch ($methodStr) {
            case 'add':
                $str = "增加";
                break;
            case 'edit':
                $str = "编辑";
                break;
            case 'delete':
                $str = "删除";
                break;
            case 'saveOrUpdate':
                $str = "保存";
                break;
            case 'all':
                $str = "列表";
                break;
            case 'index':
                $str = "列表";
                break;
            case 'lock':
                $str = "锁定";
                break;
            case 'open':
                $str = "解锁";
                break;
            case 'show':
                $str = "显示";
                break;
            case 'update':
                $str = "更新";
                break;
            case 'save':
                $str = "保存";
                break;
            case 'truncate':
                $str = "清空";
                break;
            case 'login':
                $str = "登陆";
                break;
            default:
                $str = $method;
        }
        return $str;
    }
}
