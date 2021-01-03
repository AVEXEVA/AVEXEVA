var sliderRegular = document.getElementById('slider-regular');
var sliderOvertime = document.getElementById('slider-overtime');
var sliderDoubletime = document.getElementById('slider-doubletime');
var sliderNightDiff = document.getElementById('slider-nightdiff');
var timeRegular = document.getElementById('time-regular');
var timeOvertime = document.getElementById('time-overtime');
var timeDoubletime = document.getElementById('time-doubletime');
var timeNightDiff = document.getElementById('time-nightdiff');
  var tempReg = 0;
  var tempOvertime = 0;
  var tempCompleted = 0;
  function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
$(document).ready(function(){
  noUiSlider.create(sliderRegular, {
    start: 0,
    step:.25,
    range:{
      'min':0,
      'max':8
    }
  });
  sliderRegular.noUiSlider.on('update',function(values, handle){
    timeRegular.value = values[handle];
    if($("input.lunch:checked").length == 0){
      tempReg = timeRegular.value;
    }
    timeTotal.value = parseFloat(timeNightDiff.value) + parseFloat(timeOvertime.value) + parseFloat(timeDoubletime.value) + parseFloat(values[handle]);
  });
  noUiSlider.create(sliderOvertime, {
    start: 0,
    step:.25,
    range:{
      'min':0,
      'max':12
    }
  });
  sliderOvertime.noUiSlider.on('update',function(values, handle){
    timeOvertime.value = values[handle];
    if($("input.lunch:checked").length == 0){
      tempOvertime = timeOvertime.value;
    }
    timeTotal.value = parseFloat(timeRegular.value) + parseFloat(timeNightDiff.value) + parseFloat(timeDoubletime.value) + parseFloat(values[handle]);
  });
  noUiSlider.create(sliderDoubletime, {
    start: 0,
    step:.25,
    range:{
      'min':0,
      'max':12
    }
  });
  sliderDoubletime.noUiSlider.on('update',function(values, handle){
    timeDoubletime.value = values[handle];
    timeTotal.value = parseFloat(timeRegular.value) + parseFloat(timeOvertime.value) + parseFloat(timeNightDiff.value) + parseFloat(values[handle]);
  });
  noUiSlider.create(sliderNightDiff, {
    start: 0,
    step:.25,
    range:{
      'min':0,
      'max':8
    }
  });
  sliderNightDiff.noUiSlider.on('update',function(values, handle){
    timeNightDiff.value = values[handle];
    timeTotal.value = parseFloat(timeRegular.value) + parseFloat(timeOvertime.value) + parseFloat(timeDoubletime.value) + parseFloat(values[handle]);
  });
  <?php if(isset($_GET['Edit'])){?>
    sliderRegular.noUiSlider.set(<?php echo $Ticket2['Reg'];?>);
    sliderOvertime.noUiSlider.set(<?php echo $Ticket2['OT'];?>);
    sliderDoubletime.noUiSlider.set(<?php echo $Ticket2['DT'];?>);
    sliderNightDiff.noUiSlider.set(<?php echo $Ticket2['TT'];?>);
  <?php }?>
});<?php }?>

function calculate_Total(){
  var on_site = $('#en-route').html();
  var completed = $('#completed').html();
  return calculateTotal(on_site, completed);
}
function calculateTotal(on_site, completed){
  var total = 0;
  var on_site_hours = parseFloat(on_site.substr(0,2));
  var on_site_minutes = parseFloat(on_site.substr(3,2));
  var on_site_ext = on_site.substr(6,2);
  var completed_hours = parseFloat(completed.substr(0,2));
  var completed_minutes = parseFloat(completed.substr(3,2));
  var completed_ext = completed.substr(6,2);

  if(on_site_ext == 'PM' && on_site_hours != 12){on_site_hours += 12;}
  else if(on_site_ext == 'AM' && on_site_hours == 12){on_site_hours = 0;}

  if(completed_ext == 'PM' && completed_hours != 12){completed_hours += 12;}
  else if(completed_ext == 'AM' && completed_hours == 12){completed_hours = 0;}

  if(completed_hours < on_site_hours){
    total = (24 - on_site_hours) +  completed_hours + ((completed_minutes - on_site_minutes) / 60);
  } else if(completed_hours == on_site_hours && completed_minutes < on_site_minutes){
    total = 24 - ((on_site_minutes - completed_minutes) / 60);
  } else {
    total = (completed_hours - on_site_hours) + ((completed_minutes - on_site_minutes) / 60);
  }

  total = Math.ceil(4 * total) / 4;
  $("#permaTotal").html("&nbsp;&nbsp;out of <span id='total-hours'>" + total.round(2) + "</span> hours ");
  return total;
}
$(document).ready(function(){
  $("input[name='time-regular']").on("blur",function(){
    if($(this).val() % .25 == 0){
      sliderRegular.noUiSlider.set($(this).val());
    } else {
      alert('You must round to the quarter')
    }
  });
  $("input[name='time-overtime']").on("blur",function(){
    if($(this).val() % .25 == 0){
      sliderOvertime.noUiSlider.set($(this).val());
    } else {
      alert('You must round to the quarter')
    }
  });
  $("input[name='time-doubletime']").on("blur",function(){
    if($(this).val() % .25 == 0){
      sliderDoubletime.noUiSlider.set($(this).val());
    } else {
      alert('You must round to the quarter')
    }
  });
  $("input[name='time-nightdiff']").on("blur",function(){
    if($(this).val() % .25 == 0){
      sliderNightDiff.noUiSlider.set($(this).val());
    } else {
      alert('You must round to the quarter')
    }
  });
});
