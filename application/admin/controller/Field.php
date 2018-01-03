<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Loader;
use app\common\model\Field as FieldModel;

class Field extends Controller
{
    //此处为模型模块的增删查改
    public function index(){
        $mid = Request::instance()->param('mid');
        if (empty($mid)){$this->error('请选择模型','model/index',2);}
        $where = 'mid = '.$mid;
        $list = Db::name('field')
            ->where($where)
            ->order('listorder desc')->paginate(10);
        $this->assign('list', $list);
        return view('field-list');
    }

    public function edit(){
        $id = input("id");
        $field = FieldModel::get($id);
        $field->setting = unserialize($field->setting);
        $this->assign('Obj',$field);
        return view('field-add');
    }

    public function add(){
        return view('field-add');
    }

    //逆向工程
    public function autoAdd(){
        $mid = input("mid");
        $Obj = new FieldModel();
        $res = db('model')->where('id',$mid)->find();
        $table = $res['tablename'];
        $ary = Db::query ( 'SHOW FULL COLUMNS FROM ' . $table );
        db("field")->where("mid = {$mid}")->delete();
        $data = array();
        foreach ( $ary as $kk => $vv ) {
            if ($vv ['Key'] != 'PRI') {
                $null = $vv ['Comment'] == 'YES' ? 1 : 0; //是否必填
                $typeStr = substr ( $vv ['Type'], 0, strrpos ( $vv ['Type'], '(' ) ); //字段类型
                if (empty ( $typeStr )) { //字段长度
                    $length = '';
                } else {
                    $length = str_replace ( $typeStr, "", $vv ['Type'] );
                    $length = str_replace ( '(', "", $length );
                    $length = str_replace ( ')', "", $length );
                }
                $data[] = [
                    'mid' => $mid,
                    'name' => $vv ['Comment'],
                    'fieldname' =>  $vv ['Field'],
                    'type' => $typeStr ? strtoupper ( $typeStr ) : 'TEXT',
                    'length' => $length,
                    'indexkey' => '',
                    'listshow' => 1,
                    'cansearch' => 1,
                    'isshow' => 1,
                    'tips' => '请填写' . $vv ['Comment'],
                    'disabled' => 0,
                    'listorder' => $kk,
                    'setting' => '',
                    'formtype' => 'input',
                    'errortips' => '请填写' . $vv ['Comment'],
                    'pattern' => '',
                ];
            }
        }
        $rows = $Obj->allowField(true)->saveAll($data);
        if($rows){
            $rows = count($rows);
            db("model")->where('id',$mid)->setField('lockfield',1);
            $this->success("已更新字段值，更新总数为 {$rows} 条.",url('/admin/model/index'), '', 0);
        }
        $this->error("更新失败，请重试.",url('/admin/model/index'), '', 0);
    }

    public function save(){
        $mid = input("post.mid");
        $id = input("post.id");
        $name = input("post.name");
        $fieldname = input("post.fieldname");
        $formtype = input("post.formtype");
        $setting = input("post.setting/a");
        $type = input("post.type");
        $length = input("post.length");
        $tips = input("post.tips");
        $listshow = input("post.listshow");
        $isshow = input("post.isshow");
        $cansearch = input("post.cansearch");
        $not_null = input("post.not_null");
        $listorder = input("post.listorder");
        $indexkey = input("post.indexkey");

        //重复字段
        $result = db("field")->where(["fieldname" => $fieldname, "mid" => $mid])->find();
        if(empty($id) && count($result) > 0){
            $this->error('字段已重复，请重新填写', null, '', 0);exit();
        }
        $data = [
            'name' => $name,
            'mid' => $mid,
            'fieldname' => trim($fieldname),
            'formtype' => empty($formtype) ? 'input': $formtype,
            'setting' => serialize($setting),
            'type' => empty($type) ? 'INT' : $type ,
            'length' => empty($length) ? '' : $length ,
            'tips' => empty($tips) ? '' : $tips ,
            'listshow' => empty($listshow) ? 0 : 1 ,
            'isshow' => empty($isshow) ? 0 : 1 ,
            'cansearch' => empty($cansearch) ? 0 : 1 ,
            'not_null' => empty($not_null) ? 0 : 1 ,
            'listorder' => empty($listorder) ? 0 : $listorder ,
            'indexkey' => $indexkey ,
        ];

        //创建字段
        Loader::model("Field")->createField($mid,$data);

        Db::name('field')->insert($data);
        $insert_id = Db::name('field')->getLastInsID();
        if ($insert_id) {
            $this->success($name." 字段已生成成功", url('/admin/field/index','mid='.$mid));
        } else {
            $this->error('添加失败');
            exit();
        }
    }

    public function update(){
        $data = input('post.');
        $data['listshow'] = empty($data['listshow']) ? 0 : 1;
        $data['isshow'] = empty($data['isshow']) ? 0 : 1;
        $data['cansearch'] = empty($data['cansearch']) ? 0 : 1;
        $data['not_null'] = empty($data['not_null']) ? 0 : 1;
        $data['setting'] = serialize($data['setting']);
        $Obj = Loader::model("Field");
        $result = $Obj->update($data);
        if ($result) {
            $this->success('修改成功', url('field/index',['mid'=>$data['mid']]), $result, 0);
            exit();
        } else {
            $this->error('修改失败');
            exit();
        }
    }

    public function delete(){
        $field = FieldModel::get(input("id"));
        $row = $field->delete();
        if ($row){
            $field->deleteField($field->mid, $field->getData()); //删除字段
            $this->success('删除成功.',url('field/index',['mid'=>input('mid')]),'',1);
        }else{
            $this->error('删除失败.');
        }
    }

}
