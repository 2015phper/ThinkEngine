<?php
namespace app\common\model;

use think\Model;

class Menu extends Model
{
    protected $table = "tc_menu";

    /** 无限级菜单（在父类下新增children）
     * @param $arr
     * @return array
     */
    function makeTree($arr){
        if(!function_exists('makeTreeAdd')){
            function makeTreeAdd($arr, $parent_id=0){
                $new_arr = array();
                foreach($arr as $k=>$v){
                    if($v['pid'] == $parent_id){
                        $new_arr[] = $v;
                        unset($arr[$k]);
                    }
                }
                foreach($new_arr as &$a){
                    $a['children'] = makeTreeAdd($arr, $a['id']);
                }
                return $new_arr;
            }
        }
        return makeTreeAdd($arr);
    }


    /**
     * 增加前缀
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @param $arr
     * @return array
     */
    function makeTreePre($arr){
        $arr = $this->makeTree($arr);
        if (!function_exists('makeTreePreAdd')) {
            function makeTreePreAdd($arr, $pre='') {
                $new_arr = array();
                foreach ($arr as $v) {
                    if ($pre) {
                        if ($v == end($arr)) {
                            $v['name'] = $pre.'└─ '.$v['name'];
                        } else {
                            $v['name'] = $pre.'├─ '.$v['name'];
                        }
                    }
                    if ($pre == '') {
                        $pre_for_children = '　 ';
                    } else {
                        if ($v == end($arr)) {
                            $pre_for_children = $pre.'　　 ';
                        } else {
                            $pre_for_children = $pre.'│　 ';
                        }
                    }
                    $v['children'] = makeTreePreAdd($v['children'], $pre_for_children);

                    $new_arr[] = $v;
                }
                return $new_arr;
            }
        }
        return makeTreePreAdd($arr);
    }

    /**
     * 创建select下拉的选项
     * @author ZhiyuanLi < 956889120@qq.com >
     * @since 1.0
     * @param $arr
     * @param int $depth 当$depth为0的时候表示不限制深度
     * @return string
     */
    function makeSelectOption($arr, $depth=0)
    {
        $arr = $this->makeTreePre($arr);
        if (!function_exists('make_options1')) {
            function make_options1($arr, $depth, $recursion_count=0, $ancestor_ids='') {
                $recursion_count++;
                $str = '';
                foreach ($arr as $v) {
                    $str .= "<option value='{$v['id']}' data-depth='{$recursion_count}' data-ancestor_ids='".ltrim($ancestor_ids,',')."'>{$v['name']}</option>";
                    if ($v['pid'] == 0) {
                        $recursion_count = 1;
                    }
                    if ($depth==0 || $recursion_count<$depth) {
                        $str .= make_options1($v['children'], $depth, $recursion_count, $ancestor_ids.','.$v['id']);
                    }

                }
                return $str;
            }
        }
        $header = '<option value="0">大类</option>';
        return $header.make_options1($arr, $depth);
    }
}