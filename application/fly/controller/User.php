<?php
namespace app\fly\controller;

use think\Controller;
use think\Request;
use app\common\model\User as UserModel;

class User extends Controller {

    public function login(){
        if (request()->isPost()){
            $data = input("post.");
            if(!captcha_check($data['code'])){
                $this->error("验证码错误");
            };
            $info = db("user")->where("username = '{$data['username']}'")->find();
            if(empty($info)){
                $this->error('该账号不存在', '', $info, 2);
            }
            if(md5($data['password']) != $info['password']){
                $this->error('密码错误，请重新输入', '', '', 2);
            }
            if($info['status'] != 1){
                $this->error('您的账号已被锁定，请联系管理员', '', '', 2);
            }
            db('user')->where('id',$info['id'])->update(['update_ip' => request()->ip(),'update_time' => time()]);
            $user = new UserModel();
            $user->setLogin($info['id'],$info['password']);
            $this->success('登录成功', url('/fly/user/index'), $info, 2);
        }
        $uid = session("userId");
        if($uid){
            $this->redirect(url('/fly/user/index'));
        }
        return view('login');
    }
    public function register(){
        if (request()->isPost()){
            $data = input("post.");
            $user = new UserModel();
            $data['email'] = strtolower($data['email']);
            $ret = db("user")->where("email = '{$data['email']}'")->find();
            if($ret){
                $this->error("邮箱已被注册");
            }
            if(strlen($data['password']) < 5){
                $this->error("密码至少6个字符");
            }
            if($data['password'] != $data['confirmPwd']){
                $this->error("输入的两次密码不一样");
            }
            if(!captcha_check($data['code'])){
                $this->error("验证码错误");
            };
            $data['password'] = md5($data['confirmPwd']);
            $ret = $user->save($data);
            if(!$ret){
                $this->error("注册失败，请重试");
            }
            /*import('PHPMailer', EXTEND_PATH);
            $mail = new \PHPMailer();*/
            $user->setLogin($user->id,$data['password']);
            $this->success("注册成功", url('/fly/user/index'));
        }
        $uid = session("userId");
        if($uid){
            $this->redirect(url('/fly/user/index'));
        }
        return view('reg');
    }

    /**
     * 用户中心
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\View
     */
    public function index(){
        $uid = session('userId');
        if(empty($uid)){
            $this->redirect(url("/home/user/login"));
        }
        $user = UserModel::get($uid);
        $this->assign("user", $user);
        return view('index');
    }

    /**
     * 用户主页
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\View
     */
    public function home(){
        $uid = session('userId');
        if(empty($uid)){
            $this->redirect(url("/home/user/login"));
        }
        $user = UserModel::get($uid);
        $this->assign("user", $user);
        return view('home');
    }

    /**
     * 用户设置
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\View
     */
    public function set(){
        $uid = session('userId');
        if(empty($uid)){
            $this->redirect(url("/home/user/login"));
        }
        $user = UserModel::get($uid);
        $this->assign("user", $user);
        return view('set');
    }

    /**
     * 消息中心
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\View
     */
    public function message(){
        $uid = session('userId');
        if(empty($uid)){
            $this->redirect(url("/home/user/login"));
        }
        $user = UserModel::get($uid);
        $this->assign("user", $user);
        return view('message');
    }

    public function update(){
        $data = input("post.");
        $uid = session("userId");
        $user = UserModel::get($uid);
        $rows = $user->allowField(true)->save($data);
        if(!$rows){
            $this->apiCallback("FAILED", $rows, "没有任何的改变~");
        }
        $this->apiCallback("SUCCESS", $rows, "已更新成功");
    }

    public function safety(){
        $data = input("post.");
        $uid = session("userId");
        $user = UserModel::get($uid);
        if(strcasecmp(md5($data['password']), $user->password) != 0){
            $this->apiCallback("FAILED", null, "原密码错误，请重新输入！");
        }
        if(strcasecmp($data['pwd'], $data['confirmPwd']) != 0){
            $this->apiCallback("FAILED", null, "确认密码不一致，请重新输入！");
        }
        $user->password = md5(trim($data['confirmPwd']));
        $rows = $user->save();
        if(!$rows){
            $this->apiCallback("FAILED", null, "密码修改失败，请重试！");
        }
        session(null);
        $this->apiCallback("SUCCESS", url("/home/user/signIn"), "密码修改成功，请重新登录！");
    }

    public function logout(){
        $user = new UserModel();
        $user->offLogin();
        $this->redirect('/home');
    }
}
