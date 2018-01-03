<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Loader;
use app\common\model\ModelAction as Models;

class Model extends Controller
{
    public $objName = 'model';
    //此处为模型模块的增删查改
    public function index(){
        $keyword = input("keyword");
        $where = "1=1";
        if($keyword){
            $where .= " and modelname like '%{$keyword}%' or id = '{$keyword}'";
        }
        $list = Db::name('model')->where($where)->order('id desc')->paginate(20);
        $this->assign('list', $list);
        return view('list');
    }

    public function edit(){
        $id = Request::instance()->param('id');
        $res = db('model')->find($id);
        $this->assign('ObjAry',$res);
        return view('edit');
    }

    public function add(){
        return view('add');
    }

    public function save(){
        $cate = input('post.usercategory');
        $issystem = input('post.issystem');
        $tablename = config('database.prefix').input('post.tablename');
        $res = db('model')->where('tablename',$tablename)->find();
        if($res){$this->error('表已存在，不能创建！');exit();}
        $data = [
            'usercategory' => empty($cate) ? 2 : $cate,
            'issystem' => empty($issystem) ? 2 : $issystem,
            'modelname' => input('post.modelname'),
            'tablename' => $tablename,
            'addtpl' => empty(input('post.addtpl')) ? 'add.html' : input('post.addtpl') ,
            'listtpl' => empty(input('post.listtpl')) ? 'list.html' : input('post.listtpl') ,
            'showtpl' => empty(input('post.showtpl')) ? 'show.html' : input('post.showtpl') ,
        ];
        Db::name('model')->insert($data);
        $insert_id = Db::name('model')->getLastInsID();
        //创建数据库
        $this->createTable();
        if ($insert_id) {
            $this->success($data['modelname'] . " 已添加成功", url('admin/model/index'), $insert_id, 0);
            exit();
        } else {
            $this->error('添加失败');
            exit();
        }
    }

    //创建表并设置主键
    public function createTable() {
        $tableIsExist = Db::query("show tables like '".config('database.prefix').input('post.tablename')."'");
        if(!$tableIsExist) {
            $sql = "CREATE TABLE `" . config('database.prefix') . input('post.tablename') . "` (`id` int(11) unsigned NOT NULL auto_increment, PRIMARY KEY  (`id`) ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
            Db::query($sql);
            //栏目字段
            if (input("post.usercategory") == 1) {
                $sql2 = "ALTER TABLE `" . config('database.prefix') . input('post.tablename') . "` ADD `pid` int (11) NULL COMMENT '栏目ID'";
                Db::query($sql2);
            }
        }
    }

    public function update(){
        $id = input('post.id');
        if(empty($id)){$this->error('请选择保存的数据');}
        $cate = input('post.usercategory');
        $issystem = input('post.issystem');
        $data = [
            'usercategory' => $cate,
            'issystem' => $issystem,
            'modelname' => input('post.modelname'),
            'addtpl' => input('post.addtpl'),
            'listtpl' => input('post.listtpl'),
            'showtpl' => input('post.showtpl'),
        ];
        $row = Db::name('model')->where('id',$id)->update($data);
        if ($row) {
            $this->success($data['modelname'] . " 修改成功", url('/admin/model/index'), '', 0);
            exit();
        } else {
            $this->error('修改失败');
            exit();
        }
    }

    public function delete(){
        $id = input('id');
        $Ary = db("model")->where("id",$id)->find();
        if($Ary['issystem'] == 1){
            $this->error('系统模块不能删除.',null ,'',0);
        }
        Loader::model("ModelAction")->deleteTable($id);
        $row = db('model')->delete($id);
        if ($row){
            $this->success(" 删除成功", url('/admin/model/index'), '', 0);
        }else{
            $this->error('删除失败.', null,'',0);
        }
    }


    public function createFiles() {
        $mid = input("param.mid");
        $info = db("field")->where(['mid'=>$mid,'listshow'=> 1])->order("listorder desc")->select();
        $type = input("param.type");
        $modelInfo = db("model")->where("id",input("param.mid"))->find();
        $objName = str_replace(config('database.prefix'), "", $modelInfo['tablename']);
        //后台控制器、模型、模板
        if ($type == "admin" || $type == 'all') {
            if ($modelInfo['id'] < 1) {
                $this->error('系统模块,controller类生成失败');exit();
            }
            //后台控制器
            $o = APP_PATH."admin/controller/Sample.php";
            $target = APP_PATH."admin/controller/". $this->transName($objName) .".php";
            $contents = file_get_contents($o);
            $str = str_replace("Sample",$this->transName($objName),$contents);
            file_put_contents($target, $str);
            //模型生成
            $o = APP_PATH."common/model/Sample.php";
            $target = APP_PATH."common/model/". $this->transName($objName) .".php";
            $contents = file_get_contents($o);
            $str = str_replace("Sample",$this->transName($objName),$contents);
            file_put_contents($target, $str);

            //后台添加页模板
            $o = APP_PATH."admin/view/index/sample_add.html";//配置模板打开路径
            $dir_list = APP_PATH."admin/view/". $objName ."/";//视图模板文件夹
            if( !is_dir( $dir_list ) ){
                mkdir( $dir_list, 0777 );
            }
            $target = APP_PATH."admin/view/". $objName ."/add.html";//配置写入路径
            $contents = file_get_contents($o);

            $string = $this->CreateAdd($info);//获取字段值
            $newHtml = str_replace('{body}',$string,$contents);//替换中间的字段
            $newHtml = str_replace("Sample",$objName,$newHtml);
            $newHtml = str_replace("样本",$modelInfo['modelname'],$newHtml);
            file_put_contents($target,$newHtml);

            //后台列表页模板
            $o_list = APP_PATH."admin/view/index/sample_list.html";
            $target_list = APP_PATH."admin/view/". $objName ."/list.html";//配置写入路径
            $contents_list = file_get_contents($o_list);
            $th = "\r\n";
            $td = "\r\n";
            foreach ($info as $key => $value){
                if($value['listshow'] == 1){
                    $th .= '<th>'. $value['name'] ."</th>\r\n";
                    if (in_array($value["formtype"],array("radio","checkbox","select","xiala"))) {
                        $td .= '<td>{:getFieldValue("' . $value['fieldname'] . '",$item.' . $value['fieldname'] . ','. $mid .")}</td>\r\n";
                    }elseif(in_array($value['fieldname'], array("create_time", 'update_time', 'addtime','time'))){
                        $td .= '<td>{$item.' . $value['fieldname'] . "|date='Y-m-d H:i:s',###}</td>\r\n";
                    }else{
                        $td .= '<td>{$item.' . $value['fieldname'] . "}</td>\r\n";
                    }
                }
            }//foreach
            $newHtml = str_replace('{header}',$th,$contents_list);//替换中间的字段
            $newHtml = str_replace('{body}',$td,$newHtml);//替换中间的字段
            $newHtml = str_replace("sample",$objName,$newHtml);
            $newHtml = str_replace("样本",$modelInfo['modelname'],$newHtml);
            file_put_contents($target_list,$newHtml);
        }
        if ($type == "index" || $type == 'all') {
            //前台控制器
            $o = APP_PATH."admin/controller/Sample.php";
            $target = APP_PATH."index/controller/". ucfirst($objName) .".php";
            $contents = file_get_contents($o);
            $str = str_replace("sample",$objName,$contents);
            file_put_contents($target, $str);
        }
        $this->success("控制器、模板、模型已生成！TIPS：前台模板页以及控制器需手动生成.", url('/admin/model/index'), [], 0);
    }

    /**
     * TP5 文件命名
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $name
     * @return string
     */
    public function transName($name){
        if(strpos($name, '_')){
            $arr = explode("_",$name);
            $fileName = '';
            foreach ($arr as $item){
                $fileName .= ucfirst($item);
            }
        }else{
            $fileName = ucfirst($name);
        }
        return $fileName;
    }

    /**
     * 添加页字段
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @param $arr
     * @return string
     */
    public function CreateAdd($arr){
        $string = "";
        foreach ($arr as $key => $value){
            $setting = unserialize($value['setting']);
            //校验规则
            if($value['not_null'] == 1){
                if(!empty($value['pattern'])){
                    $datatype = 'datatype="'. $value['pattern'] .'"';
                }else{
                    $datatype = 'datatype="*" nullmsg="'. $value['errortips'] .'"';
                }
            }else{
                $datatype = '';
            }
            if($value['isshow'] == 1) {
                if ($value['formtype'] == 'input') {
                    $string .= '
                <!--text-->
                <div class="form-group">
                    <label class="col-md-3 control-label">' . $value['name'] . '</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" placeholder="' . $value['tips'] . '" name="' . $value['fieldname'] . '" value="{$Obj->' . $value['fieldname'] . '}" ' . $datatype .'>
                    </div>
                </div>
                ';
                } elseif ($value['formtype'] == 'password') {
                    $string .= '
                <!--password-->
                <div class="form-group">
                    <label class="col-md-3 control-label">' . $value['name'] . '</label>
                    <div class="col-md-5">
                        <input type="password" class="form-control" placeholder="' . $value['tips'] . '" name="' . $value['fieldname'] . '" value="{$Obj->' . $value['fieldname'] . '}" ' . $datatype .'>
                    </div>
                </div>
                ';
                } elseif ($value['formtype'] == 'textarea') {
                    $string .= '
                <!--textarea-->
                <div class="form-group">
                    <label class="col-md-3 control-label">' . $value['name'] . '</label>
                    <div class="col-md-7">
                        <textarea rows="9" class="form-control" name="' . $value['fieldname'] . '" placeholder="' . $value['tips'] . '">{$Obj->' . $value['fieldname'] . '}</textarea>
                    </div>
                </div>
                ';
                } elseif ($value['formtype'] == 'date') {
                    $string .= '
                    <!--date-->
                    <div class="form-group">
                        <label class="col-md-3 control-label">' . $value['name'] . '</label>
                        <div class="col-md-4">
                            <div class="input-group date">
                                <input type="text" class="form-control layui-date-time" value="{$Obj->' . $value['fieldname'] . '}" placeholder="' . $value['tips'] . '" ' . $datatype .'>
                                <span class="input-group-addon"><i class="demo-pli-calendar-4"></i></span>
                            </div>
                        </div>
                    </div>
                ';
                } elseif ($value['formtype'] == 'image') {
                    $string .= '
                    <div class="form-group">
                        <label class="col-md-3 control-label">' . $value['name'] . '</label>
                        <div class="col-md-5 input-group">
                            <input type="text" class="form-control data-cropper" name="' . $value['fieldname'] . '" value="{$Obj->' . $value['fieldname'] . '}" ' . $datatype . ' onclick="viewPhoto(this, \'.data-cropper\')">
                            <span class="input-group-addon"><a href="JavaScript:void(0)" class="fa fa-cut" onclick="cropper(this)"></a></span>
                        </div>
                    </div>
                ';
                } elseif ($value['formtype'] == 'xiala') {
                    $contentAry = explode("\n", trim($setting["content"]));
                    $temp = "";
                    foreach ($contentAry as $kk => $item) {
                        $cAry = explode("|", trim($item));
                        if ($setting["default"] == $cAry[1]) {
                            $selectStr = "selected";
                        }
                        $temp .= '<option value="' . $cAry[1] . '" ' . $selectStr . '>' . $cAry[0] . '</option>';
                        $selectStr = "";
                    }
                    $string .= '
                    <div class="form-group">
                        <label class="col-md-3 control-label">' . $value['name'] . '</label>
                        <div class="col-md-7">
                            <select class="selectpicker col-xs-6 col-sm-4 col-md-6 col-lg-4" name="' . $value['fieldname'] . '" data-style="btn-primary" ' . $datatype .'>
                                ' . $temp . '
                            </select>
                        </div>
                    </div>
                ';
                } elseif ($value['formtype'] == 'radio') {
                    $contentAry = explode("\n", trim($setting["content"]));
                    $tempRadio = "";
                    foreach ($contentAry as $kk => $item) {
                        $cAry = explode("|", trim($item));
                        if ($setting["default"] == $cAry[1]) {
                            $selectStr = "checked";
                        }
                        $tempRadio .= '<input id="radio-' . $kk . '" class="magic-radio" type="radio" name="' . $value['fieldname'] . '" value="' . $cAry[1] . '" ' . $selectStr . '>\r\n';
                        $tempRadio .= '<label for="radio-' . $kk . '">' . $cAry[0] . '</label>';
                        $selectStr = "";
                    }
                    $string .= '
                    <!--Radio-->
                    <div class="form-group">
                        <label class="col-md-3 control-label">' . $value['name'] . '</label>
                        <div class="col-md-5">
                            <div class="radio">
    
                                ' . $tempRadio . '
    
                            </div>
                        </div>
                    </div>
                ';
                } elseif ($value['formtype'] == 'checkbox') {
                    $contentAry = explode("\n", trim($setting["content"]));
                    $tempCheckbox = "";
                    foreach ($contentAry as $kk => $item) {
                        $cAry = explode("|", trim($item));
                        if ($setting["default"] == $cAry[1]) {
                            $selectStr = "checked";
                        }
                        $tempCheckbox .= '<input id="checkbox-' . $kk . '" class="magic-checkbox" type="checkbox" name="' . $value['fieldname'] . '" value="' . $cAry[1] . '" ' . $selectStr . '>';
                        $tempCheckbox .= '<label for="checkbox-' . $kk . '">' . $cAry[0] . '</label>';
                        $selectStr = "";
                    }
                    $string .= '
                    <!--Checkbox-->
                    <div class="form-group">
                        <label class="col-md-3 control-label">' . $value['name'] . '</label>
                        <div class="col-md-5">
                            <div class="radio">
    
                                ' . $tempCheckbox . '
    
                            </div>
                        </div>
                    </div>
                ';
                } elseif ($value['formtype'] == 'editor') {
                    $string .= '
                <!--editor-->
                <div class="form-group">
                    <label class="col-md-3 control-label">' . $value['name'] . '</label>
                    <div class="col-md-8">
                        <textarea name="' . $value['fieldname'] . '" cols="30" rows="10" class="form-control content" ' . $datatype . '>{$Obj->' . $value['fieldname'] . '}</textarea>
                    </div>
                </div>
                ';
                } elseif ($value['formtype'] == 'area') {
                    $string .= '
                    <!--area-->
                        <div class="form-group">
                            <label class="col-md-3 control-label">' . $value['name'] . '</label>
                            <div class="col-md-5 input-group">
                                <input type="text" class="form-control" value="{$data.areaInfo}" disabled ' . $datatype .'>
                                <span class="input-group-addon"><a href="JavaScript:void(0)" class="fa fa-pencil-square-o" onclick="getProvince(this,false)"></a></span>
                            </div>
                        </div>
                    ';
                }
            }//isShowEnd
        }
        return $string;
    }
}
