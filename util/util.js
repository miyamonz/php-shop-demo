function zaikoCheck(val, zaiko, sendButton){
  var intVal   = Number(val); 
  var intZaiko = Number(zaiko); 
  if(isNaN(intVal)) return false;
  if(isNaN(intZaiko)) return false;

  if(intVal <= 0){
    sendButton.attr("disabled", true);
    return false;
  }
  if(intVal > zaiko){
    sendButton.attr("disabled", true);
    return false;
  }
  sendButton.attr("disabled", false);
  return true;
}


