
$(function(){
    var $container = $('#styles'); 
    $container.infinitescroll({
        navSelector : "#npage",
        nextSelector : "#npage a",
        itemSelector : "#box",
        pixelsFromNavToBottom: 200,   
        loading: {
            msgText: "正在加载中...",
            finishedMsg: "已加载完毕"
        }
    },function( newElements ) {
        var $newElems = $(newElements);
        $container.append($newElems);
    });
});