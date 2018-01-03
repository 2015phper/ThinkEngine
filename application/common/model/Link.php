<?php
namespace app\common\model;

use think\Model;

class Link extends Model
{
    protected $autoWriteTimestamp = true;


    public function saveOrUpdate($data = [], $where = []){
        $about = new Link();
        $isUpdate = empty($data['id']) ? false : true;
        $data['rank'] = empty($data['rank']) ? 0 : $data['rank'];
        $rows = $about->isUpdate($isUpdate)->allowField(true)->save($data, $where);
        return $rows;
    }

    public function getCateName($id){
        $ret = db("link_cate")->find($id);
        if ($ret){
            return $ret['name'];
        }else{
            return "未知分类";
        }
    }

    /**
     * 获取某分类下的数据
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param int $cid
     * @param $cache
     * @return mixed
     */
    public function getList($cid=1,$cache=86400){
        $list = db("link")
            ->where("cid = {$cid} and display = 1")
            ->order("rank desc")
            ->cache($cache)
            ->select();
        return $list;
    }
}