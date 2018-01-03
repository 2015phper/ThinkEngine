<?php
namespace app\common\model;

use think\Model;

class Music extends Model
{
    protected $name = 'music';

    /**
     * 音乐列表的读写
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @param $content
     * @param bool $type true写、false读
     * @return bool|string 写返回 JS路径, 读返回JS里面的内容
     */
    public function musicFile($content, $type = true){
        if($type){
            $string = "var jPlayList = " . $content;
            file_put_contents(ROOT_PATH ."/public/static/home/js/jPlayer/jplayer.play.js", $string);
            return "runtime/temp/jplayer.play.js";
        }else{
            $string = file_get_contents(ROOT_PATH ."/public/static/home/js/jPlayer/jplayer.play.js");
        }
        return $string;
    }

    /**
     * 格式化成播放器代码
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param array $list 原始数据
     * @param bool $isFile 是否写进文件中
     * @return array
     */
    public function formatMusic($list = array(), $isFile = true){
        $isFile = empty($list) ? false : $isFile;
        $newAry = array();
        foreach ($list as $key => $value){
            $newAry[$key]['id'] = $value['id'];
            $newAry[$key]['title'] = $value['title'];
            $newAry[$key]['mp3'] = $value['tingUrl'];
            $user = User::get($value['uid']);
            $newAry[$key]['artist'] = $user->username;
        };
        if($isFile) {
            self::musicFile(json_encode($newAry));
        }
        return $newAry;
    }

    /**
     * 获取音乐的收藏数量
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return int|string
     */
    public function collCount($id){
        if(empty($id)) return 0;
        return db("collect")->where("mid = 4 and aid = {$id}")->count();
    }
}