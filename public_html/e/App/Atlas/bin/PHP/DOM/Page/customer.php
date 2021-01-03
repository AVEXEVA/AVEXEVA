<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'customer';
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
      $r = sqlsrv_query($NEI,
          "SELECT TOP 1
                  OwnerWithRol.ID      AS Customer_ID,
                  OwnerWithRol.Name    AS Customer_Name,
                  OwnerWithRol.Address AS Customer_Street,
                  OwnerWithRol.City    AS Customer_City,
                  OwnerWithRol.State   AS Customer_State,
                  OwnerWithRol.Zip     AS Customer_Zip,
                  OwnerWithRol.Status  AS Customer_Status,
        OwnerWithRol.Website AS Customer_Website
          FROM    nei.dbo.OwnerWithRol
          WHERE   OwnerWithRol.ID = ?
  ;",array($_GET['ID']));
  $Customer = sqlsrv_fetch_array($r);
?><div class="panel panel-primary" style='height:100%;'>
  <h4 style='margin:0px;padding:10px;background-color:whitesmoke;border-bottom:1px solid darkgray;'><a href='customer.php?ID=<?php echo $_GET['ID'];?>'><?php $Icons->Customer();?> Customer: <?php echo $Customer['Customer_Name'];?></a></h4>
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
<div class ='Screen-Tabs shadower' style="margin: 0;border-bottom:3px solid black !important;">
  <div class='row'>
    <?php if(isset($My_Privileges['Customer']) && $My_Privileges['Customer']['User_Privilege'] >= 4){
    ?><div tab='information' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-information.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Info(3);?></div>
        <div class ='nav-text'>Information</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='cod' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-code.php?ID=<?php echo $_GET['ID'];?>');">
    <div class='nav-icon'><?php $Icons->Job(3);?></div>
    <div class ='nav-text'>Code</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Collection']) && $My_Privileges['Collection']['User_Privilege'] >= 4){
    ?><div tab='collection' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-collections.php?ID=<?php echo $_GET['ID'];?>');">
    <div class='nav-icon'><?php $Icons->Collection(3);?></div>
    <div class ='nav-text'>Collections</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Contract']) && $My_Privileges['Contract']['User_Privilege'] >= 4){
    ?><div tab='contract'class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-contracts.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Contract(3);?></div>
        <div class ='nav-text'>Contracts</div>
    </div><?php }?>
    <div tab='feed' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-feed.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons ->Activities(3);?></div>
        <div class ='nav-text'>Feed</div>
    </div>
    <?php if(isset($My_Privileges['Time']) && $My_Privileges['Time']['Group_Privilege'] >= 4){
    ?><div tab='hours' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'hours.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Payroll(3);?></div>
        <div class ='nav-text'>Hours</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='job' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-jobs.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Job(3);?></div>
        <div class ='nav-text'>Jobs</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Invoice']) && $My_Privileges['Invoice']['User_Privilege'] >= 4){
    ?><div tab='invoice' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-invoices.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Invoice(3);?></div>
        <div class ='nav-text'>Invoices</div>
    </div><?php }?>
    <?php
    $r = sqlsrv_query($NEI,"
      SELECT Count(Loc.Loc) AS Counter
      FROM   nei.dbo.Loc
      WHERE  Loc.Owner = ?
    ;",array($_GET['ID']));
    $count = sqlsrv_fetch_array($r)['Counter'];
    if($count == 1){
      $r = sqlsrv_query($NEI,"
      SELECT Loc.Loc AS Location_ID
      FROM   nei.dbo.Loc
      WHERE  Loc.Owner = ?
      ;",array($_GET['ID']));
      $Location_ID = sqlsrv_fetch_array($r)['Location_ID'];
      if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4){
      ?><div tab='location' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="document.location.href='location.php?ID=<?php echo $Location_ID;?>';">
          <div class='nav-icon'><?php $Icons->Location(3);?></div>
          <div class ='nav-text'>Location</div>
      </div><?php }
    } elseif($count > 1) {
      if(isset($My_Privileges['Location']) && $My_Privileges['Location']['User_Privilege'] >= 4){
      ?><div tab='location' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-locations.php?ID=<?php echo $_GET['ID'];?>');">
          <div class='nav-icon'><?php $Icons->Location(3);?></div>
          <div class ='nav-text'>Locations</div>
      </div><?php }
    }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='log' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-log.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Job(3);?></div>
        <div class ='nav-text'>Log</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='maintenance' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-maintenance.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Maintenance(3);?></div>
        <div class ='nav-text'>Maintenance</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='modernization' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-modernization.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Modernization(3);?></div>
        <div class ='nav-text'>Modernization</div>
    </div><?php }?>

    <?php if(isset($My_Privileges['Proposal']) && $My_Privileges['Proposal']['User_Privilege'] >= 4){
    ?><div tab='proposals' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-proposals.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Proposal(3);?></div>
        <div class ='nav-text'>Proposals</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Finances']) && $My_Privileges['Finances']['User_Privilege'] >= 4){
    ?><div tab='pnl' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-pnl.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Financial(3);?></div>
        <div class ='nav-text'>P&L</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Repair']) && $My_Privileges['Repair']['User_Privilege'] >= 4){
    ?><div tab='repair' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-repair.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Repair(3);?></div>
        <div class ='nav-text'>Repair</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Service']) && $My_Privileges['Service']['User_Privilege'] >= 4){
    ?><div tab='service' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-service.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Phone(3);?></div>
        <div class ='nav-text'>Service</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Ticket']) && $My_Privileges['Ticket']['User_Privilege'] >= 4){
    ?><div tab='tickets'class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-tickets.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Ticket(3);?></div>
        <div class ='nav-text'>Tickets</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Time']) && $My_Privileges['Time']['Group_Privilege'] >= 4){
    ?><div tab='timeline' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-timeline.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->History(3);?></div>
        <div class ='nav-text'>Timeline</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4){
    ?><div tab='testing' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-testing.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Testing(3);?></div>
        <div class ='nav-text'>Testing</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Unit']) && $My_Privileges['Unit']['User_Privilege'] >= 4){
    ?><div tab='unit' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-units.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Unit(3);?></div>
        <div class ='nav-text'>Units</div>
    </div><?php }?>
    <?php if(isset($My_Privileges['Violation']) && $My_Privileges['Violation']['User_Privilege'] >= 4){
    ?><div tab='violation' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-violations.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Violation(3);?></div>
        <div class ='nav-text'>Violations</div>
    </div><?php }?>

    <?php if(isset($My_Privileges['User']) && $My_Privileges['User']['User_Privilege'] >= 4){
    ?><div tab='workers' class='Home-Screen-Option col-lg-1 col-md-2 col-xs-3' onClick="someFunction(this,'customer-workers.php?ID=<?php echo $_GET['ID'];?>');">
        <div class='nav-icon'><?php $Icons->Users(3);?></div>
        <div class ='nav-text'>Workers</div>
    </div><?php }?>
  </div>
</div>
<div class='container-content'>

</div>
</div>
<script>
function someFunction(link,URL){
  $(link).siblings().removeClass('active');
  $(link).addClass('active');
  $.ajax({
    url:"cgi-bin/php/element/customer/" + URL,
    success:function(code){
      $("div.container-content").html(code);
    }
  });
}
</script><?php
    }
} else {}?>
