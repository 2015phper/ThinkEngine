<?php
namespace app\common\model;

use think\Model;
use think\Controller;
use think\Db;
use think\Request;

class ModelAction extends Model
{
    protected $name = "model";
    
    /**
     * @apply 删除表与field表中的字段
     * @author LiZhiyuan
     * @version v1.0
     * @param $id 模型id
     * @return bool
     */
    public function deleteTable($id){
        $result = db("model")->where("id",$id)->find();
        $table = $result['tablename'];
        if(empty($table)){return false;}
        Db::query("DELETE FROM tc_field WHERE mid = {$id}");
        Db::query("DROP TABLE IF EXISTS {$table}");
        return true;
    }
}