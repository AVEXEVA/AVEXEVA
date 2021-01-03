function isCanvasBlank(canvas) {
  var blank = document.createElement('canvas');
  blank.width = canvas[0].width;
  blank.height = canvas[0].height;
  var asdf = blank.getContext("2d");
  asdf.clearRect(0, 0, blank.width, blank.height);
  asdf.beginPath();
  asdf.fillStyle = "white";
  asdf.fillRect(0, 0, blank.width, blank.height);
  //alert(blank.toDataURL());
  return canvas[0].toDataURL() == blank.toDataURL();
}
