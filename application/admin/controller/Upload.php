<?php
/**
 * Created by PhpStorm.
 * User: ZhiyuanLi < 956889120@qq.com >
 * Date: 2017/5/13
 * Time: 14:07
 */

namespace app\admin\controller;

use think\Controller;
use app\common\model\Config as ConfigModel;
use app\common\model\Attachment;

class Upload extends Controller{

    public function index(){
        $type = ConfigModel::PostFix(false);
        $size = ConfigModel::MaxSize();
        return view("common/upload", ['type'=>$type,'size'=>$size]);
    }

    /**
     * 文件上传
     * @author ZhiyuanLi < 956889120@qq.com >
     */
    public function upload(){
        $file = request()->file('file');
        if($file){
            $type = ConfigModel::PostFix();
            $size = ConfigModel::MaxSize();
            $info = $file->validate(['size'=>$size,'ext'=>$type])->move(ROOT_PATH . DS . 'public/uploads');
            if($info){
                $name = '/uploads/'.str_replace('\\','/',$info->getSaveName());
                $imgArr = explode(',', 'jpg,gif,png,jpeg,bmp,ttf,tif');
                $imgExt= strtolower($info->getExtension());
                $isImg = in_array($imgExt,$imgArr);
                $isTrue = config('web.watermark') == 1 ? true : false;
                if($isImg && $isTrue){//如果是图片，开始处理
                    $water = config('web.water_type');
                    $image = Image::open($file);
                    //在这里你可以根据你需要，调用ThinkPHP5的图片处理方法了
                    $position = config('web.water_position');
                    if($water == 0){//文字水印
                        $text = config('web.water_text');
                        $font = ROOT_PATH. config('web.water_font');
                        $size = config('web.water_size');
                        $color = config('web.water_color');
                        $image->text($text ,$font ,$size , $color, $position)->save(ROOT_PATH . "/public" . $name);
                    }
                    if($water == 1){//图片水印
                        $water_image = ROOT_PATH. config('web.water_image');
                        $alpha = config('web.water_alpha');
                        $image->water($water_image, $position, $alpha)->save(ROOT_PATH . "/public". $name);
                    }
                    $thumb = config('web.thumb');
                    if($thumb == 1){//生成缩略图
                        $image->thumb(config('web.thumb_width'), config('web.thumb_height'), config('web.thumb_type'))->save(ROOT_PATH . "/public". $name);
                    }
                }
                $Obj = new Attachment();
                $Obj->saveOrUpdate($info->getInfo('name') ,$info->getSaveName() , $info->getExtension(), $info->getSize());
                $this->success("上传成功", null, "/uploads/" . $info->getSaveName());
            }else{
                // 上传失败获取错误信息
                $this->error($file->getError(), null, $file->getError());
            }
        }else{
            $this->error("什么都没有");
        }
    }

    public function delete(){
        $name = input('post.name');
        $ret = db("attachment")->where("name", $name)->find();
        if($ret){
            db("attachment")->delete($ret['id']);
            unlink(ROOT_PATH. $ret['filepath']);
        }
        $this->apiCallback("SUCCESS", $name, $name . "已删除成功");
    }

}