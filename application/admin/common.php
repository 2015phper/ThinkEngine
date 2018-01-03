<?php

use think\console\command\optimize\Config;
use think\Controller;
use think\Db;
use think\Request;
use think\Paginator;
use think\Loader;

/** 获取radio.checked.xiala的值
 * @author Zhiyuan Li
 * @version v1.0
 * @param $field
 * @param $value
 * @param $mid
 * @return string
 */
function getFieldValue($field,$value,$mid){
    $string = "";
    $sql = "SELECT setting FROM ".config('database.prefix')."field WHERE fieldname ='".$field."' AND mid = ".$mid;
    $setting = Db::query($sql);
    $settingAry = unserialize($setting[0]["setting"]);
    $contentAry = explode("\n", trim($settingAry["content"]));
    $rsAry = explode(",", trim($value));
    foreach($contentAry as $key => $value) {
        $cAry = explode("|", trim($value));
        $cAryStr = htmlspecialchars_decode($cAry[1]);
        if (in_array($cAryStr,$rsAry)) {
            $string .= $cAry[0]. '&nbsp;';
        }
    }
    return $string;
}

/**
 * 获取地区信息：广西 - 桂林 - 平乐县
 * @author ZhiyuanLi < 956889120@qq.com >
 * @since 1.0
 * @param $province
 * @param $city
 * @param $area
 * @return string
 */
function getAreaInfo($province,$city,$area){
    $list = db("area")->cache(86400)->where("pid != 0")->select();
    foreach ($list as $key => $value){
        if($province == $value['id']){
            $proName = $value['name'];
        }elseif ($city == $value['id']){
            $cityName = $value['name'];
        }elseif ($area == $value['id']){
            $areaName = $value['name'];
        }
    }
    if(empty($province)){
        $str = '';
    }else{
        $str = $proName . ' - ' . $cityName .' - '. $areaName;
    }
    return $str;
}

/**
 * 获取会员组名
 * @author ZhiyuanLi < 956889120@qq.com >
 * @since 1.0
 * @param $grade
 * @return mixed
 */
function getGradeName($grade){
    $list = db("group")->cache(86400)->select();
    foreach ($list as $key => $value){
        if($grade == $value['id']){
            $name = $value['name'];
        }
    }
    return $name;
}

/**
 * 后台菜单判断是否选中
 * @author ZhiyuanLi < 956889120@qq.com >
 * @param $url
 * @return bool
 */
function adminMenuUrl($url){
    $first = explode('/',$url);
    $temp = array();
    foreach ($first as $item) {
        if($item != ''){
            $temp[] = $item;
        }
    }
    $request = Request::instance();
    $controller = $request->controller();
    if (strpos($temp[1], "_")){
        $name = explode('_', $temp[1]);
        $temp[1] = implode('', $name);
    }
    if(strtolower($temp[1]) != strtolower($controller)){
        return false;
    }
    return true;
}

/** 无限级菜单（在父类下新增children）
 * @param $arr
 * @return array
 */
function makeTree($arr){
    if(!function_exists('makeTreeAdd')){
        function makeTreeAdd($arr, $parent_id=0){
            $new_arr = array();
            foreach($arr as $k=>$v){
                if($v['pid'] == $parent_id){
                    $new_arr[] = $v;
                    unset($arr[$k]);
                }
            }
            foreach($new_arr as &$a){
                $a['children'] = makeTreeAdd($arr, $a['id']);
            }
            return $new_arr;
        }
    }
    return makeTreeAdd($arr);
}


/**
 * 增加前缀
 * @author ZhiyuanLi < 956889120@qq.com >
 * @since 1.0
 * @param $arr
 * @return array
 */
function makeTreePre($arr){
    $arr = makeTree($arr);
    if (!function_exists('makeTreePreAdd')) {
        function makeTreePreAdd($arr, $pre='') {
            $new_arr = array();
            foreach ($arr as $v) {
                if ($pre) {
                    if ($v == end($arr)) {
                        $v['name'] = $pre.'└─ '.$v['name'];
                    } else {
                        $v['name'] = $pre.'├─ '.$v['name'];
                    }
                }
                if ($pre == '') {
                    $pre_for_children = '　 ';
                } else {
                    if ($v == end($arr)) {
                        $pre_for_children = $pre.'　　 ';
                    } else {
                        $pre_for_children = $pre.'│　 ';
                    }
                }
                $v['children'] = makeTreePreAdd($v['children'], $pre_for_children);

                $new_arr[] = $v;
            }
            return $new_arr;
        }
    }
    return makeTreePreAdd($arr);
}

/**
 * 创建select下拉的选项
 * @author ZhiyuanLi < 956889120@qq.com >
 * @since 1.0
 * @param $arr
 * @param int $depth 当$depth为0的时候表示不限制深度
 * @return string
 */
function makeSelectOption($arr, $depth=0)
{
    $arr = makeTreePre($arr);
    if (!function_exists('make_options1')) {
        function make_options1($arr, $depth, $recursion_count=0, $ancestor_ids='') {
            $recursion_count++;
            $str = '';
            foreach ($arr as $v) {
                $str .= "<option value='{$v['id']}' data-depth='{$recursion_count}' data-ancestor_ids='".ltrim($ancestor_ids,',')."'>{$v['name']}</option>";
                if ($v['pid'] == 0) {
                    $recursion_count = 1;
                }
                if ($depth==0 || $recursion_count<$depth) {
                    $str .= make_options1($v['children'], $depth, $recursion_count, $ancestor_ids.','.$v['id']);
                }

            }
            return $str;
        }
    }
    $header = '<option value="0">大类</option>';
    return $header.make_options1($arr, $depth);
}