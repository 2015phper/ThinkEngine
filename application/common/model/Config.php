<?php
namespace app\common\model;

use think\Model;

class Config extends Model
{
    protected $autoWriteTimestamp = false;
    protected $field = true;
    protected $pk = 'id';


    /**
     * 获取网站配置Config中的数据
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $name
     * @param int $id
     * @return mixed
     */
    public function GetOne($name, $id = 1){
        $Obj = Config::get($id);
        return empty($name) ? $Obj->webtsite : $Obj->$name;
    }

    /**
     * 获取文件上传类型, 默认["jpg","png","txt","js","zip"]
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param bool $string
     * @return mixed|string
     */
    public function PostFix($string = true){
        $Obj = Config::get(1);
        if($string){
            if(!$Obj->postfix){
                $type = "jpg,png,txt,js,zip";
            }else{
                $type = $Obj->postfix;
            }
        }else{
            if(!$Obj->postfix){
                $type = json_encode(["jpg","png","txt","js","zip"]);
            }else{
                $type = json_encode(explode(",",$Obj->postfix));
            }
        }
        return $type;
    }

    /**
     * 获取文件大小限制 默认10M
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return int|mixed
     */
    public function MaxSize(){
        $Obj = Config::get(1);
        $size = $Obj->maxsize * 1048576; //单位为M  1M = 1024KB 1KB = 1024字节
        return empty($Obj->maxsize) ? 10485760 : $size;
    }
}