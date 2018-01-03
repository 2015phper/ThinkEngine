<?php
namespace app\common\model;

use think\Model;
use app\common\model\QuestCate;

class Quest extends Model
{
    protected $autoWriteTimestamp = true;

    /**
     * 获取分类的字段
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @param string $filed
     * @return mixed
     */
    public function getCateName($id, $filed='name')
    {
        if(empty($id)) return $id;
        $Obj = QuestCate::get($id);
        return $Obj->getData($filed);
    }

    /**
     * 根据类别获取热议的数据
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param int $cid
     * @return \think\Paginator
     */
    public function getList($cid){
        $where = "status > 0 and reply > 0";
        if($cid){
            $where .= " and cid = {$cid}";
        }
        return db('Quest')->where($where)->order('reply desc')->paginate(10);
    }

}