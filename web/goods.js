var zaiko = parseInt($("#php").attr("zaiko"));
var $input = $("#chumonNum");
$("#chumonFrom input[type=submit]").attr("disabled",true);
$input.keyup(function(){
  var inputNum = parseInt($input.val());
  var numOk = (0 < inputNum )&&( inputNum <= zaiko);
  if(numOk){
    $("#attention").text("");
    $("#chumonFrom input[type=submit]").attr("disabled",false);
  }else{
    $("#attention").text("在庫数以下にしてください");
    $("#chumonFrom input[type=submit]").attr("disabled",true);
  }
  if(inputNum <= 0){
    $("#attention").text("正の整数をいれてください");
    $("#chumonFrom input[type=submit]").attr("disabled",true);
  }
  if($input.val() === "") {
    $("#attention").text("");
    $("#chumonFrom input[type=submit]").attr("disabled",true);
  }
});

