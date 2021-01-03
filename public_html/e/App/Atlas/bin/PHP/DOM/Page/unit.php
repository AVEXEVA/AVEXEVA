<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'unit';
    $_SESSION['page-id'] = isset($_GET['ID']) ? $_GET['ID'] : null;
    require('../../../cgi-bin/php/index.php');
}
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
		SELECT *
		FROM   nei.dbo.Connection
		WHERE  Connection.Connector = ?
		       AND Connection.Hash  = ?
	;",array($_SESSION['User'],$_SESSION['Hash']));
    $My_Connection = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $r = sqlsrv_query($NEI,"
		SELECT *,
		       Emp.fFirst AS First_Name,
			   Emp.Last   AS Last_Name
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;",array($_SESSION['User']));
    $My_User = sqlsrv_fetch_array($r);
	$r = sqlsrv_query($NEI,"
		SELECT *
		FROM   Portal.dbo.Privilege
		WHERE  Privilege.User_ID = ?
	;",array($_SESSION['User']));
	$My_Privileges = array();
	if($r){while($My_Privilege = sqlsrv_fetch_array($r)){$My_Privileges[$My_Privilege['Access_Table']] = $My_Privilege;}}
    if(	!isset($My_Connection['ID'])
	   	|| !isset($My_Privileges['Location'])
	  		|| $My_Privileges['Location']['User_Privilege']  < 4
	  		|| $My_Privileges['Location']['Group_Privilege'] < 4){echo 'bye world';}
        elseif(isset($_GET['ID']) && is_numeric($_GET['ID'])) {
        	$r = sqlsrv_query($NEI,
                "SELECT TOP 1
                    Elev.ID,
                    Elev.Unit           AS Unit,
                    Elev.State          AS State,
                    Elev.Cat            AS Category,
                    Elev.Type           AS Type,
                    Elev.Building       AS Building,
                    Elev.Since          AS Since,
                    Elev.Last           AS Last,
                    Elev.Price          AS Price,
                    Elev.fDesc          AS Description,
                    Loc.Loc             AS Location_ID,
                    Loc.ID              AS Name,
                    Loc.Tag             AS Tag,
                    Loc.Tag             AS Location_Tag,
                    Loc.Address         AS Street,
                    Loc.City            AS City,
                    Loc.State           AS Location_State,
                    Loc.Zip             AS Zip,
                    Loc.Route           AS Route,
                    Zone.Name           AS Zone,
                    OwnerWithRol.Name   AS Customer_Name,
                    OwnerWithRol.ID     AS Customer_ID,
                    Emp.ID AS Route_Mechanic_ID,
                    Emp.fFirst AS Route_Mechanic_First_Name,
                    Emp.Last AS Route_Mechanic_Last_Name
                FROM
                    nei.dbo.Elev
                    LEFT JOIN nei.dbo.Loc           ON Elev.Loc = Loc.Loc
                    LEFT JOIN nei.dbo.Zone          ON Loc.Zone = Zone.ID
                    LEFT JOIN nei.dbo.OwnerWithRol  ON Loc.Owner = OwnerWithRol.ID
                    LEFT JOIN nei.dbo.Route ON Loc.Route = Route.ID
                    LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
                WHERE Elev.ID = ?
    		;",array($_GET['ID']));
        $Unit = sqlsrv_fetch_array($r);
        $unit = $Unit;
        $data = $Unit;
        $r2 = sqlsrv_query($NEI,"
            SELECT *
            FROM   ElevTItem
            WHERE  ElevTItem.ElevT    = 1
                   AND ElevTItem.Elev = ?
        ;",array($_GET['ID']));
        if($r2){while($array = sqlsrv_fetch_array($r2)){$Unit[$array['fDesc']] = $array['Value'];}}
?><div class="panel panel-primary" style='height:100%;'>
  <h4 style='margin:0px;padding:10px;background-color:whitesmoke;border-bottom:1px solid darkgray;'><a href='unit.php?ID=<?php echo $_GET['ID'];?>'><?php $Icons->Unit();?> Unit: <?php echo $Unit['Unit'];?></a></h4>
    <style>
    .nav-text{
      font-weight: bold;
      text-align: center;
    }
    .nav-icon{
      text-align: center;
    }
  </style>
  <style>

    .Screen-Tabs { overflow-x: hidden }

    .Screen-Tabs>div {
      --n: 1;
      display: flex;
      align-items: center;
      overflow-y: hidden;
      width: 100%; // fallback
      width: calc(var(--n)*100%);
      /*height: 50vw;*/ max-height: 100vh;
      transform: translate(calc(var(--tx, 0px) + var(--i, 0)/var(--n)*-100%));
      color:white;
      div {
        /*width: 100%; // fallback
        width: calc(100%/var(--n));*/
        user-select: none;
        pointer-events: none
      }

    }

    .smooth { transition: transform  calc(var(--f, 1)*.5s) ease-out }
    div.Home-Screen-Option.active {
      background-color:#3d3d3d !important;
      color:white !important;
    }
    .Screen-Tabs {
      border-bottom:3px solid black;
    }
  </style>
  <div class='Screen-Tabs shadower'>
    <div class='row'>
      <div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-information.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php /*<img src='media/images/icons/information.png' width='auto' height='35px' />*/?><?php $Icons->Info(3);?></div>
          <div class ='nav-text'>Information</div>
      </div>
      <?php if($Unit['Type'] == 'Elevator' && isset($My_Privileges['Unit']) && ($My_Privileges['Unit']['User_Privilege'] >= 4 || $My_Privileges['Unit']['Group_Privilege'] >= 4)){
      ?><div tab='cod' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-items.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><img src='media/images/icons/elevator.png' width='auto' height='35px' /></div>
          <div class ='nav-text'>Elevator</div>
      </div><?php }?>
      <?php
      $r = sqlsrv_query($database_Device,"SELECT CM_Fault.* FROM Device.dbo.CM_Unit LEFT JOIN Device.dbo.CM_Fault ON CM_Unit.Location = CM_Fault.Location AND CM_Unit.Unit = CM_Fault.Unit WHERE CM_Unit.Elev_ID = ?",array($_GET['ID']));
      if($r && is_array(sqlsrv_fetch_array($r)) && ($My_Privileges['Unit']['User_Privilege'] >= 4 || $My_Privileges['Unit']['Group_Privilege'] >= 4)){
      ?><div tab='cod' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-faults.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><img src='media/images/icons/fault.png' width='auto' height='35px' /></div>
          <div class ='nav-text'>Faults</div>
      </div><?php }?>
      <?php if($Unit['Type'] == 'Elevator' && isset($My_Privileges['Unit']) && $My_Privileges['Unit']['User_Privilege'] >= 4 || $My_Privileges['Unit']['Group_Privilege'] >= 4){
      ?><div tab='cod' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-survey-sheet.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Sitemap(3);?></div>
          <div class ='nav-text'>Survey</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4 || $My_Privileges['Job']['Group_Privilege'] >= 4){
      ?><div tab='cod' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-code.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Job(3);?></div>
          <div class ='nav-text'>Code</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Customer']) && $My_Privileges['Customer']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-customer.php?ID=<?php echo $Unit['Customer_ID'];?>');">
          <div class='nav-icon'><?php $Icons->Customer(3);?></div>
          <div class ='nav-text'>Customer</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Collection']) && $My_Privileges['Collection']['User_Privilege'] >= 4 && FALSE){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-collection.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Collection(3);?></div>
          <div class ='nav-text'>Collections</div>
      </div><?php }?>
      <div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-feed.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Activities(3);?></div>
          <div class ='nav-text'>Feed</div>
      </div>
      <?php if(isset($My_Privileges['Time']) && $My_Privileges['Time']['Group_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-hours.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Hours(3);?></div>
          <div class ='nav-text'>Hours</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Invoice']) && $My_Privileges['Invoice']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-invoices.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Invoice(3);?></div>
          <div class ='nav-text'>Invoices</div>
      </div><?php }?>

      <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-jobs.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Job(3);?></div>
          <div class ='nav-text'>Jobs</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Log']) && $My_Privileges['Log']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-log.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Job(3);?></div>
          <div class ='nav-text'>Log</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Legal']) && $My_Privileges['Legal']['User_Privilege'] >= 4 && false){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-legal.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Legal(3);?></div>
          <div class ='nav-text'>Legal</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-location.php?ID=<?php echo $Unit['Location_ID'];?>');">
          <div class='nav-icon'><?php $Icons->Location(3);?></div>
          <div class ='nav-text'>Location</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Maintenance']) && $My_Privileges['Maintenance']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-maintenance.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Maintenance(3);?></div>
          <div class ='nav-text'>Maintenance</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Map']) && $My_Privileges['Map']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-map.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Map(3);?></div>
          <div class ='nav-text'>Map</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Modernization']) && $My_Privileges['Modernization']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-modernization.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Modernization(3);?></div>
          <div class ='nav-text'>Modernization</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Finances']) && $My_Privileges['Finances']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-pnl.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Financial(3);?></div>
          <div class ='nav-text'>P&L</div>
      </div><?php }?>

      <?php if(isset($My_Privileges['Repair']) && $My_Privileges['Repair']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-repair.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Repair(3);?></div>
          <div class ='nav-text'>Repair</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Route']) && $My_Privileges['Route']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick=document.location.href="route.php?ID=<?php echo $Unit['Route'];?>">
          <div class='nav-icon'><?php $Icons->Route(3);?></div>
          <div class ='nav-text'>Route</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Service']) && $My_Privileges['Service']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-service.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Phone(3);?></div>
          <div class ='nav-text'>Service</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Testing']) && $My_Privileges['Testing']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-testing.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Testing(3);?></div>
          <div class ='nav-text'>Testing</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Ticket']) && $My_Privileges['Ticket']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-tickets.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Ticket(3);?></div>
          <div class ='nav-text'>Tickets</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Time']) && $My_Privileges['Time']['Group_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-timeline.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->History(3);?></div>
          <div class ='nav-text'>Timeline</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Violation']) && $My_Privileges['Violation']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-violations.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Violation(3);?></div>
          <div class ='nav-text'>Violations</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['User']) && $My_Privileges['User']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'unit-workers.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Users(3);?></div>
          <div class ='nav-text'>Workers</div>
      </div><?php }?>

    </div>
  </div>
  <div id='container-content' class='container-content'>

  </div>
</div>
<style>
div.column {display:inline-block;vertical-align:top;}
div.label1 {display:inline-block;font-weight:bold;width:150px;vertical-align:top;}
div.data {display:inline-block;width:300px;vertical-align:top;}
</style>
<script src="../vendor/flot/excanvas.min.js"></script>
<script src="../vendor/flot/jquery.flot.js"></script>
<script src="../vendor/flot/jquery.flot.pie.js"></script>
<script src="../vendor/flot/jquery.flot.resize.js"></script>
<script src="../vendor/flot/jquery.flot.time.js"></script>
<script src="../vendor/flot/jquery.flot.categories.js"></script>
<script src="../vendor/flot-tooltip/jquery.flot.tooltip.min.js"></script>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAycrIPh5udy_JLCQHLNlPup915Ro4gPuY"></script>
<script>
function someFunction(link,URL){
  $(link).siblings().removeClass('active');
  $(link).addClass('active');
  $("div.container-content").html("<div style='text-align:center;style='color:white !important;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>");
  $.ajax({
    url:"cgi-bin/php/element/unit/" + URL,
    success:function(code){
      $("div.container-content").html(code);
    }
  });
}
$(document).ready(function(){
  <?php if(isset($_GET['Ticket_Update']) && $_GET['Ticket_Update'] == 1){?>someFunction(this,'unit-controller.php?ID=10747');<?php }
  else {?>$("div.Screen-Tabs>div>div:first-child").click();<?php }?>
});
</script>
<script data-pagespeed-no-defer src="https://hammerjs.github.io/dist/hammer.min.js"></script>
<script>
// credit: http://www.javascriptkit.com/javatutors/touchevents2.shtml
function swipedetect(callback){

  var touchsurface = document.getElementById('container-content'),
  swipedir,
  startX,
  startY,
  distX,
  distY,
  threshold = 150, //required min distance traveled to be considered swipe
  restraint = 100, // maximum distance allowed at the same time in perpendicular direction
  allowedTime = 300, // maximum time allowed to travel that distance
  elapsedTime,
  startTime,
  handleswipe = callback || function(swipedir){}

  touchsurface.addEventListener('touchstart', function(e){
      var touchobj = e.changedTouches[0]
      swipedir = 'none'
      dist = 0
      startX = touchobj.pageX
      startY = touchobj.pageY
      startTime = new Date().getTime() // record time when finger first makes contact with surface
      //e.preventDefault()
  }, false)

  touchsurface.addEventListener('touchmove', function(e){
      //e.preventDefault() // prevent scrolling when inside DIV
  }, false)

  touchsurface.addEventListener('touchend', function(e){
      var touchobj = e.changedTouches[0]
      distX = touchobj.pageX - startX // get horizontal dist traveled by finger while in contact with surface
      distY = touchobj.pageY - startY // get vertical dist traveled by finger while in contact with surface
      elapsedTime = new Date().getTime() - startTime // get time elapsed
      if (elapsedTime <= allowedTime){ // first condition for awipe met
          if (Math.abs(distX) >= threshold && Math.abs(distY) <= restraint){ // 2nd condition for horizontal swipe met
              swipedir = (distX < 0)? 'left' : 'right' // if dist traveled is negative, it indicates left swipe
          }
          else if (Math.abs(distY) >= threshold && Math.abs(distX) <= restraint){ // 2nd condition for vertical swipe met
              swipedir = (distY < 0)? 'up' : 'down' // if dist traveled is negative, it indicates up swipe
          }
      }
      handleswipe(swipedir)
      //e.preventDefault()
  }, false)
}

//USAGE:

//var el = document.getElementById('wrapper');
swipedetect(function(swipedir){
if(swipedir == 'left'){
  $(".Home-Screen-Option.active").next().click();
}
if(swipedir == 'right'){
  $(".Home-Screen-Option.active").prev().click();
}
});

</script><?php
    }
} else {}?>
