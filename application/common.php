<?php
header("Content-type:text/html;charset=utf-8");
// 应用公共文件
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(E_ERROR | E_PARSE );

/**
 * 64位图像保存接口
 * @author ZhiyuanLi < 956889120@qq.com >
 * @since 1.0
 * @param $base
 * @return bool|string
 */
function upload($base)
{
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
            return $path;
        } else {
            return false;
        }
    }
}

/**
 * 同时支持 utf-8、gb2312都支持的汉字截取函数 ,默认编码是utf-8
 * @author ZhiyuanLi < 956889120@qq.com >
 * @param $string
 * @param $str_len
 * @param int $start
 * @param string $code
 * @return string
 */
function str_cut($string, $str_len, $start = 0, $code = 'UTF-8')
{
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if (count($t_string[0]) - $start > $str_len) return join('', array_slice($t_string[0], $start, $str_len)) . "...";
        return join('', array_slice($t_string[0], $start, $str_len));
    } else {
        $start = $start * 2;
        $str_len = $str_len * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $str_len)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129) $i++;
        }
        if (strlen($tmpstr) < $strlen) $tmpstr .= "...";
        return $tmpstr;
    }
}

function killAllHtml($string)
{
    $string = preg_replace('/&((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&', '"', '<', '>'), $string));
    $string = htmlspecialchars_decode($string);
    $string = preg_replace("@<script(.*?)</script>@is", "", $string);
    $string = preg_replace("@<iframe(.*?)</iframe>@is", "", $string);
    $string = preg_replace("@<style(.*?)</style>@is", "", $string);
    $string = preg_replace("@<(.*?)>@is", "", $string);
    $string = preg_replace("/\s+/", " ", $string);
    $string = preg_replace("/<\!--.*?-->/si", "", $string);
    $string = str_replace("&nbsp;", "", $string);
    return $string;
}

/**
 * 产生随机字串，可用来自动生成密码 默认长度6位 字母和数字混合
 * @author Zhiyuan Li
 * @version v1.0
 * @param int $len 长度
 * @param string $type 字串类型 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
 * @return string
 */
function rand_string($len = 6, $type = '', $addChars = '')
{
    $str = '';
    switch ($type) {
        case 0 :
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        case 1 :
            $chars = str_repeat('0123456789', 3);
            break;
        case 2 :
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
            break;
        case 3 :
            $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
            break;
        default :
            // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
            $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
            break;
    }
    if ($len > 10) { //位数过长重复字符串一定次数
        $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
    }
    if ($type != 4) {
        $chars = str_shuffle($chars);
        $str = substr($chars, 0, $len);
    } else {
        // 中文随机字
        for ($i = 0; $i < $len; $i++) {
            $str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
        }
    }
    return $str;
}

/**
 * @apply 把数组改成合并的字符串类型 例子：数组 => 1,2,3 数组 => 一、二、三
 * @author LiZhiyuan
 * @version v1.0
 * @param $array
 * @param $name
 * @param string $string
 * @return string
 * @tips separator(分隔符)
 */
function separator($array, $name = 'id', $string = ',')
{
    if (empty($array)) {
        return false;
    }
    $newAry = array();
    foreach ($array as $key => $value) {
        $newAry[] = $value[$name];
    }
    return implode($string, $newAry);
}

function getTags($title)
{
    $num = 10;
    require_once(THINK_PATH . "library/think/Scws.php");
    $pscws = new pscws4();
    $pscws->set_dict(THINK_PATH . "library/think/scws/dict.utf8.xdb");
    $pscws->set_rule(THINK_PATH . 'library/think/scws/rules.utf8.ini');
    $pscws->set_ignore(true);
    $pscws->send_text($title);
    $words = $pscws->get_tops($num);
    $pscws->close();
    $tags = array();
    if ($words) {
        foreach ($words as $val) {
            $tags[] = $val['word'];
        }
        return implode(",", $tags);
    } else {
        return '';
    }
}


/**
 * @apply 获取书本树状菜单(菜单)
 * @author LiZhiyuan
 * @version v1.0
 * @param $data
 * @param int $pid
 * @param int $cid
 * @return array|string
 */
function getTree($data, $pid = 0, $cid = 0)
{
    $tree = '';
    foreach ($data as $key => $value) {
        if ($value['pid'] == $pid) {
            //父亲找到儿子
            if ($value['id'] == $cid) {
                $value['spread'] = true;
            }
            $value['children'] = getTree($data, $value['id'], $cid);
            $tree[] = $value;
            unset($data[$key]);
        }
    }
    return $tree;
}

/**
 * 友好时间显示
 * @param $time
 * @return bool|string
 */
function friendDate($time)
{
    if (!$time)
        return false;
    $fdate = '';
    $d = time() - intval($time);
    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
    if ($d == 0) {
        $fdate = '刚刚';
    } else {
        switch ($d) {
            case $d < $atd:
                $fdate = date('Y年m月d日', $time);
                break;
            case $d < $td:
                $fdate = '后天 ' . date('H:i', $time);
                break;
            case $d < 0:
                $fdate = '明天 ' . date('H:i', $time);
                break;
            case $d < 60:
                $fdate = "<font color='#EB2F3F'>" . $d . '秒前</font>';
                break;
            case $d < 3600:
                $fdate = "<font color='#F17C67'>" . floor($d / 60) . '分钟前</font>';
                break;
            case $d < $dd:
                $fdate = "<font color='#57D2F7'>" . floor($d / 3600) . '小时前</font>';
                break;
            case $d < $yd:
                $fdate = '昨天 ' . date('H:i', $time);
                break;
            case $d < $byd:
                $fdate = '前天 ' . date('H:i', $time);
                break;
            case $d < $md:
                $fdate = date('m月d日 H:i', $time);
                break;
            case $d < $ld:
                $fdate = date('m月d日', $time);
                break;
            default:
                $fdate = date('Y年m月d日', $time);
                break;
        }
    }
    return $fdate;
}

// 递归删除文件夹
function delFile($path, $delDir = FALSE)
{
    if (!is_dir($path))
        return FALSE;
    $handle = @opendir($path);
    if ($handle) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..")
                is_dir("$path/$item") ? delFile("$path/$item", $delDir) : unlink("$path/$item");
        }
        closedir($handle);
        if ($delDir) return rmdir($path);
    } else {
        if (file_exists($path)) {
            return unlink($path);
        } else {
            return FALSE;
        }
    }
}

/**
 * 缓存更新
 * @author ZhiyuanLi < 956889120@qq.com >
 * @return bool
 */
function CacheClear(){
    delFile(RUNTIME_PATH);
    \think\Cache::clear();
    //删除网站配置文件
    delFile(ROOT_PATH . "config/extra");
    return true;
}

/**
 * CURL
 * @author ZhiyuanLi < 956889120@qq.com >
 * @param $url
 * @return mixed
 */
function httpGet($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $output=curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}

/**
 * 获取天气
 * @author ZhiyuanLi < 956889120@qq.com >
 * @param string $location 城市名称。除拼音外，还可以使用 v3 id、汉语等形式
 * @return mixed
 */
function getWeather($location = "广州"){
    $key = 'wmjp53ppqozz58o3';
    $uid = 'UCA2B76A12';
    $api = 'https://api.seniverse.com/v3/weather/daily.json';
    $param = ['ts' => time(), 'ttl' => 300, 'uid' => $uid,];
    $sig_data = http_build_query($param); // http_build_query 会自动进行 url 编码
    $sig = base64_encode(hash_hmac('sha1', $sig_data, $key, TRUE));
    $param['sig'] = $sig;
    $param['location'] = $location;
    $param['start'] = 0; // 开始日期。0 = 今天天气
    $param['days'] = 5; // 查询天数，1 = 只查一天
    $url = $api . '?' . http_build_query($param);
    $ret = httpGet($url);
    return $ret;
}