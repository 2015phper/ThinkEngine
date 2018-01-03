<?php
namespace app\common\model;

use think\Model;

class Area extends Model
{
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
    }

    /**
     * 获取字段值
     * @author Zhiyuan Li
     * @version v1.0
     * @param $id
     * @param string $field
     * @return bool|mixed
     */
    public function getNameById($id, $field = '')
    {
        if(empty($id)){return false;}
        $info = db("area")->find($id);
        return empty($field) ? $info['name'] : $info[$field];
    }
}