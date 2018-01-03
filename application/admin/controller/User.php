<?php
namespace app\admin\controller;

use app\common\model\User as UserModel;
use think\Controller;
use think\Request;
use \think\Db;
class user extends Controller
{
    public function login(){
        $username = cookie("username");
        $pwd = cookie("password");
        if(!empty($username)  && !empty($pwd)){
            $res = Db::name('user')->where("username = '{$username}' and password = '{md5($pwd)}'")->find();
            if($res['isadmin'] == 1 && $res['status'] == 1){
                $this->setLogin($res['id'],$res['password']);
                header("Location:".url("admin/index/index"));die;
            }
        }

        return $this->fetch('login');
    }

    public function loginIn()
    {
        $captcha = input("post.captcha");
        if(!captcha_check($captcha)){
            $this->error("验证码错误，请重新输入", null, '', 0);
        };
        $username = trim(input('post.username'));
        $pwd = md5(input('post.password'));
        $res = Db::name('user')->where("username = '{$username}' and password = '{$pwd}'")->find();
        if($res){
            if($res['isAdmin'] == 2){
                $this->error("账号错误，禁止登陆后台.", null, '', 0);
            }elseif ($res['status'] != 1){
                $this->error("账号未审核或已被锁定，暂时不能登陆后台管理中心.", null, '', 0);
            }
            /*if(input('post.remember') == 1){
                cookie(['prefix' => 'think_', 'expire' => 3600]);
                cookie("username",$username,time() + 86400);
                cookie("password",input('post.password'),time() + 86400);
            }*/
            $user = UserModel::get($res['id']);
            db('user')->where('id',$res['id'])->update(['update_time' => time(),'update_ip' => Request::instance()->ip()]);
            $user->setLogin($res['id'],$res['password']);
            $time = date("Y-m-d H:i:s");
            $this->success("欢迎来到ThinkEngine CMS后台，欢迎回来 {$res['username']}，现在北京时间是{$time}.", url("/admin/index/index"), null, 0);
        }else{
            $this->error("用户名或密码错误.", null, '', 0);
        }
    }

    public function register(){
        return $this->fetch("register");
    }

    public function save(){
        $username = trim(input('post.username'));
        $pwd = md5(input('post.password'));
        $confirmPassword = md5(input('post.confirmPassword'));
        if($pwd != $confirmPassword){
            $this->error("密码与确认密码不一致，请重新输入.", null, '', 2);
        }

        $res = db('user')->where('username',$username)->find();
        if($res){
            $this->error('当前用户名已被注册，请重新填写用户名.', null, '', 2);
        }
        $request = Request::instance();
        $ip = $request->ip();
        $data = [
            'username' => $username,
            'password' => $confirmPassword,
            'regip' => $ip,
            'lastip' => $ip,
            'regtime' => time(),
            'lastvist' => time(),
            'sex' => 2,
            'status' => 1,
        ];
        $id = Db::name('user')->insertGetId($data);
        if($id){
            db("group_access")->save(['uid'=>$id,'group_id'=>2]);
            $this->setLogin($id,$data['password']);
            $this->success("注册成功，正在登陆中，请稍后...",url('/admin/index/index'), null, '', 2);
        }else{
            $this->error("注册失败，请检查字段是否有误.", null, '', 2);
        }
    }

    public function index(){
        $keyword = input("keyword");
        $where = "1=1";
        if($keyword){
            $where .= " and username like '%{$keyword}%' or truename like '%{$keyword}%' or id = '{$keyword}'";
        }
        $list = db("user")->where($where)->order("id desc")->paginate(10);
        $this->assign('Obj',new UserModel());
        $this->assign('list',$list);
        return view("list");
    }

    public function add(){
        //用户组
        $user = new UserModel();
        $this->assign('Obj',$user);
        $gradeList = db("group")->select();
        $this->assign('gradeList',$gradeList);
        return view("add");
    }

    public function edit(){
        $id = input("param.id");
        $user = UserModel::get($id);

        //用户组
        $gradeList = db("group")->select();
        $this->assign('gradeList',$gradeList);

        //用户组
        $group = db("group_access")->where("uid",$id)->select();
        $this->assign('group',separator($group,'group_id'));

        $this->assign('Obj',$user);
        return view("edit");
    }

    public function saveOrUpdate(){
        $data = input('post.');
        $data['group'] = input('post.group/a');
        $Obj = new UserModel();
        $rows = $Obj->saveOrUpdate($data);
        if($rows){
            $this->success("操作成功！", url('/admin/user/index'), '', 0);
        }
        $this->error("操作失败！", '', '', 1);
    }

    public function userFace(){
        $base = $_POST['src'];
        $path = upload($base);
        if($path){
            $data['status'] = "SUCCESS";
            $data['src'] = $path;
            $data['msg'] = "保存成功";
            return $data;
        }else{
            $data['status'] = "SUCCESS";
            $data['src'] = $path;
            $data['msg'] = "保存失败";
            return $data;
        }
    }

    /**
     * 后台退出
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     */
    public function loginOut(){
        $user = new UserModel();
        $user->offLogin();
        $this->success('退出成功',url('admin/user/login'), null, 0);exit();
    }

    public function lockScreen(){
        if(request()->isPost()){
            $pwd = input("post.password");
            $uid = session("userId");
            $user = UserModel::get($uid);
            if(md5($pwd) != $user->password){
                $this->error("密码错误", null, '', 0);
            }
            $time = date("Y-m-d H:i:s");
            $this->success("已成功解锁，现在时间是：{$time}", url("/admin"), null, 0);
        }
        return view("lock-screen");
    }

    public function detail(){
        $id = session("userId");
        $info = db("user")->find($id);
        return view();
    }

    /**
     * 删除功能已完善 支持POST GET删除
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = UserModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/user/index"), '', 1);
    }
}
