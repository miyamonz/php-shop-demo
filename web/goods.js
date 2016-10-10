var $input = $("#chumonNum");

$input.on('click keydown keyup', function(){
  var val   = $input.val();
  var zaiko = parseInt($("#php").attr("zaiko"));
  var sendButton = $("#chumonFrom input[type=submit]").attr("disabled",true);
  var r = zaikoCheck(val, zaiko, sendButton);
});
