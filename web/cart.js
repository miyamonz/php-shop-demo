console.log($(".henkou"));
var buttons = $(".henkou");

$(document).on('click','.henkou',function (e){
  $(this).prev().toggle();
});

