<?php
namespace app\admin\controller;

use think\Controller;
use think\Msg;

class Index extends Controller
{
    public function index(){
        /*注册用户*/
        $data['userCount'] = number_format(db("user")->count());
        $begin = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $end = mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
        $data['userYes'] = db("user")->where("create_time >= {$begin} and create_time < {$end}")->count(); //昨天的注册量
        $data['userYes'] = $data['userYes'] == 0 ? $data['userYes']+1:$data['userYes'];
        $begin=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $end=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $data['userDay'] = db("user")->where("create_time >= {$begin} and create_time < {$end}")->count(); //今天的注册量
        /*评论用户*/
        $data['commentUpdate'] = db("comment")->where("addtime >= {$begin} and addtime < {$end}")->count();
        $data['commentCount'] = number_format(db("comment")->count());

        /*总的文章*/
        $data['newsCount'] = number_format(db("news")->count());

        /*总的留言*/
        $data['messCount'] = number_format(db("message")->count());

        /*总的留言*/
        $data['musicCount'] = number_format(db("music")->count());

        /*总的留言*/
        $data['topicCount'] = number_format(db("topic")->count());

        /*总的留言*/
        $data['attachmentCount'] = number_format(db("attachment")->count());
        $begin = mktime ( 0, 0, 0, date ( "m" ), 1, date ( "Y" ) );
        $end = mktime ( 23, 59, 59, date ( "m" ), date ( "t" ), date ( "Y" ) );
        $data['attCount'] = db("attachment")->where("create_time >= {$begin} and create_time < {$end}")->count();
        return view('index', $data);
    }

    /**
     * 获取天气
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\Json
     */
    public function getWeather(){
        $h=date('H');
        if($h>=8 && $h<=20){
            $text = "text_day";
            $code = "code_day";
        }else{
            $text = "text_night";
            $code = "code_night";
        }

        $arr = array();
        $date = date("Y-m-d");
        $dateEnd = date("Y-m-d", strtotime("+3 days"));
        $ret = db("weather")->where("date >= '{$date}' and date <= '{$dateEnd}'")->select();
        if(!$ret){
            $weather = getWeather();
            $location = $weather['results'][0]['location'];
            $time = $weather['results'][0]['last_update'];
            $insert = array();
            foreach ($weather['results'][0]['daily'] as $key => $item){
                $insert['name'] = $location['name'];
                $insert['path'] = $location['path'];
                $insert['date'] = $item['date'];
                $insert['text_day'] = $item['text_day'];
                $insert['code_day'] = $item['code_day'];
                $insert['text_night'] = $item['text_night'];
                $insert['code_night'] = $item['code_night'];
                $insert['high'] = $item['high'];
                $insert['low'] = $item['low'];
                $insert['wind_direction'] = $item['wind_direction'];
                $insert['wind_speed'] = $item['wind_speed'];
                $insert['wind_scale'] = $item['wind_scale'];
                $insert['last_update'] = strtotime($time);
                db("weather")->insert($insert);
            }
        }
        $ret = db("weather")->where("date >= '{$date}' and date <= '{$dateEnd}'")->select();
        $week = ['周日', '周一', '周二', '周三', '周四', '周五', '周六', ];
        foreach ($ret as $key => $item){
            $arr[$key]['weather'] = "weather".$key;
            $arr[$key]['type'] = self::getType($item[$code]);
            $arr[$key]['name'] = $item['name'];
            $arr[$key]['date'] = $item['date'];
            $arr[$key]['text'] = $item[$text];
            $arr[$key]['code'] = $item[$code];
            $arr[$key]['high'] = $item['high'];
            $arr[$key]['week'] = $week[date('w', strtotime($item['date']))];
            $arr[$key]['default'] = ($item['high']+$item['low'])/2 . "&#176;C";
            $arr[$key]['low'] = $item['low'];
            $arr[$key]['last_update'] = date("Y-m-d H:i:s", $item['last_update']);
        }
        return json($arr);
    }

    /**
     * CLEAR_DAY 晴天
     * CLEAR_NIGHT 晴天晚
     * PARTLY_CLOUDY_DAY 白天晴间多云
     * PARTLY_CLOUDY_NIGHT 夜晚晴间多云
     * CLOUDY 阴天
     * RAIN 雨天
     * SLEET 大雨天
     * SNOW 雪天
     * WIND 起风天
     * FOG 雾天
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $type
     * @author ZhiyuanLi < 956889120@qq.com >
     * @param $type
     * @return string
     */
    public function getType($type){
        $h=date('H');
        if($h>=8 && $h<=20){
            $day = true;
        }else{
            $day = false;
        }
        if($type >= 0 && $type < 4 && $day == true){
            $code = "CLEAR_DAY";
        }elseif($type >= 0 && $type < 4 && $day == false){
            $code = "CLEAR_NIGHT";
        }elseif($type >= 4 && $type < 9 && $day == true){
            $code = "PARTLY_CLOUDY_DAY";
        }elseif($type >= 4 && $type < 9 && $day == false){
            $code = "PARTLY_CLOUDY_NIGHT";
        }elseif($type == 9){
            $code = "CLOUDY";
        }elseif($type >= 10 && $type < 15){
            $code = "RAIN";
        }elseif($type >= 15 && $type < 20){
            $code = "SLEET";
        }elseif($type >= 20 && $type < 26){
            $code = "SNOW";
        }elseif($type >= 32 && $type < 37){
            $code = "WIND";
        }elseif($type >= 30 && $type < 32){
            $code = "FOG";
        }else{
            $code = "CLEAR_DAY";
        }
        return $code;
    }

    /**
     * font-awesome 图标
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\View
     */
    public function fontAwesome(){
        return view("icons-font-awesome");
    }

    /**
     * Ionic 图标
     * @author ZhiyuanLi < 956889120@qq.com >
     * @return \think\response\View
     */
    public function Ionic(){
        return view("icons-ionicons");
    }

}
