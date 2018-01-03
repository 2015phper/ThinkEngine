<?php
namespace app\common\model;

use think\Model;

class Picture extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 保存在Photo中，以逗号隔开，已排除重复的
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @param $aid
     * @return bool
     */
    public function savePhoto($id, $aid){
        if(!$id) return false;
        if(!$aid) return false;
        $Obj = Picture::get($id);
        $rows = $this->query("select * from tc_picture where FIND_IN_SET('{$aid}',photo)");
        if(!$rows){
            $ids = explode(',', $Obj->photo);
            array_push($ids, $aid);
            $arr = array();
            foreach ($ids as $item){
                if($item){
                    $arr[] = $item;
                }
            }
            $data['id'] = $id;
            $data['photo'] = implode(',', $arr);
            $Obj->update($data);
        }
    }

    /**
     * 获取图库的数量
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return int
     */
    public function getPhotoCount($id){
        if(!$id){
            return 0;
        }
        $Obj = Picture::get($id);
        $ids = explode(',', $Obj->photo);
        $i = 0;
        foreach ($ids as $item){
            if($item){
                $i ++;
            }
        }
        return $i;
    }

    public function getPhotoList($id){
        if(!$id) return array();
        $Obj = Picture::get($id);
        if(empty($Obj->photo)) return array();
        $list = db("attachment")->where("id in ({$Obj->photo})")->order("id desc")->select();
        return $list;
    }

    /**
     * 获取封面图
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id Picture Id
     * @param boolean true thumb
     * @return mixed|string
     */
    public function getCover($id, $type=true){
        $none = "/uploads/index/no-img.jpg";
        if(!$id){return $none;}
        $picture = Picture::get($id);
        if(empty($picture->cover)){
            // 如果没有设置封面图则读取最新的一张图作为封面
            $ids = explode(',', $picture->photo);
            arsort($ids);
            $Obj = Attachment::get($ids[0]);
        }else{
            $Obj = Attachment::get($picture->cover);
        }
        if(empty($Obj)){
            return $none;
        }
        if($type == true){
            return $Obj->thumb;
        }
        return $Obj->filepath;
    }

    /**
     * 获取图库分类名称
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return bool|string
     */
    public function getCateName($id){
        if(!$id) return date("Y-m-d");
        $cate = db("picture_cate")->find($id);
        return $cate['name'];
    }

    /**
     * 获取图库分类列表
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param int $page
     * @return \think\Paginator
     */
    public function getCateList($page=20){
        $cate = new PictureCate();
        return $cate->paginate($page);
    }
}