// 提示
function tips(txt){
    $('window').append('<div class="tips" style="display: none">'+txt+'</div>').fadeIn()
    setTimeout(function(){$('.tips').remove()},1500);
}