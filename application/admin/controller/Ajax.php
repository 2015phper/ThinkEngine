<?php
namespace app\admin\controller;

use think\Controller;
use app\common\model\SampleModel;

class Ajax extends Controller {

    function getTags() {
        $title = $_POST['title'];
        return json(getTags($title));
    }
}
