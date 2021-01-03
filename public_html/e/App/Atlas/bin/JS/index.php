<script src='bin/JS/Constants.js'></script>

<!-- jQuery -->
<script src='bin/JS/jquery.min.js'></script>
<script src='bin/JS/jquery.ui.min.js'></script>

<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css' />
<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css' />
<link rel='stylesheet' type='text/css' href='https://cdn.datatables.net/select/1.3.1/css/select.dataTables.min.css' />
<script data-pagespeed-no-defer src='https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js'></script>
<script data-pagespeed-no-defer src='https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js'></script>
<script data-pagespeed-no-defer src='https://cdn.datatables.net/select/1.3.1/js/dataTables.select.min.js'></script>

<script src='bin/JS/moment.js'></script>

<script>
jQuery.fn.dataTableExt.oApi.fnStandingRedraw = function(oSettings) {
  if(oSettings.oFeatures.bServerSide === false){
    var before = oSettings._iDisplayStart;
    oSettings.oApi._fnReDraw(oSettings);
    oSettings._iDisplayStart = before;
    oSettings.oApi._fnCalculateEnd(oSettings);
  }
  oSettings.oApi._fnDraw(oSettings);
};
</script>

<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js' integrity='sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV' crossorigin='anonymous'></script>

<?php require('bin/JS/Function/index.php');?>
<?php require('bin/JS/Event/index.php');?>
