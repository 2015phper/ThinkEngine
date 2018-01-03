/**
 * Created by Administrator on 2017/4/27.
 */
$(function () {
    layui.use(['layer','form','element','laydate','upload'],function () {
        var layer = layui.layer,
            form = layui.form(),
            laydate = layui.laydate;
        $("body").on("click",'.layerDate',function () {
            laydate({elem: this, istime: true, format: 'YYYY-MM-DD'})
        })
        $("body").on("click",'.layerHour',function () {
            laydate({elem: this, istime: true, format: 'YYYY-MM-DD hh:mm:ss'})
        })
        
        function openiFrame(url,width,height) {
            layer.open({
                type: 2,
                skin: "layer-layui-rim",
                shadeClose: true,
                shade: 0.8,
                area: [width,height],
                title: title,
                content: url
            });
        }
    })
})
