<?php
namespace app\common\model;

use think\Model;
use think\Request;
use think\Controller;

class User extends Model
{
    protected $pk = "id";
    protected $field = true;
    protected $autoWriteTimestamp = true;
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = "update_time";
    protected $type = ['time' => 'timestamp'];

    public function getUserName($id){
        if(empty($id)){return '-';}
        $Obj = User::get($id);
        return $Obj->username;
    }

    /**
     * 获取字段
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @param string $filed
     * @return mixed
     */
    public function getName($id, $filed='nickname')
    {
        if(empty($id)) return $id;
        $Obj = User::get($id);
        return $Obj->getData($filed);

    }

    /**
     * 获取用户所在的组
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return string
     */
    public function getUserGroup($id){
        if(empty($id)){return '-';}
        $ret = db("group_access")->where("uid = {$id}")->select();
        $group = db("group")->select();
        $name = '';
        foreach ($ret as $key => $item){
            foreach ($group as $value){
                if($item['group_id'] == $value['id']){
                    $name .= $value['name'] . '、';
                }
            }
        }
        return rtrim($name, '、');
    }

    public function getPhoto($id){
        if(empty($id)){return '';}
        $Obj = User::get($id);
        return $Obj->photo;
    }

    /**
     * 获取地区信息：广西 - 桂林 - 平乐县
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @param $uid
     * @return string
     */
    function getAreaInfo($uid=''){
        $uid = empty($uid) ? session("userId") : $uid;
        $user = User::get($uid);
        $list = db("area")->cache(86400)->where("pid != 0")->select();
        foreach ($list as $key => $value){
            if($user->province == $value['id']){
                $proName = $value['name'];
            }elseif ($user->city == $value['id']){
                $cityName = $value['name'];
            }elseif ($user->area == $value['id']){
                $areaName = $value['name'];
            }
        }
        if(empty($user->province)){
            $str = '';
        }else{
            $str = $proName . ' - ' . $cityName .' - '. $areaName;
        }
        return $str;
    }

    /**
     * 获取单个用户的详细信息
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @param string $field
     * @return array|false|mixed|\PDOStatement|string|Model
     */
    public function getUser($id, $field = ''){
        if(empty($id)){return '未知';}
        $info = db('user')
            ->alias('u')
            ->where("u.id = {$id}")
            ->join('group_access a','a.uid = u.id')
            ->join('group g','g.id = a.group_id')
            ->find();
        return empty($field) ? $info : $info[$field];
    }


    /**
     * 设置登陆保存
     * @author Zhiyuan Li
     * @version v1.0
     * @param $uid
     * @param $password
     * @return bool
     */
    public function setLogin($uid,$password){
        session("userId",$uid);
        $Obj = User::get($uid);
        $token = md5(time() . $password);
        self::saveToken($uid, $token);
        session("token", $token);
        $data = [
            'uid' => $uid,
            'account' => $Obj->username,
            'time' => time(),
            'online' => 1,
            'ip' => Request::ip(),
        ];
        db("login_log")->insert($data);
        return true;
    }

    /**
     * 保存Token
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $uid
     * @return string Token
     */
    public function saveToken($uid, $token){
        $rows = db("online")->find($uid);
        if($rows){
            $rows['isLogin'] = 1;
            $rows['inTime'] = time();
            $rows['token'] = $token;
            db("online")->update($rows);
        }else{
            $rows['id'] = $uid;
            $rows['isLogin'] = 1;
            $rows['inTime'] = time();
            $rows['token'] = $token;
            db("online")->insert($rows);
        }
        return true;
    }

    /**
     * 退出接口
     * @author Zhiyuan Li
     * @version v1.0
     * @return bool
     */
    public function offLogin(){
        $res = db("login_log")->where(['uid' => session("userId"),'online' => 1])->find();
        db("login_log")->where('id',$res['id'])->update(["online" => 0]);
        session(null);
        cookie(null);

        //离线
        $uid = session("userId");
        $data['outTime'] = time();
        $data['isLogin'] = 0;
        db("online")->where("id", $uid)->update($data);
        return true;
    }

    public function saveOrUpdate($data = [], $where = []){
        $Obj = new User();
        $isUpdate = empty($data['id']) ? false : true;
        $data['rank'] = empty($data['rank']) ? 0 : $data['rank'];
        $data['password'] = strlen ( $data['password'] ) > 15 ? $data['password'] : md5 ( $data['password'] );
        $rows = $Obj->isUpdate($isUpdate)->allowField(true)->save($data, $where);

        db("group_access")->where("uid = {$Obj->id}")->delete();
        if(!empty($data['group'])){
            foreach ($data['group'] as $key => $value){
                db("group_access")->insert(["uid"=>$Obj->id,'group_id'=> $value]);
            }
        }
        return $rows;
    }
}