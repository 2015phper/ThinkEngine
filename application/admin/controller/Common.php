<?php
/**
 * Created by PhpStorm.
 * User: ZhiyuanLi < 956889120@qq.com >
 * Date: 2017/5/13
 * Time: 14:07
 */

namespace app\admin\controller;

use think\Controller;
use think\Cache;

class Common extends Controller{


    /**
     * 图标选择页面
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @return \think\response\View
     */
    public function Icon(){
        return view('Common/icons-font-awesome');
    }

    /**
     * 缓存清除
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     */
    public function CacheClear(){
        CacheClear();
        $this->success("系统提示：缓存已成功更新。", null, '', 0);
    }

    /**
     * 地区信息菜单选择
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @return string
     */
    public function getArea(){
        $id = $_POST['pid'];
        if(empty($id)){$id = 1;}
        $list = db('area')->where("pid = {$id}")->order('rank desc')->select();
        $str = "<option value=''>请选择</option>";
        foreach ($list as $key => $item){
            $str .= '<option value="'. $item['id'] .'">'. $item['name'] .'</option>';
        }
        return $str;
    }

}