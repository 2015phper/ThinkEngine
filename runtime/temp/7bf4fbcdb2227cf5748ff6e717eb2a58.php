<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:90:"D:\wamp\www\120.79.12.123\ThinkEngine\public/../application/admin\view\link_cate\list.html";i:1511399613;s:24:"static/admin/navbar.html";i:1514944209;s:23:"static/admin/aside.html";i:1511141685;s:22:"static/admin/menu.html";i:1511605267;s:24:"static/admin/footer.html";i:1511745268;}*/ ?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网址导航分类列表 | TE.CMS - ThinkEngineCMS</title>
    <!--STYLESHEET-->
    <link href="__LAYUI__css/layui.css" rel="stylesheet" >
    <link href='__ADMIN__css/font-face.css' rel='stylesheet' type='text/css'>
    <link href="__ADMIN__css/bootstrap.min.css" rel="stylesheet">
    <link href="__ADMIN__css/nifty.min.css" rel="stylesheet">
    <link href="__ADMIN__css/demo/nifty-demo-icons.min.css" rel="stylesheet">
    <link href="__ADMIN__css/demo/nifty-demo.min.css" rel="stylesheet">
    <link href="__ADMIN__plugins/magic-check/css/magic-check.min.css" rel="stylesheet">
    <link href="__ADMIN__plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="__ADMIN__plugins/morris-js/morris.min.css" rel="stylesheet">
    <link href="__ADMIN__plugins/pace/pace.min.css" rel="stylesheet">

    <!--JAVASCRIPT-->
    <script src="__ADMIN__js/jquery-2.2.4.min.js"></script>
    <script src="__ADMIN__js/jquery.cookie.js"></script>
    <script src="__ADMIN__js/bootstrap.min.js"></script>
    <script src="__ADMIN__js/nifty.min.js"></script>
    <script src="__ADMIN__js/demo/nifty-demo.min.js"></script>
    <script src="__ADMIN__plugins/pace/pace.min.js"></script>
    <script src="__LAYUI__layui.js"></script>
    <script src="__ADMIN__js/myself.min.js"></script>
</head>
<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
    <div id="container" class="aside-float aside-bright effect navbar-fixed footer-fixed mainnav-lg mainnav-fixed">
        <!--NAVBAR-->
        <!--JAVASCRIPT-->
<script src="__ADMIN__plugins/bootbox/bootbox.min.js"></script>
<!--NAVBAR-->
<header id="navbar">
    <div id="navbar-container" class="boxed">
<!--Brand logo & name-->
<div class="navbar-header">
    <a href="<?php echo url('/admin'); ?>" class="navbar-brand">
        <!--<img src="__ADMIN__img/logo.png" alt="Nifty Logo" class="brand-icon">-->
        <div class="brand-title">
            <span class="brand-text">Think Engine</span>
        </div>
    </a>
</div>
<!--================================-->
<!--End brand logo & name-->

<!--Navbar Dropdown-->
<div class="navbar-content clearfix">
    <ul class="nav navbar-top-links pull-left">

        <!--Navigation toogle button-->
        <li class="tgl-menu-btn">
            <a class="mainnav-toggle" href="#">
                <i class="demo-pli-view-list"></i>
            </a>
        </li>
        <!--End Navigation toogle button-->



        <!--Notification dropdown-->
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                <i class="demo-pli-bell"></i>
                <span class="badge badge-header badge-danger"></span>
            </a>

            <!--Notification dropdown menu-->
            <div class="dropdown-menu dropdown-menu-md">
                <div class="pad-all bord-btm">
                    <p class="text-semibold text-main mar-no">你有9条新的通知.</p>
                </div>
                <div class="nano scrollable">
                    <div class="nano-content">
                        <ul class="head-list">

                            <!-- Dropdown list-->
                            <li>
                                <a href="#">
                                    <div class="clearfix">
                                        <p class="pull-left">数据库修复进度</p>
                                        <p class="pull-right">70%</p>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div style="width: 70%;" class="progress-bar">
                                            <span class="sr-only">70% Complete</span>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a href="#">
                                    <div class="clearfix">
                                        <p class="pull-left">升级进度</p>
                                        <p class="pull-right">10%</p>
                                    </div>
                                    <div class="progress progress-sm">
                                        <div style="width: 10%;" class="progress-bar progress-bar-warning">
                                            <span class="sr-only">10% Complete</span>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="#">
                                    <span class="badge badge-success pull-right">90%</span>
                                    <div class="media-left">
                                        <i class="demo-pli-data-settings icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">HDD 已满</div>
                                        <small class="text-muted">50 分钟前</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="#">
                                    <div class="media-left">
                                        <i class="demo-pli-file-edit icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">写一篇属于你的文章</div>
                                        <small class="text-muted">最后更新 8小时前</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="#">
                                    <span class="label label-danger pull-right">New</span>
                                    <div class="media-left">
                                        <i class="demo-pli-speech-bubble-7 icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">评论消息</div>
                                        <small class="text-muted">最后更新 8小时前</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="#">
                                    <div class="media-left">
                                        <i class="demo-pli-add-user-plus-star icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">新用户注册</div>
                                        <small class="text-muted">4分钟前</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li class="bg-gray">
                                <a class="media" href="#">
                                    <div class="media-left">
                                        <img class="img-circle img-sm" alt="Profile Picture" src="/static/admin/img/profile-photos/9.png">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">Lucy 给你发了一段消息</div>
                                        <small class="text-muted">30 分钟前</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li class="bg-gray">
                                <a class="media" href="#">
                                    <div class="media-left">
                                        <img class="img-circle img-sm" alt="Profile Picture" src="/static/admin/img/profile-photos/3.png">
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">Jackson 给你发了一段消息</div>
                                        <small class="text-muted">40 分钟前</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!--Dropdown footer-->
                <div class="pad-all bord-top">
                    <a href="#" class="btn-link text-dark box-block">
                        <i class="fa fa-angle-right fa-lg pull-right"></i>显示所有通知
                    </a>
                </div>
            </div>
        </li>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End notifications dropdown-->


        <!--小工具合集-->
        <li class="dropdown">
            <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-paw"></i>
            </a>

            <!--Notification dropdown menu-->
            <div class="dropdown-menu dropdown-menu-md">
                <div class="pad-all bord-btm">
                    <p class="text-semibold text-main mar-no">网站小工具</p>
                </div>
                <div class="nano scrollable">
                    <div class="nano-content">
                        <ul class="head-list">

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="http://seo.chinaz.com/?q=www.52nd.xin" target="_blank">
                                    <span class="badge badge-danger pull-right">1</span>
                                    <div class="media-left">
                                        <i class="demo-pli-data-settings icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">站长工具</div>
                                        <small class="text-muted">SEO、网站的收录、权重</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="https://www.51.la/?19277864" target="_blank">
                                    <span class="badge badge-danger pull-right">New</span>
                                    <div class="media-left">
                                        <i class="demo-pli-gear icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">访问量</div>
                                        <small class="text-muted">50la数据访问量统计</small>
                                    </div>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>

                <!--Dropdown footer-->
                <div class="pad-all bord-top">
                    <a href="javascript:layer.msg('暂无更多')" class="btn-link text-dark box-block"><i class="fa fa-angle-right fa-lg pull-right"></i>更多小工具</a>
                </div>
            </div>
        </li>
        <!--小工具合集结束-->

        <!--常用功能-->
        <li class="dropdown">
            <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle">
                <i class="fa fa-gear"></i>
            </a>

            <!--Notification dropdown menu-->
            <div class="dropdown-menu dropdown-menu-md">
                <div class="pad-all bord-btm">
                    <p class="text-semibold text-main mar-no">功能模块</p>
                </div>
                <div class="nano scrollable">
                    <div class="nano-content">
                        <ul class="head-list">

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="<?php echo url('/admin/model/index'); ?>">
                                    <span class="badge badge-danger pull-right">New</span>
                                    <div class="media-left">
                                        <i class="demo-pli-layout-grid icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">模块管理</div>
                                        <small class="text-muted">自动化的模块快速建立</small>
                                    </div>
                                </a>
                            </li>

                            <!-- Dropdown list-->
                            <li>
                                <a class="media" href="<?php echo url('/admin/menu/index'); ?>">
                                    <span class="badge badge-success pull-right">New</span>
                                    <div class="media-left">
                                        <i class="demo-pli-internet icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <div class="text-nowrap">菜单管理</div>
                                        <small class="text-muted">一览大小 便知明暗</small>
                                    </div>
                                </a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </li>
        <!--常用功能结束-->



        <!--Mega dropdown-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <li class="mega-dropdown">
            <a href="#" class="mega-dropdown-toggle">
                <i class="demo-pli-layout-grid"></i>
            </a>
            <div class="dropdown-menu mega-dropdown-menu">
                <div class="row">
                    <div class="col-sm-4 col-md-3">

                        <!--Mega menu list-->
                        <ul class="list-unstyled">
                            <li class="dropdown-header"><i class="demo-pli-file icon-fw"></i> 静态页面</li>
                            <li><a href="<?php echo url('/admin/index/fontAwesome'); ?>">font-awesome 图标</a></li>
                            <li><a href="<?php echo url('/admin/index/Ionic'); ?>">Ionic 图标</a></li>
                        </ul>

                    </div>
                    <div class="col-sm-4 col-md-3">

                        <!--Mega menu list-->
                        <ul class="list-unstyled">
                            <li class="dropdown-header"><i class="demo-pli-mail icon-fw"></i> Mailbox</li>
                            <li><a href="#"><span class="pull-right label label-danger">Hot</span>Indox</a></li>
                            <li><a href="#">Read Message</a></li>
                            <li><a href="#">Compose</a></li>
                        </ul>
                        <p class="pad-top mar-top bord-top text-sm">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes.</p>
                    </div>
                    <div class="col-sm-4 col-md-3">
                        <!--Mega menu list-->
                        <ul class="list-unstyled">
                            <li>
                                <a href="<?php echo url('/index'); ?>" target="_blank" class="media mar-btm">
                                    <span class="badge badge-success pull-right">90%</span>
                                    <div class="media-left">
                                        <i class="demo-pli-data-settings icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <p class="text-semibold text-dark mar-no">Index 模块</p>
                                        <small class="text-muted">首页模块~</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo url('/home'); ?>" target="_blank" class="media mar-btm">
                                    <div class="media-left">
                                        <i class="demo-pli-support icon-2x"></i>
                                    </div>
                                    <div class="media-body">
                                        <p class="text-semibold text-dark mar-no">DJ舞曲系统</p>
                                        <small class="text-muted">优美的界面、丰富的资源，音乐系统</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <p class="dropdown-header"><i class="demo-pli-file-jpg icon-fw"></i> 图库</p>
                        <ul class="list-unstyled list-inline text-justify">

                            <li class="pad-btm">
                                <img src="/static/admin/img//thumbs/mega-menu-2.jpg" alt="thumbs">
                            </li>
                            <li class="pad-btm">
                                <img src="/static/admin/img//thumbs/mega-menu-3.jpg" alt="thumbs">
                            </li>
                            <li class="pad-btm">
                                <img src="/static/admin/img//thumbs/mega-menu-1.jpg" alt="thumbs">
                            </li>
                            <li class="pad-btm">
                                <img src="/static/admin/img//thumbs/mega-menu-4.jpg" alt="thumbs">
                            </li>
                            <li class="pad-btm">
                                <img src="/static/admin/img//thumbs/mega-menu-5.jpg" alt="thumbs">
                            </li>
                            <li class="pad-btm">
                                <img src="/static/admin/img//thumbs/mega-menu-6.jpg" alt="thumbs">
                            </li>
                        </ul>
                        <a href="#" class="btn btn-sm btn-block btn-default">浏览图库</a>
                    </div>
                </div>
            </div>
        </li>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End mega dropdown-->

    </ul>
    <ul class="nav navbar-top-links pull-right">

        <li><a class="lang-selector" href="<?php echo url('/index/index/index'); ?>" target="_blank" title="首页"><i class="fa fa-home"></i></a></li>

        <li><a class="lang-selector" href="<?php echo url('/admin/Common/CacheClear'); ?>" title="清除缓存"><i class="fa fa-trash"></i></a></li>

        <!--User dropdown-->
        <li id="dropdown-user" class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle text-right">
                                <span class="pull-right">
                                    <!--<img class="img-circle img-user media-object" src="/static/admin/img/profile-photos/1.png" alt="Profile Picture">-->
                                    <i class="demo-pli-male ic-user"></i>
                                </span>
                <div class="username hidden-xs"><?php echo $userObj->username; ?></div>
            </a>


            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right panel-default">


                <!-- User dropdown menu -->
                <ul class="head-list">
                    <li>
                        <a href="<?php echo url('user/detail'); ?>">
                            <i class="demo-pli-male icon-lg icon-fw"></i> 我的资料
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="badge badge-danger pull-right">9</span>
                            <i class="demo-pli-mail icon-lg icon-fw"></i> 我的消息
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <!--<span class="label label-success pull-right">New</span>-->
                            <i class="demo-pli-gear icon-lg icon-fw"></i> 设置
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo url('admin/user/lockScreen'); ?>">
                            <i class="demo-pli-computer-secure icon-lg icon-fw"></i> 锁屏
                        </a>
                    </li>
                </ul>

                <!-- Dropdown footer -->
                <div class="pad-all text-right">
                    <a href="javascript:void(0)" class="btn btn-primary" onclick="loginOut()">
                        <i class="demo-pli-unlock"></i> 退出
                    </a>
                </div>
            </div>
        </li>
        <!--End user dropdown-->

        <li>
            <a href="#" class="aside-toggle navbar-aside-icon">
                <i class="pci-ver-dots"></i>
            </a>
        </li>
    </ul>
</div>
<!--End Navbar Dropdown-->

</div>
</header>
<!--END NAVBAR-->
        <!--END NAVBAR-->
        <div class="boxed">
            <!--CONTENT CONTAINER-->
            <div id="content-container">
                <!--Page Title-->
                <div id="page-title">
                    <h1 class="page-header text-overflow">网址导航分类管理</h1>

                    <!--Searchbox-->
                    <div class="searchbox">
                        <div class="input-group custom-search-form">
                            <input type="text" class="form-control" placeholder="Search..">
                            <span class="input-group-btn">
                                <button class="text-muted" type="button"><i class="demo-pli-magnifi-glass"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <!--End page title-->

                <!--Breadcrumb-->
                <ol class="breadcrumb">
					<li><a href="<?php echo url('/admin'); ?>">首页</a></li>
					<li><a href="<?php echo url('link_cate/add'); ?>">新增</a></li>
					<li class="active">列表</li>
                </ol>
                <!--End breadcrumb-->

                <!--Page content-->
                <div id="page-content">
					<div class="row">
					<div class="panel">
					    <div class="panel-heading">
					        <h3 class="panel-title">网址导航分类列表</h3>
					    </div>
					
					    <!--Data Table-->
					    <div class="panel-body">
					        <div class="pad-btm form-inline">
                                <div class="row">
                                    <div class="col-sm-6 table-toolbar-left">
                                        <a class="btn btn-purple" href="<?php echo url('/admin/link_cate/add'); ?>"><i class="fa fa-plus-circle"></i> 新增</a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-danger" onclick="delChecked('<?php echo url('/admin/link_cate/delete'); ?>',this,'form-horizontal')" title="批量删除"><i class="fa fa-trash-o"></i></button>
                                        </div>
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" class="btn btn-primary pull-left" onclick="checkThis(this,3)">全选</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 table-toolbar-right">
                                        <form action="<?php echo url('/admin/link_cate/index'); ?>" method="get">
                                            <div class="form-group">
                                                <input id="demo-input-search2" type="text" placeholder="输入关键词搜索"
                                                       class="form-control" autocomplete="off" name="keyword" value="<?php echo \think\Request::instance()->get('keyword'); ?>">
                                            </div>
                                            <div class="btn-group">
                                                <button class="btn btn-default"><i class="fa fa-search"></i></button>
                                                <?php if(!empty(\think\Request::instance()->get('keyword'))): ?><a href="<?php echo url('admin/link_cate/index'); ?>" class="btn btn-default">清空搜索</a><?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                </div>
					        </div>
					        <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-bordered table-hover toggle-circle form-horizontal">
                                    <thead>
                                    <tr>
                                        <th width="20"></th>
                                        <th>ID</th>
                                        
<th>名称</th>
<th>排序</th>

                                        <th>操作</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "暂无数据" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                                    <tr>
                                        <td>
                                            <input id="list-<?php echo $key; ?>" class="magic-checkbox" type="checkbox" value="<?php echo $item['id']; ?>" >
                                            <label for="list-<?php echo $key; ?>"></label>
                                        </td>
                                        <td><?php echo $item['id']; ?></td>
                                        
<td><?php echo $item['name']; ?></td>
<td><?php echo $item['rank']; ?></td>

                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo url('link_cate/edit', ['id'=>$item['id']]); ?>" class="btn-xs btn btn-dark">编辑</a>
                                                <a href="<?php echo url('link_cate/delete', ['id'=>$item['id']]); ?>" onClick='return confirm("确定要删除吗？");' class="btn-xs btn btn-danger">删除</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; endif; else: echo "暂无数据" ;endif; ?>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <?php echo $list->render(); ?>
                                </div>
                            </div>
					        </div>
					    </div>
					    <!--End Data Table-->
					</div>
                </div>
                <!--End page content-->
            </div>
            <!--END CONTENT CONTAINER-->
            <!--ASIDE-->
            <!--ASIDE-->
<!--===================================================-->
<aside id="aside-container">
    <div id="aside">
        <div class="nano">
            <div class="nano-content">

                <!--Nav tabs-->
                <!--================================-->
                <ul class="nav nav-tabs nav-justified">
                    <li class="active">
                        <a href="#demo-asd-tab-1" data-toggle="tab">
                            <i class="demo-pli-speech-bubble-7"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#demo-asd-tab-2" data-toggle="tab">
                            <i class="demo-pli-information icon-fw"></i> Report
                        </a>
                    </li>
                    <li>
                        <a href="#demo-asd-tab-3" data-toggle="tab">
                            <i class="demo-pli-wrench icon-fw"></i> Settings
                        </a>
                    </li>
                </ul>
                <!--================================-->
                <!--End nav tabs-->



                <!-- Tabs Content -->
                <!--================================-->
                <div class="tab-content">

                    <!--First tab (Contact list)-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div class="tab-pane fade in active" id="demo-asd-tab-1">
                        <p class="pad-hor mar-top text-semibold text-main">
                            <span class="pull-right badge badge-warning">3</span> Family
                        </p>

                        <!--Family-->
                        <div class="list-group bg-trans">
                            <a href="#" class="list-group-item">
                                <div class="media-left pos-rel">
                                    <img class="img-circle img-xs" src="/static/admin/img/profile-photos/2.png" alt="Profile Picture">
                                    <i class="badge badge-success badge-stat badge-icon pull-left"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Stephen Tran</p>
                                    <small class="text-muted">Availabe</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item">
                                <div class="media-left pos-rel">
                                    <img class="img-circle img-xs" src="/static/admin/img/profile-photos/7.png" alt="Profile Picture">
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Brittany Meyer</p>
                                    <small class="text-muted">I think so</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item">
                                <div class="media-left pos-rel">
                                    <img class="img-circle img-xs" src="/static/admin/img/profile-photos/1.png" alt="Profile Picture">
                                    <i class="badge badge-info badge-stat badge-icon pull-left"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Jack George</p>
                                    <small class="text-muted">Last Seen 2 hours ago</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item">
                                <div class="media-left pos-rel">
                                    <img class="img-circle img-xs" src="/static/admin/img/profile-photos/4.png" alt="Profile Picture">
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Donald Brown</p>
                                    <small class="text-muted">Lorem ipsum dolor sit amet.</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item">
                                <div class="media-left pos-rel">
                                    <img class="img-circle img-xs" src="/static/admin/img/profile-photos/8.png" alt="Profile Picture">
                                    <i class="badge badge-warning badge-stat badge-icon pull-left"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Betty Murphy</p>
                                    <small class="text-muted">Idle</small>
                                </div>
                            </a>
                            <a href="#" class="list-group-item">
                                <div class="media-left pos-rel">
                                    <img class="img-circle img-xs" src="/static/admin/img/profile-photos/9.png" alt="Profile Picture">
                                    <i class="badge badge-danger badge-stat badge-icon pull-left"></i>
                                </div>
                                <div class="media-body">
                                    <p class="mar-no">Samantha Reid</p>
                                    <small class="text-muted">Offline</small>
                                </div>
                            </a>
                        </div>

                        <hr>
                        <p class="pad-hor text-semibold text-main">
                            <span class="pull-right badge badge-success">Offline</span> Friends
                        </p>

                        <!--Works-->
                        <div class="list-group bg-trans">
                            <a href="#" class="list-group-item">
                                <span class="badge badge-purple badge-icon badge-fw pull-left"></span> Joey K. Greyson
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge badge-info badge-icon badge-fw pull-left"></span> Andrea Branden
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge badge-success badge-icon badge-fw pull-left"></span> Johny Juan
                            </a>
                            <a href="#" class="list-group-item">
                                <span class="badge badge-danger badge-icon badge-fw pull-left"></span> Susan Sun
                            </a>
                        </div>


                        <hr>
                        <p class="pad-hor mar-top text-semibold text-main">News</p>

                        <div class="pad-hor">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetuer
                                <a data-title="45%" class="add-tooltip text-semibold" href="#">adipiscing elit</a>, sed diam nonummy nibh. Lorem ipsum dolor sit amet.
                            </p>
                            <small class="text-muted"><em>Last Update : Des 12, 2014</em></small>
                        </div>


                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--End first tab (Contact list)-->


                    <!--Second tab (Custom layout)-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div class="tab-pane fade" id="demo-asd-tab-2">

                        <!--Monthly billing-->
                        <div class="pad-all">
                            <p class="text-semibold text-main">Billing &amp; reports</p>
                            <p class="text-muted">Get <strong>$5.00</strong> off your next bill by making sure your full payment reaches us before August 5, 2016.</p>
                        </div>
                        <hr class="new-section-xs">
                        <div class="pad-all">
                            <span class="text-semibold text-main">Amount Due On</span>
                            <p class="text-muted text-sm">August 17, 2016</p>
                            <p class="text-2x text-thin text-main">$83.09</p>
                            <button class="btn btn-block btn-success mar-top">Pay Now</button>
                        </div>


                        <hr>

                        <p class="pad-hor text-semibold text-main">Additional Actions</p>

                        <!--Simple Menu-->
                        <div class="list-group bg-trans">
                            <a href="#" class="list-group-item"><i class="demo-pli-information icon-lg icon-fw"></i> Service Information</a>
                            <a href="#" class="list-group-item"><i class="demo-pli-mine icon-lg icon-fw"></i> Usage Profile</a>
                            <a href="#" class="list-group-item"><span class="label label-info pull-right">New</span><i class="demo-pli-credit-card-2 icon-lg icon-fw"></i> Payment Options</a>
                            <a href="#" class="list-group-item"><i class="demo-pli-support icon-lg icon-fw"></i> Message Center</a>
                        </div>


                        <hr>

                        <div class="text-center">
                            <div><i class="demo-pli-old-telephone icon-3x"></i></div>
                            Questions?
                            <p class="text-lg text-semibold text-main"> (415) 234-53454 </p>
                            <small><em>We are here 24/7</em></small>
                        </div>
                    </div>
                    <!--End second tab (Custom layout)-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


                    <!--Third tab (Settings)-->
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <div class="tab-pane fade" id="demo-asd-tab-3">
                        <ul class="list-group bg-trans">
                            <li class="pad-top list-header">
                                <p class="text-semibold text-main mar-no">Account Settings</p>
                            </li>
                            <li class="list-group-item">
                                <div class="pull-right">
                                    <input class="toggle-switch" id="demo-switch-1" type="checkbox" checked>
                                    <label for="demo-switch-1"></label>
                                </div>
                                <p class="mar-no">Show my personal status</p>
                                <small class="text-muted">Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</small>
                            </li>
                            <li class="list-group-item">
                                <div class="pull-right">
                                    <input class="toggle-switch" id="demo-switch-2" type="checkbox" checked>
                                    <label for="demo-switch-2"></label>
                                </div>
                                <p class="mar-no">Show offline contact</p>
                                <small class="text-muted">Aenean commodo ligula eget dolor. Aenean massa.</small>
                            </li>
                            <li class="list-group-item">
                                <div class="pull-right">
                                    <input class="toggle-switch" id="demo-switch-3" type="checkbox">
                                    <label for="demo-switch-3"></label>
                                </div>
                                <p class="mar-no">Invisible mode </p>
                                <small class="text-muted">Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. </small>
                            </li>
                        </ul>


                        <hr>

                        <ul class="list-group pad-btm bg-trans">
                            <li class="list-header"><p class="text-semibold text-main mar-no">Public Settings</p></li>
                            <li class="list-group-item">
                                <div class="pull-right">
                                    <input class="toggle-switch" id="demo-switch-4" type="checkbox" checked>
                                    <label for="demo-switch-4"></label>
                                </div>
                                Online status
                            </li>
                            <li class="list-group-item">
                                <div class="pull-right">
                                    <input class="toggle-switch" id="demo-switch-5" type="checkbox" checked>
                                    <label for="demo-switch-5"></label>
                                </div>
                                Show offline contact
                            </li>
                            <li class="list-group-item">
                                <div class="pull-right">
                                    <input class="toggle-switch" id="demo-switch-6" type="checkbox" checked>
                                    <label for="demo-switch-6"></label>
                                </div>
                                Show my device icon
                            </li>
                        </ul>



                        <hr>

                        <p class="pad-hor text-semibold text-main mar-no">Task Progress</p>
                        <div class="pad-all">
                            <p>Upgrade Progress</p>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-success" style="width: 15%;"><span class="sr-only">15%</span></div>
                            </div>
                            <small class="text-muted">15% Completed</small>
                        </div>
                        <div class="pad-hor">
                            <p>Database</p>
                            <div class="progress progress-sm">
                                <div class="progress-bar progress-bar-danger" style="width: 75%;"><span class="sr-only">75%</span></div>
                            </div>
                            <small class="text-muted">17/23 Database</small>
                        </div>

                    </div>
                    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
                    <!--Third tab (Settings)-->

                </div>
            </div>
        </div>
    </div>
</aside>
<!--===================================================-->
<!--END ASIDE-->
            <!--END ASIDE-->
            <!--MAIN NAVIGATION-->
            <!--MAIN NAVIGATION-->
<!--===================================================-->
<nav id="mainnav-container">
    <div id="mainnav">
        <!--Menu-->
        <!--================================-->
        <div id="mainnav-menu-wrap">
            <div class="nano">
                <div class="nano-content">

                    <!--Profile Widget-->
                    <div id="mainnav-profile" class="mainnav-profile">
                        <div class="profile-wrap">
                            <div class="pad-btm">
                                <span class="label label-<?php if($userObj->sex == 0): ?>danger<?php elseif($userObj->sex == 1): ?>info<?php else: ?>warning<?php endif; ?> pull-right"><?php if($userObj->sex == 0): ?>女<?php elseif($userObj->sex == 1): ?>男<?php else: ?>保密<?php endif; ?></span>
                                <img class="img-circle img-sm img-border" src="<?php if($userObj->photo == ''): ?>/static/admin/img/profile-photos/1.png<?php else: ?><?php echo $userObj->photo; endif; ?>" alt="<?php echo $userObj->username; ?>">
                            </div>
                            <a href="#profile-nav" class="box-block" data-toggle="collapse" aria-expanded="false">
                                <span class="pull-right dropdown-toggle">
                                    <i class="dropdown-caret"></i>
                                </span>
                                <p class="mnp-name"><?php echo $userObj->username; ?></p>
                                <span class="mnp-desc"><?php if($userObj->email): ?><?php echo $userObj->email; else: ?><?php echo $userObj->telephone; endif; ?></span>
                            </a>
                        </div>
                        <div id="profile-nav" class="collapse list-group bg-trans">
                            <a href="<?php echo url('admin/user/edit',['id'=>$userObj->id]); ?>" class="list-group-item">
                                <i class="demo-pli-male icon-lg icon-fw"></i> 我的资料
                            </a>
                            <a href="#" class="list-group-item">
                                <i class="demo-pli-gear icon-lg icon-fw"></i> 设置
                            </a>
                            <a href="<?php echo url('admin/user/lockScreen'); ?>" class="list-group-item">
                                <i class="demo-pli-computer-secure icon-lg icon-fw"></i> 锁屏
                            </a>
                            <a href="javascript:void(0)" class="list-group-item" onclick="loginOut()">
                                <i class="demo-pli-unlock icon-lg icon-fw"></i> 退出
                            </a>
                        </div>
                    </div>

                    <!--Shortcut buttons-->
                    <!--================================-->
                    <div id="mainnav-shortcut">
                        <ul class="list-unstyled">
                            <li class="col-xs-3" data-content="我的资料">
                                <a class="shortcut-grid" href="javascript:void(0)">
                                    <i class="demo-psi-male"></i>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="消息">
                                <a class="shortcut-grid" href="#">
                                    <i class="demo-psi-speech-bubble-3"></i>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="Activity">
                                <a class="shortcut-grid" href="#">
                                    <i class="demo-psi-thunder"></i>
                                </a>
                            </li>
                            <li class="col-xs-3" data-content="锁屏">
                                <a class="shortcut-grid" href="<?php echo url('/admin/user/lockScreen'); ?>">
                                    <i class="demo-psi-lock-2"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!--================================-->
                    <!--End shortcut buttons-->
                    <ul id="mainnav-menu" class="list-group">
                        <!--Category name-->
                        <li class="list-header">基本配置</li>
                        <!--Menu list item-->
                        <?php if(is_array($menuAry) || $menuAry instanceof \think\Collection || $menuAry instanceof \think\Paginator): $i = 0; $__LIST__ = $menuAry;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;if($item['children'] != ''): ?>
                        <li>
                            <a href="<?php echo url($item['url']); ?>">
                                <i class="<?php echo $item['icon']; ?>"></i>
                                <span class="menu-title"><?php echo $item['name']; ?></span>
                                <i class="arrow"></i>
                            </a>
                            <!--Submenu-->
                            <ul class="collapse">
                                <?php if(is_array($item['children']) || $item['children'] instanceof \think\Collection || $item['children'] instanceof \think\Paginator): $i = 0; $__LIST__ = $item['children'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$value): $mod = ($i % 2 );++$i;?>
                                <li <?php if(adminMenuUrl($value['url']) == 'true'): ?>class="active-link"<?php endif; ?>><a href="<?php echo url($value['url']); ?>"><i class="<?php echo $value['icon']; ?>"></i><?php echo $value['name']; ?></a></li>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </li>
                        <?php else: ?>
                        <li <?php if($item['url'] == $urlRequest): ?>class="active-link"<?php endif; ?>>
                            <a href="<?php echo $item['url']; ?>">
                                <i class="<?php echo $item['icon']; ?>"></i>
                                <span class="menu-title"><?php echo $item['name']; ?></span>
                            </a>
                        </li>
                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <!--Widget-->
                    <!--================================-->
                    <div class="mainnav-widget">

                        <!-- Show the button on collapsed navigation -->
                        <div class="show-small">
                            <a href="#" data-toggle="menu-widget" data-target="#demo-wg-server">
                                <i class="demo-pli-monitor-2"></i>
                            </a>
                        </div>

                        <!-- Hide the content on collapsed navigation -->
                        <div id="demo-wg-server" class="hide-small mainnav-widget-content">
                            <ul class="list-group">
                                <li class="list-header pad-no pad-ver">Server Status</li>
                                <li class="mar-btm">
                                    <span class="label label-primary pull-right">15%</span>
                                    <p>CPU Usage</p>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar progress-bar-primary" style="width: 15%;">
                                            <span class="sr-only">15%</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="mar-btm">
                                    <span class="label label-purple pull-right">75%</span>
                                    <p>Bandwidth</p>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar progress-bar-purple" style="width: 75%;">
                                            <span class="sr-only">75%</span>
                                        </div>
                                    </div>
                                </li>
                                <li class="pad-ver"><a href="#" class="btn btn-success btn-bock">View Details</a></li>
                            </ul>
                        </div>
                    </div>
                    <!--================================-->
                    <!--End widget-->
                </div>
            </div>
        </div>
        <!--End menu-->
    </div>
</nav>
<!--END MAIN NAVIGATION-->
            <!--END MAIN NAVIGATION-->
        </div>
        <!-- FOOTER -->
        <!--===================================================-->
<footer id="footer">
    <div class="show-fixed pull-right">
        You have <a href="#" class="text-bold text-main"><span class="label label-danger">3</span> pending action.</a>
    </div>
    <p class="pad-lft">&#0169; 2017-2020 志远出品 Think Engine.</p>
</footer>
        <!-- END FOOTER -->
        <!-- SCROLL PAGE BUTTON -->
        <button class="scroll-top btn">
            <i class="pci-chevron chevron-up"></i>
        </button>
    </div>
    <!-- END OF CONTAINER -->
</body>
</html>

