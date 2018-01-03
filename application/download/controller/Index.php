<?php
namespace app\download\controller;

use think\Controller;

class Index extends Controller {
    public function index(){
        return view('/index');
    }
}
