  function toggleBox(Id, state = null){
    if(state == null){
      document.getElementById(Id).checked = !document.getElementById(Id).checked;
    } else {
      document.getElementById(Id).checked = state;
    }
  }
  function checkBoxes(className){
    Boxes = array();
    document.getElementsByClassName(className).prototype.forEach.call(els, function(el) {
      Boxes[el.id] = el.checked;
    });
    return Boxes;
  }
  function checkBox(link){
    if(typeof link === 'string' || link instanceof String){
      return document.getElementById(link).checked;
    } else {
      return $(link).prop('checked');
    }
  }