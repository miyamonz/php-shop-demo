console.log($(".henkou"));
var buttons = $(".henkou");

$(document).on('click','.henkou',function (e){
  var form = $(this).parent().find(".cartChange");
  var visible = !(form.css('display') == 'none');
  buttons.parent().find(".cartChange").hide();
  form.toggle(!visible);
  var text = visible ? "変更する": "閉じる" ;
  $(this).text(text);
});

var zaikoJson = JSON.parse($("#php").attr("zaiko"));
$(".cartChange").on('click keyup keydown ',function(){
    var cartNum = Number($(this).attr("value"));
    var val = $(this).find("input[name=quantity]").val();
    var zaiko = zaikoJson[cartNum]['zaiko'];
    console.log(zaiko);
    var ok = zaikoCheck(val, zaiko, $(this).find("button[type=submit]"));
});

