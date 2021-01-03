<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'route';
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
              "SELECT
                  Route.ID             AS ID,
                  Route.Name           AS Route,
                  Route.Name           AS Route_Name,
                  Route.ID             AS Route_ID,
                  Emp.fFirst           AS First_Name,
                  Emp.Last             AS Last_Name,
                  Emp.ID               AS Employee_ID,
                  Emp.fFirst           AS Employee_First_Name,
                  Emp.Last             AS Employee_Last_Name,
                  Emp.fWork            AS fWork,
                  Emp.ID               AS Route_Mechanic_ID,
                  Emp.fFirst           AS Route_Mechanic_First_Name,
                  Emp.Last             AS Route_Mechanic_Last_Name,
                  Rol.Phone            AS Route_Mechanic_Phone_Number,
                  Portal.Email         AS Route_Mechanic_Email
              FROM
                  Route
                  LEFT JOIN nei.dbo.Emp   ON  Route.Mech = Emp.fWork
                  LEFT JOIN nei.dbo.Rol          ON Emp.Rol    = Rol.ID
                  LEFT JOIN Portal.dbo.Portal    ON Emp.ID     = Portal.Branch_ID AND Portal.Branch = 'Nouveau Elevator'
              WHERE
                  Route.ID        =   ?
          ;",array($_GET['ID']));
          $Route = sqlsrv_fetch_array($r);
?><div class="panel panel-primary" style='height:100%;'>
  <h4 style='margin:0px;padding:10px;background-color:whitesmoke;border-bottom:1px solid darkgray;'><a href='route.php?ID=<?php echo $_GET['ID'];?>'><?php $Icons->Route();?> Route : <?php echo $Route['Route_Name'];?> : <?php echo $Route['Employee_First_Name'] . " " . $Route['Employee_Last_Name'];?></a></h4>
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
      * { margin: 0 }

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
  </style>
<div class='Screen-Tabs shadower'>
  <div class='row'>
      <div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-information.php?ID=<?php echo $_GET['ID'];?>');">
              <div class='nav-icon'><?php $Icons->Information(3);?></div>
              <div class ='nav-text'>Information</div>
      </div>
      <?php
      $r = sqlsrv_query($NEI,"SELECT Elev.ID FROM nei.dbo.Elev LEFT JOIN nei.dbo.Loc ON Elev.Loc = Loc.Loc WHERE Loc.Route = ?;",array($_GET['ID']));
      if($r){
        $Units = array();
        while($row = sqlsrv_fetch_array($r)){$Units[] = $row['ID'];}
        if(count($Units) > 0){
          $Units = "WHERE (CM_Unit.Elev_ID = " . implode(" OR CM_Unit.Elev_ID = ",$Units) . ")";
          $r = sqlsrv_query($database_Device,"SELECT CM_Unit.* FROM Device.dbo.CM_Unit {$Units}");
          if($r && is_array(sqlsrv_fetch_array($r))){
          ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-faults.php?ID=<?php echo $_GET['ID'];?>');">
                  <div class='nav-icon'><?php $Icons->Information(3);?></div>
                  <div class ='nav-text'>Faults</div>
          </div><?php }
        }
      }?>
      <?php if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-locations.php?ID=<?php echo $_GET['ID'];?>');">
              <div class='nav-icon'><?php $Icons->Location(3);?></div>
              <div class ='nav-text'>Locations</div>
      </div><?php }?>
<?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-maintenance.php?ID=<?php echo $_GET['ID'];?>');">
              <div class='nav-icon'><?php $Icons->Maintenance(3);?></div>
              <div class ='nav-text'>Maintenance</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['Violation']) && $My_Privileges['Violation']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-violations.php?ID=<?php echo $_GET['ID'];?>');">
              <div class='nav-icon'><?php $Icons->Violation(3);?></div>
              <div class ='nav-text'>Violations</div>
      </div><?php }?>
      <div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-map.php?ID=<?php echo $_GET['ID'];?>');">
              <div class='nav-icon'><?php $Icons->Map(3);?></div>
              <div class ='nav-text'>Map</div>
      </div>
      <?php if(isset($My_Privileges['Unit']) && $My_Privileges['Unit']['User_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'route-units.php?ID=<?php echo $_GET['ID'];?>');">
              <div class='nav-icon'><?php $Icons->Unit(3);?></div>
              <div class ='nav-text'>Units</div>
      </div><?php }?>
      <?php if(isset($My_Privileges['User']) && $My_Privileges['User']['Other_Privilege'] >= 4){
      ?><div class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="document.location.href='user.php?ID=<?php echo $_GET['ID'];?>';">
              <div class='nav-icon'><?php $Icons->User(3);?></div>
              <div class ='nav-text'>User</div>
      </div><?php }?>
  </div>
</div>
<div class='container-content'></div>
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
        $.ajax({
            url:"cgi-bin/php/element/route/" + URL,
            success:function(code){
                $("div.container-content").html(code);
            }
        });
    }
    $(document).ready(function(){
        $("div.Screen-Tabs>div>div:first-child").click();
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
