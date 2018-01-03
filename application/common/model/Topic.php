<?php
namespace app\common\model;

use think\Model;

class Topic extends Model
{
    protected $autoWriteTimestamp = true;


    public function saveOrUpdate($data = [], $where = []){
        $about = new Topic();
        $isUpdate = empty($data['id']) ? false : true;
        $data['uid'] = empty($data['uid']) ? session("userId") : $data['uid'];
        $rows = $about->isUpdate($isUpdate)->allowField(true)->save($data, $where);
        return $rows;
    }

    /**
     * 歌单下的所有歌曲
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return bool|\think\Paginator
     */
    public function topicMusic($id){
        if (empty($id)){
            return false;
        }
        $list = db("topic")
            ->alias("t")
            ->join("tc_topic_rel r", "t.id = r.topic_id")
            ->join("tc_music m", "r.music_id = m.id")
            ->field("t.* , r.id as rel_id, r.create_time as rel_create_time, m.*")
            ->where("t.id = {$id}")
            ->order("rel_create_time desc")
            ->paginate(15);
        return $list;
    }

    /**
     * 获取某个专辑下的音乐总数
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return int
     */
    public function musicCount($id){
        if(empty($id)){return 0;}
        return db("topic_rel")->where("topic_id = {$id}")->count('id');
    }
}