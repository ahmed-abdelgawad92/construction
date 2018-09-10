function assignError(element,errorMsg) {
  element.addClass("is-invalid");
  element.after("<div style='display:block' class='invalid-feedback'>"+errorMsg+"</div>");
}
