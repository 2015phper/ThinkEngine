<?php
namespace app\common\model;

use think\Model;

class News extends Model
{
    protected $name = 'news';

    public function getCateName($id){
        $cate = db("news_cate")->select();
        foreach ($cate as $kk => $vv){
            if($id == $vv['id']){
                return $vv['name'];
            }
        }
    }

    /**
     * 获取文章回复总数
     * @author Zhiyuan Li
     * @version v1.0
     * @param $id
     * @return int|string
     */
    public function getComCount($id){
        if(empty($id)){return 0;}
        $ret = db("comment")->where("aid = {$id} and mid = 2")->count();
        return $ret;
    }

    /**
     * 获取字段的值
     * @author Zhiyuan Li
     * @version v1.0
     * @param $id
     * @param string $field
     * @return bool|mixed
     */
    public function getFieldName($id, $field = ''){
        if(empty($id)){return false;}
        $ret = db("news")->find($id);
        return empty($field) ? $ret['title'] : $ret[$field];
    }

    /**
     * 获取某分类下的所有子类ID
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return bool|string
     */
    public function getCateSon($id){
        if(empty($id)){return false;}
        $list = db("news_cate")->where("pid = {$id} or id = {$id}")->select();
        if(!$list){return false;}
        $ids = array();
        foreach ($list as $key => $item){
            $ids[] = $item['id'];
        }
        return implode(',', $ids);
    }

    /**
     * 某分类下的所有文章数目
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $id
     * @return int|string
     */
    public function getCateCount($id){
        if(empty($id)) {
            return db("news")->count();
        }
        $ret = self::getCateSon($id);
        if(empty($ret)){return 0;}
        return db("news")->where("pid in ({$ret})")->count();
    }
}