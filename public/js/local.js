$(function(){
   $("#get_vcode").click(function(){
       $.ajax({
           url: 'http://182.92.162.175/tool/cpt_check',
           type: "post",
           data: {
               'cpt':$('input[name=cpt]').val(),
               '_token':$('input[name=_token]').val()
           },
           success: function(data){
               if(data.code == 200){
                   $.ajax({
                       url: 'http://182.92.162.175/tool/sms_send',
                       type: "post",
                       data: {
                           'phone':$('input[name=phone]').val(),
                           '_token':$('input[name=_token]').val()
                       },
                       success: function(data){
                           alert(data.info);
                       }
                   });
               }else{
                    alert(data.info);
               }
           }
       });
    });

    //var map = new AMap.Map('map');   
    
//    var infoWin= new AMap.InfoWindow({
//        closeWhenClickMap: true,
//        content: '我是信息窗口'
//    });
//    infoWin.open(map,map.getCenter());
    
    
//    var mk1 = new AMap.Marker({
//        map:map
//    });
//    mk1.setPosition(map.getCenter());
//    AMap.event.addListener(mk1,'click',function(){
//        var infoWin= new AMap.InfoWindow({
//            closeWhenClickMap: true,
//            content: '我是信息窗口'
//        });
//        infoWin.open(map,map.getCenter());        
//    });
    //mk1.setContent('王国营来啦，快跑啊');
//    
//    var mk2 = new AMap.Marker({
//        map:map
//    });
//    mk2.setPosition(new AMap.LngLat(116,39));
//    mk2.setIcon('http://b.amap.com/imgs/icon4.png');
    
    
//    AMap.event.addListener(map,'dragend',function(){
//        //alert('尾巴太海沧了');
//    });
//    
//    //给地图增加比例尺控件
//    map.plugin(['AMap.Scale'],function(){
//        var scale = new AMap.Scale();
//        map.addControl(scale);
//    });
//    //加载地图切换控件
//    map.plugin(['AMap.MapType'],function(){
//        var type = new AMap.MapType();
//        map.addControl(type);
//    });
//    map.plugin(['AMap.OverView'],function(){
//        var overView = new AMap.OverView();
//        overView.open();
//        map.addControl(overView);
//    });
//    map.plugin(['AMap.ToolBar'],function(){
//        var toolbar = new AMap.ToolBar();
//        map.addControl(toolbar);
//    });
    
});