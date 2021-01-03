<script src='bin/JS/Event/loadDimensions.js?<?php echo rand(0, 99999999);?>'></script>
<script>
$(document).ready(function(){
  loadDimensions({});
});
$(window).resize(function(){
  loadDimensions({});
});
</script>
