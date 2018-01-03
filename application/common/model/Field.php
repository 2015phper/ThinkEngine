<?php
namespace app\common\model;

use think\Model;
use think\Db;
use app\common\model\ModelAction;

class Field extends Model
{
    /*
    *创建字段
    */
    public function createField($mid,$data) {
        //获取模型配置信息
        $result = db("model")->where('id',$mid)->find();
        $table = $result['tablename'];

        //判断表是否存在
        $table =  trim($table);
        $tableIsExist = Db::query("show tables like '{$table}'");
        if (!$tableIsExist) {
             //创建表
            $sql = "CREATE TABLE `".$table."` (`id` int(11) unsigned NOT NULL auto_increment, `content` text COMMENT '".$result["modelname"]."', PRIMARY KEY  (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            Db::query($sql);
        }
        //单图上传
        if (in_array($data['formtype'], array('image'))) {
            $sql    = "ALTER TABLE `" . $table . "` ADD `photo` varchar (255) DEFAULT '' COMMENT '".$data['name']."'";
            $sql2    = "ALTER TABLE `" . $table . "` ADD `photo_s` varchar (255) DEFAULT '' COMMENT '".$data['name']."'";
            Db::query($sql);
            DB::query($sql2);
            return true;
        }
        //地区
        if (in_array($data['formtype'], array('area'))) {
            $sql = "ALTER TABLE `" . $table . "` ADD `province` int (10) DEFAULT '0' COMMENT '省份'";
            Db::query($sql);
            $sql = "ALTER TABLE `" . $table . "` ADD `city` int (10) DEFAULT '0' COMMENT '城市'";
            Db::query($sql);
            $sql = "ALTER TABLE `" . $table . "` ADD `area` int (10) DEFAULT '0' COMMENT '区域'";
            Db::query($sql);
            return true;
        }
        //创建字段
        if (in_array($data['formtype'], array('editor', 'checkbox', 'files', 'fields'))) $data['type'] = 'TEXT';
        $settingAry = unserialize($data['setting']);
        if ($data['formtype'] == 'datetime' || $data['formtype'] == 'date' || $data['formtype'] == 'time') {
            if ($settingAry['type'] == 'yyyy-M-d H:mm:ss') {
                $data['type']   = 'datetime';
                $data['formtype'] == 'datetime';
            } else if ($settingAry['type'] == 'yyyy-M-d') {
                $data['type']   = 'date';
                $data['formtype'] == 'date';
            } else {
                $data['type']   = 'time';
                $data['formtype'] == 'time';
            }
        }
        if (in_array($data['type'], array('BIGINT', 'INT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'CHAR', 'VARCHAR'))) {
            $length = $data['length'] < 255 ? $data['length'] : 255;
            if (in_array($data['type'], array('CHAR', 'VARCHAR'))) {
                $value = "DEFAULT ''";
            } else {
                $value = "DEFAULT 0";
            }
            $sql    = "ALTER TABLE `" . $table . "` ADD `" . $data['fieldname'] . "` " . $data['type'] . " (" . $length . ") ".$value." COMMENT '".$data['name']."'";
        } elseif (in_array($data['type'], array('CHAR', 'VARCHAR'))) {
            $length = $data['length'] < 255 ? $data['length'] : 255;
            $sql    = "ALTER TABLE `" . $table . "` ADD `" . $data['fieldname'] . "` " . $data['type'] . " (" . $length . ") CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '".$data['name']."' NULL";
        } elseif (in_array($data['type'], array('DECIMAL', 'FLOAT', 'DOUBLE'))) {
            $length = strpos($data['length'], ',') === false ? $data['length'] . ',0' : $data['length'];
            $sql    = "ALTER TABLE `" . $table . "` ADD `" . $data['fieldname'] . "` " . $data['type'] . " (" . $length . ") NULL COMMENT '".$data['name']."'";
        } elseif ($data['formtype'] == 'datetime' || $data['formtype'] == 'date' || $data['formtype'] == 'time') {
            $sql    = "ALTER TABLE `" . $table . "` ADD `" . $data['fieldname'] . "` " . $data['type'] . " COMMENT '".$data['name']."'";
        }else {
            $sql    = "ALTER TABLE `" . $table . "` ADD `" . $data['fieldname'] . "` " . $data['type'] . " CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '".$data['name']."' NULL";
        }
        Db::query($sql);
        //创建索引
        if ($data['indexkey']) {
            $index  = "ALTER TABLE `" . $table . "` ADD " . $data['indexkey'] . " (`" . $data['fieldname'] . "`) ";!
            Db::query($index);
        }
    }
    /*
    *删除字段
    */
    public function deleteField($mid,$data) {
        //获取模型配置信息
        $model = ModelAction::get($mid);
        //判断表是否存在
        $tableIsExist = Db::query("show tables like '{$model->tablename}'");
        if (!$tableIsExist) {
            //提示表不存在
            return false;
        } else {
            //删除字段
            if (in_array($data['formtype'], array('area'))) {
                $sql = "ALTER TABLE `" . $model->tablename . "` DROP `province`";
                Db::query($sql);
                $sql = "ALTER TABLE `" . $model->tablename . "` DROP `city`";
                Db::query($sql);
                $sql = "ALTER TABLE `" . $model->tablename . "` DROP `area`";
                Db::query($sql);
            }
            if (in_array($data['formtype'], array('image'))) {
                $sql   = "ALTER TABLE `" . $model->tablename . "` DROP `photo`";
                Db::query($sql);
                $sql   = "ALTER TABLE `" . $model->tablename . "` DROP `photo_s`";
                Db::query($sql);
            }
            $sql = "ALTER TABLE `" . $model->tablename . "` DROP `" . $data['fieldname'] . "`";
            Db::query($sql);
            return true;
        }
    }
}