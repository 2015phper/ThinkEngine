<?php
namespace app\admin\controller;

use app\common\model\Attachment;
use think\Controller;
use app\common\model\Picture as PictureModel;
use think\File;

class picture extends Controller {
    
    public function index(){
        $list = db('picture')->order('id desc')->paginate(20);
        $Obj = new PictureModel();
        foreach ($list as $key => $value){
            $list[$key]['number'] = $Obj->getPhotoCount($value['id']);
        }
        $this->assign('Obj', $Obj);
        $this->assign('list', $list);
        return view('list');
    }
    
    public function edit(){
        $cate = db("picture_cate")->select();
        $this->assign("cate", $cate);
        $id = input("id");
        $Obj = PictureModel::get($id);
        $this->assign('Obj',$Obj);

        if($Obj->photo){
            $attachment = new Attachment();
            $picture = $attachment->field('id, thumb, name, filesize')->where("id in ({$Obj->photo})")->order('id desc')->select();
            foreach ($picture as $key => $value){
                $picture[$key]['filesize'] = self::getSize($value['filesize'], 2);
            }
        }
        $this->assign('picture', $picture);
        return view('add');
    }

    /**
     * 计算大小
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $size 字节数
     * @param int $format 0字节, 1KB, 2MB, 3GB
     * @param int $float  精确小数
     * @return string
     */
    function getSize($size, $format = 0, $float = 2) {
        $size /= pow(1024, $format);
        return number_format($size, $float);
    }
    
    public function add(){
        $cate = db("picture_cate")->select();
        $this->assign("cate", $cate);
        return view('add');
    }

    public function save(){
        $data = input('post.');
        $id = $data['id'];
        $Obj = new PictureModel();
        if($id){
            $Obj = $Obj->get(input('post.id'));
        }
        $rows = $Obj->allowField(true)->save($data);
        if($rows){
            $this->success(empty($id) ? '添加成功':'更新成功', url('/admin/picture/edit', ['id'=>$Obj->id]), [], 0);
        }else{
            $this->error('操作失败，请重试');die;
        }
    }

    /**
     * 删除图库里面的图像
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function remove(){
        $aid = input('post.id');
        if(!$aid){
            $this->error("请选择要移除的图片");
        }
        $url = request()->server('HTTP_REFERER');
        $url = pathinfo($url);
        $id = $url['filename'];
        $Obj = PictureModel::get($id);
        $ids = explode(",", $Obj->photo);
        foreach ($ids as $key => $item){
            if($aid == $item){
                unset($ids[$key]);
            }
        }
        $arr = array();
        foreach ($ids as $value){
            if($value){
                $arr[] = $value;
            }
        }
        $data['photo'] = implode(',', $arr);
        $row = $Obj->isUpdate(true)->save($data);
        if($row){
            $attachment = Attachment::get($aid);
            $name = $attachment->name;
            @unlink(ROOT_PATH. 'public'. $attachment->filepath);
            @unlink(ROOT_PATH. 'public'. $attachment->thumb);
            $attachment->delete();
            $this->success("已成功图片（{$name}）移出{$Obj->name}.");
        }
        $this->error("移除图片失败");
    }

    public function setCover(){
        $cover = input("post.id");
        $id = input("post.aid");
        $picture = PictureModel::get($id);
        $picture->cover = $cover;
        $rows = $picture->save();
        if(!$rows){
            $this->error("没有任何改变.");
        }
        $this->success("设置封面图成功。");
    }

    /**
     * 图片上传，已添加缩略图
     * @date 2017-12-18
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function upload(){
        // 上个链接的id
        $url = request()->server('HTTP_REFERER');
        $url = pathinfo($url);
        $id = $url['filename'];
        $Obj = PictureModel::get($id);
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . DS . 'public/uploads');
            if($info){
                $name = '/uploads/'.str_replace('\\','/',$info->getSaveName());
                $filePath = ROOT_PATH. "public".$name;  //打开的文件路径
                $dir = ROOT_PATH. "public/uploads/". date('Ymd') . "/thumb";
                if (! is_dir ( $dir )) {
                    mkdir ( $dir, 0777 );
                }
                $savePath = "./uploads/". date('Ymd') . "/thumb". str_replace(date('Ymd'), '' ,str_replace('\\','/',$info->getSaveName()));//保存缩略图的文件路径
                $Image = \think\Image::open($filePath);
                $Image->thumb(220, 165, \think\Image::THUMB_CENTER)->save($savePath);
                $data['uid'] = session("userId");
                $data['mid'] = 5;
                $data['name'] = $info->getInfo('name');
                $data['thumb'] = "/uploads/". date('Ymd') . "/thumb". str_replace(date('Ymd'), '' ,str_replace('\\','/',$info->getSaveName()));
                $data['filename'] = str_replace(date('Ymd').'/', '', str_replace('\\','/',$info->getSaveName()));
                $data['filetype'] = $info->getExtension();
                $data['filesize'] = $info->getSize();
                $data['isimage'] = 1;
                $data['filepath'] = str_replace("\\", '/', $name);
                $data['create_time'] = time();
                $aid = db("attachment")->insert($data, false, true);
                $Obj->savePhoto($id, $aid);
                $this->success("已将文件添加到：{$Obj->name}，文件名：{$info->getInfo('name')}", null, $name);
            }else{
                // 上传失败获取错误信息
                $this->error($file->getError(), null, $file->getError());
            }
        }else{
            $this->error("什么都没有");
        }
    }

    /**
     * 缩略图保存
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return string
     */
    public function thumb(){
        $base = input("thumb");
        if($base) {
            header('Content-type:text/html;charset=utf-8');
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base, $result)) {
                $type = $result[2];
                $uploadDir = ROOT_PATH . "public/uploads/" . date('Ymd') . "/";//文件夹路径
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777);
                }
                $Name = md5(microtime(true)) . '.' . $type;//生成文件名
                $result = file_put_contents($uploadDir . $Name, base64_decode(str_replace($result[1], '', $base)));
                if ($result) {
                    $path = "/uploads/" . date('Ymd') . "/" . $Name;
                    //$info = \think\Image::open(ROOT_PATH. 'public' .$path);
                    $data['uid'] = session("userId");
                    $data['mid'] = 5;
                    $data['name'] = $Name;
                    $data['filename'] = $Name;
                    $data['filetype'] = $type;
                    $data['filesize'] = 0;
                    $data['filepath'] = $path;
                    $data['isimage'] = 2; //1图片 2缩略图
                    $data['create_time'] = time();
                    $aid = db("attachment")->insert($data, false, true);
                    // 上个链接的id
                    $url = request()->server('HTTP_REFERER');
                    $url = pathinfo($url);
                    $id = $url['filename'];
                    $Obj = PictureModel::get($id);
                    $Obj->saveThumb($id, $aid);
                    return $path;
                }
            }

        }
    }

    /**
     * 删除功能已完善 支持POST GET删除 支持逗号隔开
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function delete(){
        $id = input("id");
        $rows = PictureModel::destroy($id);
        $this->success("已成功删除 {$rows} 条记录.", url("/admin/picture/index"), '', 1);
    }
    
}
