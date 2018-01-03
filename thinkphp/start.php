<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think;

// ThinkPHP 引导文件
// 加载基础文件
require __DIR__ . '/base.php';
//开启域名部署后
/*switch ($_SERVER['HTTP_HOST']) {
    case 'www.52nd.xin':
        $model = 'index';// index
        $route = true;// 开启路由
        break;
    case 'm.52nd.xin':
        $model = 'admin';// admin模块
        $route = false;// 关闭路由
        break;
    default:
        $model = 'index';// index
        $route = true;// 开启路由
}
define('BIND_MODULE', $model);
App::route($route);// 路由*/
// 执行应用
App::run()->send();
