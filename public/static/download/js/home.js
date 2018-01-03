/* 
  layuiAdmin 官网主页 
*/


layui.use(['carousel', 'layer', 'util'], function(){
  var $ = layui.$
  ,carousel = layui.carousel
  ,util = layui.util;
  
  //倒计时
  var adminCountdown = $('#adminCountdown');
  if(adminCountdown[0]){
    $.get('/api/getTime?v='+ (new Date().getTime()), function(res){
      util.countdown(new Date(2017,11,29,15,18,00), new Date(res.time), function(date, serverTime, timer){
        var str = function(){
          if(date.join('') == 0){
            return '正在发布';
          };
          return /*date[0] + ' 天 ' +*/ util.digit(date[1]) + ' 时 ' + util.digit(date[2]) + ' 分 ' + util.digit(date[3]) + ' 秒 ' + '后开放';
        }();
        
        
        adminCountdown.html(str);
      });
    },'json');
  }
  
  //工具条
  util.fixbar({
    bar1: '&#xe698;'
    ,bar2: '&#xe606;'
    ,bgcolor: '#353544'
    ,click: function(type){
      if(type === 'bar1'){
        location.hash = '';
        location.hash = 'get';
      } else if(type === 'bar2'){
        location.href = "http://fly.layui.com/";
      }
    }
  });
  
  //轮播实例
  var height = 737, setHeight = function(){
    var width = $(window).width();
    return (width > 1395 ? height : (width - 30)*height/1395) + 'px';
  }, inst1 = carousel.render({
    elem: '#LAY_preview'
    ,width: '100%'
    ,height: setHeight()
    ,anim: 'fade'
    ,indicator: 'outside'
  });
  
  //Tips
  $('*[lay-tips]').on('mouseenter', function(){
    var content = $(this).attr('lay-tips');
    
    this.index = layer.tips('<div style="padding: 10px; font-size: 14px; color: #eee;">'+ content + '</div>', this, {
      time: -1
      ,maxWidth: 280
      ,tips: [3, '#3A3D49']
    });
  }).on('mouseleave', function(){
    layer.close(this.index);
  });
  
  //尺寸适应
  $(window).on('resize', function(){
    if($(this).width() > 1395 && !setHeight.xs){
      setHeight.xs = false;
      return inst1.config.elem.css('height', height)
    };
    inst1.config.elem.css('height', setHeight());
    setHeight.xs = true;
  });
   
  
});

