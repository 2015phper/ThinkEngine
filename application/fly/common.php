<?php
use think\Request;

/**
 * Index前台菜单
 * @author ZhiyuanLi < 956889120@qq.com >
 * @param $url
 * @return bool
 */
function indexMenuUrl($url){
    $first = explode('/',$url);
    $temp = array();
    foreach ($first as $item) {
        if($item != ''){
            $temp[] = $item;
        }
    }
    $request = Request::instance();
    $controller = $request->controller();
    if(strtolower($temp[1]) != strtolower($controller)){
        return false;
    }
    return true;
}