console.log($(".henkou"));
var buttons = $(".henkou");

$(document).on('click','.henkou',function (e){
  var form = $(this).prev();
  var visible = !(form.css('display') == 'none');
  buttons.prev().hide();
  form.toggle(!visible);
  var text = visible ? "変更する": "閉じる" ;
  $(this).text(text);
});

