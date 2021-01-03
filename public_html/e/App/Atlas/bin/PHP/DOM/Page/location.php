<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'location';
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
    elseif(is_numeric($_GET['ID'])) {
        $r = sqlsrv_query($NEI,"SELECT TOP 1
                    Loc.Loc              AS Location_ID,
                    Loc.ID               AS Name,
                    Loc.Tag              AS Tag,
                    Loc.Address          AS Street,
                    Loc.City             AS City,
                    Loc.State            AS State,
                    Loc.Zip              AS Zip,
                    Loc.Balance          AS Location_Balance,
                    Loc.Custom8          AS Resident_Mechanic,
                    Zone.Name            AS Zone,
                    Loc.Route            AS Route_ID,
                    Emp.ID               AS Route_Mechanic_ID,
                    Emp.fFirst           AS Route_Mechanic_First_Name,
                    Emp.Last             AS Route_Mechanic_Last_Name,
                    Loc.Owner            AS Customer_ID,
                    OwnerWithRol.Name    AS Customer_Name,
                    OwnerWithRol.Balance AS Customer_Balance,
                    Terr.Name            AS Territory_Domain/*,
                    Sum(SELECT Location.ID FROM Loc AS Location WHERE Location.Owner='Loc.Owner') AS Customer_Locations*/
            FROM    Loc
                    LEFT JOIN nei.dbo.Zone         ON Loc.Zone   = Zone.ID
                    LEFT JOIN nei.dbo.Route        ON Loc.Route  = Route.ID
                    LEFT JOIN nei.dbo.Emp          ON Route.Mech = Emp.fWork
                    LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner  = OwnerWithRol.ID
                    LEFT JOIN Terr         		   ON Terr.ID    = Loc.Terr
            WHERE
                    Loc.Loc = ?
        ;",array($_GET['ID']));
        $Location = sqlsrv_fetch_array($r);
        $data = $Location;
		$location = $Location;
?><div class="panel panel-primary" style='height:100%;'>
  <h4 style='margin:0px;padding:10px;background-color:whitesmoke;border-bottom:1px solid darkgray;'><a href='location.php?ID=<?php echo $_GET['ID'];?>'><?php $Icons->Location();?> Location: <?php echo $Location['Name'];?></a></h4>
<style>
  * { margin: 0 }
  .Home-Screen-Option,.row,.nav-icon,.nav-text {
    margin: 0;
    color:white !important;
  }

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
    background-color: black !important;
    color:white !important;
    margin: auto;

  }
  .Home-Screen-Option:hover  {
    background-color: white !important;
    color: white !important;
  }
</style>
<div class ='Screen-Tabs shadower' style="margin: 0;border-bottom:3px solid black !important;">
  <div class='row' style="margin: 0">
    <?php if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4){
    ?><div tab='information' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' style="margin: 0" onClick="someFunction(this,'location-information.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon' style="margin: 0"><?php $Icons->Information(3);?></div>
        <div class ='nav-text' style="margin: 0">Information</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Contract']) && $My_Privileges['Contract']['User_Privilege'] >= 4 || $My_Privileges['Contract']['Group_Privilege'] >= 4){
    ?><div tab='contracts' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-contracts.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Contract(3);?></div>
        <div class ='nav-text'>Contracts</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Invoice']) && $My_Privileges['Invoice']['User_Privilege'] >= 4){
    ?><div tab='collections' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-collections.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Collection(3);?></div>
        <div class ='nav-text'>Collections</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Contact']) && $My_Privileges['Contact']['User_Privilege'] >= 4){
    ?><div tab='contacts' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-contacts.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Info(3);?></div>
        <div class ='nav-text'>Contacts</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Customer']) && $My_Privileges['Customer']['User_Privilege'] >= 4){
    ?><div tab='customer' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="open_page('<div page-target=\'customer\'></div>', {ID:<?php echo $Location['Customer_ID'];?>});">
        <div class='nav-icon'><?php $Icons->Customer(3);?></div>
        <div class ='nav-text'>Customer</div>
    </div><?php }?>
    <div tab='feed' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-feed.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Activities(3);?></div>
        <div class ='nav-text'>Feed</div>
    </div>
    <?php if(isset($My_Privileges['Invoice']) && $My_Privileges['Invoice']['User_Privilege'] >= 4){
    ?><div tab='invoices' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-invoices.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Invoice(3);?></div>
        <div class ='nav-text'>Invoices</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Time']) && $My_Privileges['Time']['Group_Privilege'] >= 4){
    ?><div tab='hours' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-hours.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Hours(3);?></div>
        <div class ='nav-text'>Hours</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='jobs' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-jobs.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Job(3);?></div>
        <div class ='nav-text'>Jobs</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Legal']) && $My_Privileges['Legal']['User_Privilege'] >= 4 && false){
    ?><div tab='legal' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-legal.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Legal(3);?></div>
        <div class ='nav-text'>Legal</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Log']) && $My_Privileges['Log']['User_Privilege'] >= 4 ){
    ?><div tab='logs' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-log.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Job(3);?></div>
        <div class ='nav-text'>Log</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='maintenance' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-maintenance.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Maintenance(3);?></div>
        <div class ='nav-text'>Maintenance</div>
    </div><?php }?>
    <div tab='map' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-map.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Map(3);?></div>
        <div class ='nav-text'>Map</div>
    </div>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='modernization' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-modernization.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Modernization(3);?></div>
        <div class ='nav-text'>Modernization</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Finances']) && $My_Privileges['Finances']['User_Privilege'] >= 4){
    ?><div tab='PNL' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-pnl.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Customer(3);?></div>
        <div class ='nav-text'>P&L</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Proposal']) && $My_Privileges['Proposal']['User_Privilege'] >= 4){
    ?><div tab='proposals' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-proposals.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Proposal(3);?></div>
        <div class ='nav-text'>Proposals</div>
    </div><?php }?>
    <?php
    $r = sqlsrv_query($NEI,"
      SELECT 	Count(Route.ID) AS Counter
      FROM	nei.dbo.Route
          LEFT JOIN nei.dbo.Emp ON Route.Mech = Emp.fWork
      WHERE	Emp.ID = ?
              AND Route.ID = ?
    ;", array($_SESSION['User'],$Location['Route_ID']));
    $count = sqlsrv_fetch_array($r)['Counter'];
    if(isset($My_Privileges['Route']) &&
      (($My_Privileges['Route']['User_Privilege'] >= 4
        && $count > 0 )
       || $My_Privileges['Route']['Other_Privilege'] >= 4)){
    ?><div tab='route' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="document.location.href='route.php?ID=<?php echo $Location['Route_ID'];?>';">
        <div class='nav-icon'><?php $Icons->Route(3);?></div>
        <div class ='nav-text'>Route</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Service']) && $My_Privileges['Service']['User_Privilege'] >= 4){
    ?><div tab='service' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-service.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Phone(3);?></div>
        <div class ='nav-text'>Service</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='testing' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-testing.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Testing(3);?></div>
        <div class ='nav-text'>Testing</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Ticket']) && $My_Privileges['Ticket']['User_Privilege'] >= 4){
    ?><div tab='tickets' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-tickets.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Ticket(3);?></div>
        <div class ='nav-text'>Tickets</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Time']) && $My_Privileges['Time']['Group_Privilege'] >= 4){
    ?><div tab='timeline' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-timeline.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->History(3);?></div>
        <div class ='nav-text'>Timeline</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Unit']) && $My_Privileges['Unit']['User_Privilege'] >= 4){
    ?><div tab='units' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-units.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Unit(3);?></div>
        <div class ='nav-text'>Units</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Violation']) && $My_Privileges['Violation']['User_Privilege'] >= 4){
    ?><div tab='violations' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-violations.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Violation(3);?></div>
        <div class ='nav-text'>Violations</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4){
    ?><div tab='workers' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'location-workers.php?ID=<?php echo $Location['Location_ID'];?>');">
        <div class='nav-icon'><?php $Icons->Users(3);?></div>
        <div class ='nav-text'>Workers</div>
    </div><?php }?>

  </div>
</div>
  <div class='container-content'>

  </div>

  <!-- /.panel -->
</div>
</div>
<script>
function clickTab(Tab,Subtab){
  $("a[tab='" + Tab + "']").click();
  setTimeout(function(){$("a[tab='" + Subtab + "']").click();},1000);
}
$(document).ready(function(){
  $("a[tab='overview-pills']").click();
});
function someFunction(link,URL){
  $(link).siblings().removeClass('active');
  $(link).addClass('active');
  $("div.container-content").html("<div style='text-align:center;'><div class='sk-cube-grid' style='display:inline-block;position:relative;';><div class='sk-cube sk-cube1' style='background-color:#cc0000'></div><div class='sk-cube sk-cube2' style='background-color:#cc0000'></div><div class='sk-cube sk-cube3' style='background-color:#cc0000'></div><div class='sk-cube sk-cube4' style='background-color:#cc0000'></div><div class='sk-cube sk-cube5' style='background-color:#cc0000'></div><div class='sk-cube sk-cube6' style='background-color:#cc0000'></div><div class='sk-cube sk-cube7' style='background-color:#cc0000'></div><div class='sk-cube sk-cube8' style='background-color:#cc0000'></div><div class='sk-cube sk-cube9' style='background-color:#cc0000'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-45px;'><div class='sk-cube sk-cube1' style='background-color:#00007f'></div><div class='sk-cube sk-cube2' style='background-color:#00007f'></div><div class='sk-cube sk-cube3' style='background-color:#00007f'></div><div class='sk-cube sk-cube4' style='background-color:#00007f'></div><div class='sk-cube sk-cube5' style='background-color:#00007f'></div><div class='sk-cube sk-cube6' style='background-color:#00007f'></div><div class='sk-cube sk-cube7' style='background-color:#00007f'></div><div class='sk-cube sk-cube8' style='background-color:#00007f'></div><div class='sk-cube sk-cube9' style='background-color:#00007f'></div></div><div class='sk-cube-grid' style='display:inline-block;position:relative;top:-84px;'><div class='sk-cube sk-cube1' style='background-color:gold'></div><div class='sk-cube sk-cube2' style='background-color:gold'></div><div class='sk-cube sk-cube3' style='background-color:gold'></div><div class='sk-cube sk-cube4' style='background-color:gold'></div><div class='sk-cube sk-cube5' style='background-color:gold'></div><div class='sk-cube sk-cube6' style='background-color:gold'></div><div class='sk-cube sk-cube7' style='background-color:gold'></div><div class='sk-cube sk-cube8' style='background-color:gold'></div><div class='sk-cube sk-cube9' style='background-color:gold'></div></div></div><div style='font-size:72px;text-align:center;' class='BankGothic'>Nouveau Elevator</div><div style='font-size:42px;text-align:center;'><i>Raising Your Life</i></div>");
  $.ajax({
    url:"cgi-bin/php/element/location/" + URL,
    success:function(code){
      $("div.container-content").html(code);
    }
  });
}
$(document).ready(function(){
  <?php if(isset($_SESSION['Forward-Backward']['Location']) && $_SESSION['Forward-Backward']['Location']['ID'] == $_GET['ID'] && FALSE){
    ?>$("div.Screen-Tabs>div>div[Tab='<?php echo $_SESSION['Forward-Backward']['Location']['Tab'];?>']").click();<?php
  } else {
    ?>$("div.Screen-Tabs>div>div:first-child").click();<?php
  }?>
});
</script><?php
    }
} else {}?>
