<?php
namespace app\common\model;

use think\Model;

class About extends Model
{
    protected $autoWriteTimestamp = true;


    public function saveOrUpdate($data = [], $where = []){
        $about = new About();
        $isUpdate = empty($data['id']) ? false : true;
        $data['rank'] = empty($data['rank']) ? 0 : $data['rank'];
        $rows = $about->isUpdate($isUpdate)->allowField(true)->save($data, $where);
        return $rows;
    }

    /**
     * 获取某个模块下的单页面数据
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param string $module
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getList($module='index'){
        return db("about")->where("module", $module)->order("rank desc")->select();
    }
}