
   function dialog(msg){
    $('.dialog').css('display','block').wrap('<div id="blackbg" style="background-color:rgba(0, 0, 0, 0.8);position:absolute;top:0;left:0;bottom:0;right:0;z-index:999"><div>').addClass('slideInDown')
    .find('p').html(msg).addClass('slideInDown');

    $('.dialog').find('button').click(function (){
      $('#blackbg').fadeOut();
    });
    $('#blackbg').click(function (){
      $('.dialog > button').trigger('click');
    });
   }