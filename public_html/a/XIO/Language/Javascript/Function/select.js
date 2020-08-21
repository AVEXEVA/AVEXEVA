function select(link){
  var formData = new FormData($('form#' + $(link).attr('form'))[0]);
  redirect(formData);
}