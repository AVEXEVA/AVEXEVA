function clearMarkers() {
  setMapOnAll(toggle == 0 ? null : map);
  toggle = toggle == 0 ? 1 : 0;
  Timeline_Supervisor = ''
  $("#Feed").html("");
  REFRESH_DATETIME = '<?php echo date("Y-m-d H:i:s",strtotime('-300 minutes'));?>';
  TIMELINE = new Array();
  getTimeline();
}
