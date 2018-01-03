<?php
namespace app\common\model;

use think\Model;

class Attachment extends Model
{

    public function saveOrUpdate($fileName, $name, $type, $size){
        $data['uid'] = session("userId");
        $data['mid'] = 5;
        $data['name'] = $fileName;
        $data['filename'] = str_replace(date("Ymd")."\\", '', $name);
        $data['filetype'] = $type;
        $data['filesize'] = $size;
        $data['filepath'] = "/uploads/" . str_replace("\\", '/', $name);
        $data['create_time'] = time();
        $rows = db("attachment")->insert($data);
        return $rows;
    }
}