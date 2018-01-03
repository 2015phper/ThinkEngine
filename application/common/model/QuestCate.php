<?php
namespace app\common\model;

use think\Model;

class QuestCate extends Model
{
    protected $autoWriteTimestamp = true;

    public function getList(){
        return db("quest_cate")->order("update_time desc")->select();
    }
}