<?php
session_start();
require('../../../php/index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
        sqlsrv_query($Portal,"INSERT INTO Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "ticket.php"));
        $r = sqlsrv_query($NEI,"SELECT *, fFirst AS First_Name, Last as Last_Name FROM Emp WHERE ID= ?",array($_SESSION['User']));
        $My_User = sqlsrv_fetch_array($r);
        $Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
        $r = sqlsrv_query($Portal,"
            SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
            FROM   Portal.dbo.Privilege
            WHERE  User_ID = ?
        ;",array($_SESSION['User']));
        $My_Privileges = array();
        while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
        $Privileged = FALSE;
        if(isset($My_Privileges['Ticket']) && $My_Privileges['Ticket']['User_Privilege'] >= 4 && $My_Privileges['Ticket']['Group_Privilege'] >= 4 && $My_Privileges['Location']['Other_Privilege'] >= 4){$Privileged = TRUE;}
        elseif($My_Privileges['Ticket']['Group_Privilege'] >= 4){
            $r = sqlsrv_query(  $NEI,"SELECT LID FROM nei.dbo.TicketO WHERE TicketO.ID='{$_GET['ID']}'");
            $r2 = sqlsrv_query( $NEI,"SELECT Loc FROM nei.dbo.TicketD WHERE TicketD.ID='{$_GET['ID']}'");
            $r3 = sqlsrv_query( $NEI,"SELECT Loc FROM nei.dbo.TicketDArchive WHERE TicketDArchive.ID='{$_GET['ID']}'");
            $r = sqlsrv_fetch_array($r);
            $r2 = sqlsrv_fetch_array($r2);
            $r3 = sqlsrv_fetch_array($r3);
            $Location = NULL;
            if(is_array($r)){$Location = $r['LID'];}
            elseif(is_array($r2)){$Location = $r2['Loc'];}
            elseif(is_array($r3)){$Location = $r3['Loc'];}
            if(!is_null($Location)){
                $r = sqlsrv_query(  $NEI,"SELECT ID FROM nei.dbo.TicketO WHERE TicketO.LID='{$Location}' AND fWork='{$My_User['fWork']}'");
                $r2 = sqlsrv_query( $NEI,"SELECT ID FROM nei.dbo.TicketD WHERE TicketD.Loc='{$Location}' AND fWork='{$My_User['fWork']}'");
                $r3 = sqlsrv_query( $NEI,"SELECT ID FROM nei.dbo.TicketDArchive WHERE TicketDArchive.Loc='{$Location}' AND fWork='{$My_User['fWork']}'");
                if($r || $r2 || $r3){
                    if($r){$a = sqlsrv_fetch_array($r);}
                    if($r2){$a2 = sqlsrv_fetch_array($r2);}
                    if($r3){$a3 = sqlsrv_fetch_array($r3);}
                    if($a || $a2 || $a3){
                        $Privileged = true;
                    }
                }
            }
            if(!$Privileged){
                if($My_Privileges['Ticket']['User_Privilege'] >= 4 && is_numeric($_GET['ID'])){
                    $r = sqlsrv_query(  $NEI,"SELECT ID FROM nei.dbo.TicketO WHERE TicketO.ID='{$_GET['ID']}' AND fWork='{$User['fWork']}'");
                    $r2 = sqlsrv_query( $NEI,"SELECT ID FROM nei.dbo.TicketD WHERE TicketD.ID='{$_GET['ID']}' AND fWork='{$User['fWork']}'");
                    $r3 = sqlsrv_query( $NEI,"SELECT ID FROM nei.dbo.TicketDArchive WHERE TicketDArchive.ID='{$_GET['ID']}' AND fWork='{$User['fWork']}'");
                    if($r || $r2 || $r3){
                        if($r){$a = sqlsrv_fetch_array($r);}
                        if($r2){$a2 = sqlsrv_fetch_array($r2);}
                        if($r3){$a3 = sqlsrv_fetch_array($r3);}
                        if($a || $a2 || $a3){
                            $Privileged = true;
                        }
                    }
                }
            }
        }
    } elseif($_SESSION['Branch'] == 'Customer' && is_numeric($_GET['ID'])){
            $r  = sqlsrv_query( $NEI,"SELECT Loc.Loc FROM nei.dbo.TicketO        LEFT JOIN nei.dbo.Loc ON TicketO.LID        = Loc.Loc WHERE TicketO.ID=?        AND Loc.Owner = ?;",array($_GET['ID'],$_SESSION['Branch_ID']));
            $r2 = sqlsrv_query( $NEI,"SELECT Loc.Loc FROM nei.dbo.TicketD        LEFT JOIN nei.dbo.Loc ON TicketD.Loc        = Loc.Loc WHERE TicketD.ID=?        AND Loc.Owner = ?;",array($_GET['ID'],$_SESSION['Branch_ID']));
            $r3 = sqlsrv_query( $NEI,"SELECT Loc.Loc FROM nei.dbo.TicketDArchive LEFT JOIN nei.dbo.Loc ON TicketDArchive.Loc = Loc.Loc WHERE TicketDArchive.ID=? AND Loc.Owner = ?;",array($_GET['ID'],$_SESSION['Branch_ID']));
            if($r || $r2 || $r3){
                if($r){$a = sqlsrv_fetch_array($r);}else{$a = false;}
                if($r2){$a2 = sqlsrv_fetch_array($r2);}else{$a2 = false;}
                if($r3){$a3 = sqlsrv_fetch_array($r3);}else{$a3 = false;}
                if($a || $a2 || $a3){
                    $Privileged = true;
                }
            }
    }

    if(!isset($array['ID'])  || !$Privileged){?><html><head></head></html><?php }
    else {
$Ticket = null;
if(isset($_GET['ID']) && is_numeric($_GET['ID'])){
    $r = sqlsrv_query($NEI,"
            SELECT
                TicketO.*,
                Loc.Tag             AS Tag,
                Loc.Loc              AS Location_ID,
                Loc.Address         AS Address,
                Loc.City            AS City,
                Loc.State           AS State,
                Loc.Zip             AS Zip,
                Loc.Latt            AS Lattitude,
                Loc.Latt            AS Latitude,
                Loc.fLong           AS Longitude,
                Job.ID              AS Job_ID,
                Job.fDesc           AS Job_Description,
                OwnerWithRol.ID     AS Owner_ID,
                OwnerWithRol.ID     AS Customer_ID,
                OwnerWithRol.Name   AS Customer,
                JobType.Type        AS Job_Type,
                Elev.ID             AS Unit_ID,
                Elev.Unit           AS Unit_Label,
                Elev.State          AS Unit_State,
                Elev.Type           AS Unit_Type,
                Zone.Name           AS Division,
                TickOStatus.Type    AS Status,
                Emp.ID              AS Employee_ID,
                Emp.fFirst          AS First_Name,
                Emp.Last            AS Last_Name,
                Emp.Title           AS Role,
				        TicketO.fDesc		AS Description,
                'TicketO'           AS Table2
				FROM
                nei.dbo.TicketO
                LEFT JOIN nei.dbo.Loc           ON TicketO.LID      = Loc.Loc
                LEFT JOIN nei.dbo.Job           ON TicketO.Job      = Job.ID
                LEFT JOIN nei.dbo.OwnerWithRol  ON Loc.Owner    = OwnerWithRol.ID
                LEFT JOIN nei.dbo.JobType       ON Job.Type         = JobType.ID
                LEFT JOIN nei.dbo.Elev          ON TicketO.LElev    = Elev.ID
                LEFT JOIN nei.dbo.Zone          ON Loc.Zone         = Zone.ID
                LEFT JOIN nei.dbo.TickOStatus   ON TicketO.Assigned = TickOStatus.Ref
                LEFT JOIN nei.dbo.Emp           ON TicketO.fWork    = Emp.fWork
            WHERE
                TicketO.ID=?;",array($_GET['ID']));
    $Ticket = sqlsrv_fetch_array($r);
    $Ticket['Loc'] = $Ticket['LID'];
    $Ticket['Status'] = (strlen($Ticket['Status']) == 0) ? 'Reviewing' : $Ticket['Status'];
    if($Ticket['ID'] == "" || $Ticket['ID'] == 0 || !isset($Ticket['ID'])){
        $r = sqlsrv_query($NEI,"
            SELECT
                TicketD.*,
                Loc.Tag             AS Tag,
                Loc.Loc              AS Location_ID,
                Loc.Address         AS Address,
                Loc.City            AS City,
                Loc.State           AS State,
                Loc.Zip             AS Zip,
                Loc.Latt            AS Lattitude,
                Loc.Latt            AS Latitude,
                Loc.fLong           AS Longitude,
                Job.ID              AS Job_ID,
                Job.fDesc           AS Job_Description,
                OwnerWithRol.ID     AS Owner_ID,
                OwnerWithRol.ID     AS Customer_ID,
                OwnerWithRol.Name   AS Customer,
                JobType.Type        AS Job_Type,
                Elev.ID             AS Unit_ID,
                Elev.Unit           AS Unit_Label,
                Elev.State          AS Unit_State,
                Elev.Type           AS Unit_Type,
                Zone.Name           AS Division,
                Emp.ID              AS Employee_ID,
                Emp.fFirst          AS First_Name,
                Emp.Last            AS Last_Name,
                Emp.Title           AS Role,
				        'Completed'         AS Status,
                'TicketD'           AS Table2
            FROM
                nei.dbo.TicketD
                LEFT JOIN nei.dbo.Loc           ON TicketD.Loc      = Loc.Loc
                LEFT JOIN nei.dbo.Job           ON TicketD.Job      = Job.ID
                LEFT JOIN nei.dbo.OwnerWithRol  ON Loc.Owner        = OwnerWithRol.ID
                LEFT JOIN nei.dbo.JobType       ON Job.Type         = JobType.ID
                LEFT JOIN nei.dbo.Elev          ON TicketD.Elev     = Elev.ID
                LEFT JOIN nei.dbo.Zone          ON Loc.Zone         = Zone.ID
                LEFT JOIN nei.dbo.Emp           ON TicketD.fWork    = Emp.fWork
            WHERE
                TicketD.ID = ?;",array($_GET['ID']));
        $Ticket = sqlsrv_fetch_array($r);
    }
    if($Ticket['ID'] == "" || $Ticket['ID'] == 0 || !isset($Ticket['ID'])){
        $r = sqlsrv_query($NEI,"
            SELECT
                TicketDArchive.*,
                Loc.Tag             AS Tag,
                Loc.Loc              AS Location_ID,
                Loc.Address         AS Address,
				        Loc.Loc             AS Location_Loc,
                Loc.City            AS City,
                Loc.State           AS State,
                Loc.Zip             AS Zip,
                Loc.Latt            AS Lattitude,
                Loc.Latt            AS Latitude,
                Loc.fLong           AS Longitude,
                Job.ID              AS Job_ID,
                Job.fDesc           AS Job_Description,
                OwnerWithRol.ID     AS Owner_ID,
                OwnerWithRol.ID     AS Customer_ID,
                OwnerWithRol.Name   AS Customer,
                JobType.Type        AS Job_Type,
                Elev.ID             AS Unit_ID,
                Elev.Unit           AS Unit_Label,
                Elev.State          AS Unit_State,
                Elev.Type           AS Unit_Type,
                Zone.Name           AS Division,
                Emp.ID              AS Employee_ID,
				Emp.ID              AS User_ID,
                Emp.fFirst          AS First_Name,
                Emp.Last            AS Last_Name,
                Emp.Title           AS Role,
				'Completed'         AS Status,
                'TicketDArchive'    AS Table2
            FROM
                nei.dbo.TicketDArchive
                LEFT JOIN nei.dbo.Loc           ON TicketDArchive.Loc = Loc.Loc
                LEFT JOIN nei.dbo.Job           ON TicketDArchive.Job = Job.ID
                LEFT JOIN nei.dbo.OwnerWithRol  ON Loc.Owner = OwnerWithRol.ID
                LEFT JOIN nei.dbo.JobType       ON Job.Type = JobType.ID
                LEFT JOIN nei.dbo.Elev          ON TicketDArchive.Elev = Elev.ID
                LEFT JOIN nei.dbo.Zone          ON Loc.Zone = Zone.ID
                LEFT JOIN nei.dbo.Emp           ON TicketDArchive.fWork = Emp.fWork
            WHERE
                TicketDArchive.ID = ?;",array($_GET['ID']));
        $Ticket = sqlsrv_fetch_array($r);
    }
if($Ticket['Table2'] == 'TicketO'){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.TicketDPDA WHERE ID = ?;",array($_GET['ID']));
  $Ticket2 = sqlsrv_fetch_array($r);
} elseif($Ticket['Table2'] == 'TicketD'){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.TicketD WHERE ID = ?;",array($_GET['ID']));
  $Ticket2 = sqlsrv_fetch_array($r);
} elseif($Ticket['Table2'] == 'TicketDArchive'){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.TicketDArchive WHERE ID = ?;",array($_GET['ID']));
  $Ticket2 = sqlsrv_fetch_array($r);
}
if($Ticket['Level'] == 7 && $Ticket['State'] != 'NJ' && (!(strpos($Ticket['Role'], 'HELPER') !== false) || $_SESSION['User'] == 1033)){
  $r = sqlsrv_query($Portal,
    " SELECT  *
      FROM    Portal.dbo.Category_Test
      WHERE   Category_Test.Ticket = ?
    ", array($_GET['ID']));
  $Annual_Test_Check = 'false';
  if($r){
    while($row = sqlsrv_fetch_array($r)){
      $Annual_Test_Check = 'true';
      $Category_Test[$row['ID']] = $row;
    }
  }
}

?>
<div class="panel panel-primary">
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Info(1);?> Description</h4></div>
	<div class='panel-body white-background' style='font-size:14px;padding:10px;'>
		<div class='row'>
			<div class='col-xs-4'><?php $Icons->User(1);?> Worker:</div>
			<div class='col-xs-8'><?php echo proper($Ticket['First_Name'] . " " . $Ticket["Last_Name"]);?></div>
    </div>
    <div class='row'>
			<div class='col-xs-4'><?php $Icons->Calendar(1);?> Date:</div>
			<div class='col-xs-8'><?php echo date("m/d/Y",strtotime($Ticket['EDate']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Description(1);?> Description:</div>
      <div class='colx-s-8'>&nbsp;</div>
    </div>
    <div class='row'>
			<div class='col-xs-12'><pre><?php echo proper($Ticket['fDesc']);?></pre></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Location(1);?> Location:</div>
			<div class='col-xs-8'><?php echo strlen($Ticket['Tag']) > 0 ? $Ticket['Tag'] : 'N/A';?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Unit(1);?> Unit:</div>
      <style>
      .popup {
        position:absolute;
        left:0;
        top:0;
        width:100%;
        height:100%;
        overflow-y:scroll;
        z-index:999999999999;
      }
      </style>
			<?php
      if(isset($_GET['Edit']) || ($Ticket['Assigned'] >= 1 && $Ticket['Assigned'] <= 3)){?>
      <div class='col-xs-8'><button style='width:100%;' onClick='getUnits(this);'><?php
        if(strlen($Ticket['Unit_State']) > 0){
          echo $Ticket['Unit_State'] . ' - ' . $Ticket['Unit_Label'];
        } elseif(is_null($Ticket['Unit_ID'])) {
          echo 'Change Unit';
        } else {
          echo $Ticket['Unit_Label'];
        }?></button></div>
      <?php } else {?>
      <div class='col-xs-8'><?php echo strlen($Ticket['Unit_State']) > 0 ? $Ticket['Unit_State'] . " - " .  $Ticket['Unit_Label'] : $Ticket['Unit_Label'];?></div>
      <?php }?>
      <script>
      function changeUnit(link){
        $.ajax({
          url:"cgi-bin/php/post/ticket-change_unit.php",
          method:"POST",
          data:{
            ID:'<?php echo $_GET['ID'];?>',
            Unit:$(link).attr('rel')
          },
          success:function(code){document.location.href='ticket2.php?ID=<?php echo $_GET['ID'];?>';}
        });
      }
      function getUnits(link){
        $("body").append("<div class='popup' style='background-color:#1d1d1d;'><div class='panel-primary''><div class='panel-heading' style='padding-top:50px;'><h3><?php echo $Ticket['Tag'];?>'s Units</h3></div><div class='panel-body' style='padding:25px;><div class='row'><?php
          $r = sqlsrv_query($NEI,"SELECT * FROM Elev WHERE Elev.Loc = ?",array($Ticket['Location_ID']));
          if($r){while($row = sqlsrv_fetch_array($r)){
            echo "<div class='col-xs-12'><button style='width:100%;height:50px;' onClick='changeUnit(this);' rel='{$row['ID']}'>{$row['State']} - {$row['Unit']}";
          }}
        ?><div class='col-xs-12'>&nbsp;</div><div class='col-xs-12'><button onClick='cancelUnit(this);' style='width:100%;height:50px;'>Cancel</button></div></div></div></div>");
      }
      function cancelUnit(link){
        $(".popup").remove();
      }
      </script>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Job(1);?> Job:</div>
			<div class='col-xs-8'><?php echo strlen($Ticket['Job_Description']) > 0 ? $Ticket['Job_Description'] : 'N/A';?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Ticket(1);?> Status:</div>
      <div class='col-xs-8'><?php echo strlen($Ticket['Status']) > 0 ? $Ticket['Status'] : 'Reviewing';?></div>
    </div>
    <?php if($Ticket['Status'] == 'Reviewing' && !isset($_GET['Edit']) && $My_User['fWork'] == $Ticket['fWork']){?>
    <div class='row'>
      <div class='col-xs-12'><button onClick="document.location.href='ticket2.php?ID=<?php echo $_GET['ID'];?>&Edit=True';" style='width:100%;'>Edit Ticket</button></div>
    </div>
    <?php }?>
  </div>
  <?php
  $A_Latitude = $Ticket['Latitude'];
  $A_Longitude = $Ticket['Longitude'];
  if($A_Latitude == 0 || $A_Longitude == 0){
    $r = sqlsrv_query($NEI,"SELECT * FROM ElevTItem WHERE Elev = ? AND ElevT = 8;", array($Ticket['Unit_ID']));
    if($r){while($row = sqlsrv_fetch_array($r)){
        if($row['fDesc'] == 'Latitude'){$A_Latitude = $row['Value'];}
        if($row['fDesc'] == 'Longitude'){$A_Longitude = $row['Value'];}
    }}
  }
  $M_Latitude = 0;
  $M_Longitude = 0;
  $r = sqlsrv_query($Portal_44, "SELECT Top 1 GPS.Latitude, GPS.Longitude FROM Portal.dbo.GPS WHERE GPS.Employee_ID = ? ORDER BY Time_Stamp DESC;", array($Ticket['Employee_ID']));
  if($r){while($row = sqlsrv_fetch_array($r)){
    $M_Latitude = $row['Latitude'];
    $M_Longitude = $row['Longitude'];
  }}
  if($A_Latitude != 0 && $A_Longitude != 0 && $M_Latitude != 0 && $M_Longitude != 0){?>
    <script>
    function renderMap(){
        var latlng = {lat:<?php echo isset($_GET['Latitude']) ? $_GET['Latitude'] : 40.7831;?>, lng:<?php echo isset($_GET['Longitude']) ? $_GET['Longitude'] : -73.9712;?>};
        var myOptions = {
          zoom: <?php echo isset($_GET['Latitude'], $_GET['Longitude']) ? 18 : 10;?>,
          center: latlng
        };
        map = new google.maps.Map(document.getElementById("DIRECTIONAL_MAP"), myOptions);
        directionsService = new google.maps.DirectionsService;
        directionsDisplay = new google.maps.DirectionsRenderer({
            map: map
        });
        var LookUp_M = new google.maps.LatLng(<?php echo $M_Latitude;?>, <?php echo $M_Longitude;?>);
        var LookUp_A = new google.maps.LatLng(<?php echo $A_Latitude;?>, <?php echo $A_Longitude;?>);
        calculateAndDisplayRoute(directionsService, directionsDisplay, LookUp_M, LookUp_A);
    }
    function calculateAndDisplayRoute(directionsService2, directionsDisplay2, pointA, pointB) {
        directionsService2.route({
          origin: pointA,
          destination: pointB,
          travelMode: google.maps.TravelMode.WALKING
        }, function(response, status) {
            if (status == 'OK') {
                directionsDisplay2.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }
    </script>

    <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Map(1);?> Map / Directions</h4></div>
    <div class='panel-body'>
    <div class='row'><div class='col-xs-12' id='DIRECTIONAL_MAP' style='height:300px;'></div></div>
  </div>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g&callback=renderMap"></script>
  <script type='text/javascript' src='https://maps.googleapis.com/maps/api/directions/json?origin=43.65077%2C-79.378425&destination=43.63881%2C-79.42745&key=AIzaSyAJwGnwOrNUvlYnmB5sdJGkXy8CQsTA46g'></script><?php }?>
  <?php
  $r = sqlsrv_query($NEI,
    " SELECT  Deficiency.ID AS ID,
              Category_Elevator_Part.Name AS Elevator_Part,
              Category_Violation_Condition.Name AS Violation,
              Category_Remedy.Name AS Remedy,
              Deficiency.[Percentage] AS Percentage,
              Deficiency.Comments AS Comments
      FROM    Portal.dbo.Deficiency
              LEFT JOIN Portal.dbo.Category_Elevator_Part ON Deficiency.Elevator_Part = Category_Elevator_Part.ID
              LEFT JOIN Portal.dbo.Category_Remedy ON Deficiency.Remedy = Category_Remedy.ID
              LEFT JOIN Portal.dbo.Category_Violation_Condition ON Deficiency.Violation = Category_Violation_Condition.ID
              LEFT JOIN Portal.dbo.Category_Test ON Deficiency.Category_Test = Category_Test.ID
              LEFT JOIN nei.dbo.Violation ON Category_Test.Violation = Violation.ID
      WHERE   Deficiency.Ticket = ?;",array($Ticket['WorkOrder']));
  $Deficiences = array();
  if($r){while($row = sqlsrv_fetch_array($r)){$Deficiences[$row['ID']] = $row;}}
  $r = sqlsrv_query($NEI,
    " SELECT  Deficiency.ID AS ID,
              Category_Elevator_Part.Name AS Elevator_Part,
              Category_Violation_Condition.Name AS Violation,
              Category_Remedy.Name AS Remedy,
              Deficiency.[Percentage] AS Percentage,
              Deficiency.Comments AS Comments
      FROM    Portal.dbo.Deficiency
              LEFT JOIN Portal.dbo.Category_Elevator_Part ON Deficiency.Elevator_Part = Category_Elevator_Part.ID
              LEFT JOIN Portal.dbo.Category_Remedy ON Deficiency.Remedy = Category_Remedy.ID
              LEFT JOIN Portal.dbo.Category_Violation_Condition ON Deficiency.Violation = Category_Violation_Condition.ID
              LEFT JOIN Portal.dbo.Category_Test ON Deficiency.Category_Test = Category_Test.ID
              LEFT JOIN nei.dbo.Violation ON Category_Test.Violation = Violation.ID
      WHERE   Deficiency.Action = 'M'
              AND Violation.Job = ?;",array($Ticket['Job_ID']));
  if($r){while($row = sqlsrv_fetch_array($r)){$Deficiences[$row['ID']] = $row;}}
  if(count($Deficiences) > 0){
    ?><div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Ticket(1);?> Deficiencies</h4></div>
    <div class='panel-body' style='background-color:#1d1d1d !important;'>
      <?php
        $count = 0;
        foreach($Deficiences AS $index=>$Deficiency){
        $count++;?>
        <div class='row' style='background-color:#2d2d2d !important;margin-top:5px;padding-top:10px;padding-bottom:10px;'>
          <div class='col-xs-1'><?php echo $count;?></div>
          <div class='col-xs-9'><?php echo ucwords($Deficiency['Remedy']);?> <?php echo ucwords($Deficiency['Violation']);?> <?php echo ucwords($Deficiency['Elevator_Part']);?></div>
          <div class='col-xs-2'><input <?php echo $Deficiency['Percentage'] == 1 || $Ticket['Assigned'] <> 3 ? 'disabled' : null;?> onChange='add_Deficiency_Resolution(this);' class='Deficiency_Checkbox' rel='<?php echo $Deficiency['ID'];?>' type='checkbox' style='width:100%;height:25px;' <?php echo $Deficiency['Percentage'] == 1 ? 'checked' : null;?> name='Deficiency[<?php echo $Deficiency['ID'];?>]' /></div>
        </div>
        <?php if(isset($Deficiency['Comments']) && strlen($Deficiency['Comments']) > 1){?><div class='row' style='padding-bottom:10px;background-color:#2d2d2d !important;'>
          <div class='col-xs-1'><?php $Icons->Paragraph(1);?></div>
          <!--<div class='col-xs-2'> Notes:</div>-->
          <div class='col-xs-11' ><?php echo $Deficiency['Comments'];?></div>
          <!--<div class='col-xs-2'>&nbsp;</div>-->
        </div>
        <?php }?>
      <?php }?>
    </div>
  <?php }?>
  <?php
    preg_match('/(.{1,50}[;?.]{1,1}){1,10}/', $Ticket['fDesc'], $matches);
    foreach($matches as $match){
      var_dump($match);
    }
    //$Tasks = explode(";", substr($Ticket['fDesc'], 0, strlen($Ticket['fDesc']) - 1));
    /*$r = sqlsrv_query($Portal, "SELECT Ticket_Tasks.Description, Ticket_Tasks.Percentage FROM Portal.dbo.Ticket_Tasks WHERE Ticket_Tasks.WO = ? AND ? <> '' AND ? <> 0 ORDER BY ID ASC;", array($Ticket['WorkOrder'],$Ticket['WorkOrder'],$Ticket['WorkOrder']));
    if($r){
      $SQL_Tasks_Descriptions = array();
      $SQL_Tasks_Percentages = array();
      while($Task = sqlsrv_fetch_array($r)){
        array_push($SQL_Tasks_Descriptions, $Task['Description']);
        array_push($SQL_Tasks_Percentages, $Task['Percentage']);
      }
    }*/
    ?><div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Ticket(1);?> Tasks</h4></div>
    <div class='panel-body' style='background-color:#1d1d1d !important;'>
      <?php
      preg_match('[;?]', $Ticket['fDesc'], $matches);
      var_dump($matches);
      /*
        $count = 0;
        $count3 = 0;
        if(count($Tasks) > 0){
          foreach($Tasks AS $index=>$Task){
            $count2 = 0;
            foreach($SQL_Tasks_Descriptions as $index2=>$Description) {
              //var_dump($SQL_Tasks_Percentages[$count2]);
              if((trim($Task) == $Description && ($SQL_Tasks_Percentages[$count2] == '1'))){
                $count2++;
                $count3 = $count3 == 0 || $count >= $count3 ? $count + 1 : $count3 + 1;
                ?><div class='row' style='background-color:#2d2d2d !important;margin-top:5px;padding-top:10px;padding-bottom:10px;'>
                  <div class='col-xs-1'><?php echo $count3;?></div>
                  <div class='col-xs-9 Task_Description'><?php echo trim($Task);?></div>
                  <div class='col-xs-2'><input disabled checked type='checkbox' class='Task_Checkbox' onChange='toggleTaskResolution(this)' name='Task[<?php echo $count;?>]' /></div>
                </div><?php
                continue 2;
              }
              $count2++;
            }
            $count = $count <= $count3 ? $count3 + 1 : $count + 1;
            ?>
            <div class='row' style='background-color:#2d2d2d !important;margin-top:5px;padding-top:10px;padding-bottom:10px;'>
              <div class='col-xs-1'><?php echo $count;?></div>
              <div class='col-xs-9 Task_Description'><?php echo trim($Task);?></div>
              <div class='col-xs-2'><input <?php if($Ticket['Assigned'] > 3 || $Ticket['Assigned'] == 1){?>disabled<?php }?> type='checkbox' class='Task_Checkbox' onChange='toggleTaskResolution(this)' name='Task[<?php echo $count;?>]' /></div>
            </div>
          <?php
        }
      }*/?>
    </div>
  <?php ?>
  <script>
  function toggleTaskResolution(link){
    if($(link).prop('checked')){
      $("textarea[name='Resolution']").val($("textarea[name='Resolution']").val().length > 0 ?
      html_unescape($("textarea[name='Resolution']").val()) + '\n' + 'Completed: ' + html_unescape($(link).parent().prev().html()) :
      'Completed: ' + html_unescape($(link).parent().prev().html()));
    } else {
      $("textarea[name='Resolution']").val($('textarea[name="Resolution"]').val().replace('Completed: ' + $(link).parent().prev().html(), ''));
      $("textarea[name='Resolution']").val($('textarea[name="Resolution"]').val().replace('\n\n'));
    }
  }
  </script>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Ticket(1);?> Work Orders</h4></div>
  <div class='panel-body'>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
    <script>
    var Deficiencies = [];
    function html_unescape(s) {
      var div = document.createElement("div");
      div.innerHTML = s;
      return div.textContent || div.innerText; // IE is different
    }
    function add_Deficiency_Resolution(link){
      if($(link).prop('checked',false)){
        var match = $("textarea[name='Resolution']").replace($(link).val(), '');
      } else {
        $("textarea[name='Resolution']").val($("textarea[name='Resolution']").val().length > 0 ?
          html_unescape($("textarea[name='Resolution']").val()) + '\n' + 'Completed: ' + html_unescape($(link).parent().prev().html()) :
          'Completed: ' + html_unescape($(link).parent().prev().html()));
      }
    }
    </script>
    <script>
    function changeWorkOrder(link){
      $.ajax({
        url:"cgi-bin/php/post/ticket-change_work_order.php",
        method:"POST",
        data:{
          ID:'<?php echo $_GET['ID'];?>',
          Work_Order:$(link).attr('rel')
        },
        success:function(code){document.location.href='ticket2.php?ID=<?php echo $_GET['ID'];?>';}
      });
    }
    function getWorkOrders(link){
      $("body").append("<div class='popup' style='background-color:#1d1d1d;'><div class='panel-primary''><div class='panel-heading' style='padding-top:50px;'><h3><?php echo $Ticket['Tag'];?>'s Work Orders</h3></div><div class='panel-body' style='padding:25px;><div class='row'><?php
        $r = sqlsrv_query($NEI,"SELECT TicketO.WorkOrder FROM nei.dbo.TicketO WHERE TicketO.LID = ? AND TicketO.Assigned = 6 AND TicketO.fWork = ? AND TicketO.WorkOrder IS NOT NULL AND TicketO.WorkOrder <> 0 AND TicketO.WorkOrder <> '' AND TicketO.WorkOrder <> ? AND TicketO.Assigned >= 5 GROUP BY TicketO.WorkOrder;",array($Ticket['Location_ID'],$My_User['fWork'],$_GET['ID']));
        if($r){while($row = sqlsrv_fetch_array($r)){
          echo "<div class='col-xs-12'><button style='width:100%;height:50px;' onClick='changeWorkOrder(this);' rel='{$row['WorkOrder']}'>{$row['WorkOrder']}";
        }}
      ?><div class='col-xs-12'>&nbsp;</div><div class='col-xs-12'><button onClick='changeWorkOrder(this);' rel='<?php echo $_GET['ID'];?>' style='width:100%;height:50px;'>Clear</button></div><div class='col-xs-12'>&nbsp;</div><div class='col-xs-12'><button onClick='cancelWorkOrder(this);' style='width:100%;height:50px;'>Cancel</button></div></div></div></div>");
    }
    function cancelWorkOrder(link){
      $(".popup").remove();
    }
    </script>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Info(1);?> Change W.O.:</div>
      <?php
      if(isset($_GET['Edit']) || ($Ticket['Assigned'] >= 1 && $Ticket['Assigned'] <= 3)){?>
      <div class='col-xs-8'><button style='width:100%;' onClick='getWorkOrders(this);'><?php
        if(strlen($Ticket['WorkOrder']) > 0){
          echo $Ticket['WorkOrder'];
        } else {
          echo 'Change Work Order';
        }?></button></div>
      <?php } else {?>
      <div class='col-xs-8'><?php echo strlen($Ticket['WorkOrder']) > 0 ? $Ticket['WorkOrder'] : 'No Work Order';?></div>
      <?php }?>
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
    <?php $r = sqlsrv_query($NEI,"SELECT * FROM (
      (SELECT TicketO.ID, TicketO.WorkOrder FROM nei.dbo.TicketO WHERE TicketO.WorkOrder = ? AND TicketO.WorkOrder <> 0)
      UNION ALL
      (SELECT TicketD.ID, TicketD.WorkOrder  FROM nei.dbo.TicketD WHERE TicketD.WorkOrder = ? AND TicketD.WorkOrder <> 0)
      UNION ALL
      (SELECT TicketDArchive.ID, TicketDArchive.WorkOrder  FROM nei.dbo.TicketDArchive WHERE TicketDArchive.WorkOrder = ? AND TicketDArchive.WorkOrder <> 0)
    ) AS Tickets WHERE Tickets.ID <> ?;",array($Ticket['WorkOrder'],$Ticket['WorkOrder'],$Ticket['WorkOrder'],$_GET['ID']));
    $i = 1;
    if($r){while($row = sqlsrv_fetch_array($r)){
      ?><div class='row'><div class='col-xs-4' style='text-align:right;'><?php echo $i;?>.</div><div class='col-xs-8' onClick="document.location.href='ticket2.php?ID=<?php echo $row['ID'];?>';">Ticket #<?php echo $row['ID'];?></div></div><?php
      $i++;
    }}?>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
  </div>
	<?php if(isset($_GET['Edit']) && $Ticket['Table2'] == 'TicketO' && $Ticket['Employee_ID'] = $_SESSION['User']){?>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Time</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Delivery(1);?> En Route:</div>
			<div class='col-xs-8' id='en-route'><?php echo $Ticket['TimeRoute'] != '' ? date("h:i A",strtotime($Ticket['TimeRoute'])) : date("h:i A",strtotime($Ticket['TimeSite']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Resident(1);?> At Work:</div>
			<div class='col-xs-8' id='on-site'><?php echo date("h:i A",strtotime($Ticket['TimeSite']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Check(1);?> Completed:</div>
      <div class='col-xs-8' id='completed'><?php echo date("h:i A",strtotime($Ticket['TimeComp']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Regular:</div>
      <div class='col-xs-2'><input type='text' id='time-regular' name='time-regular' size='3' value='<?php echo $Ticket2['Reg'];?>' /></div>
      <div class='col-xs-6' ><div id='slider-regular' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Overtime:</div>
      <div class='col-xs-2'><input type='text' id='time-overtime' name='time-overtime' size='3' /></div>
      <div class='col-xs-6'><div id='slider-overtime' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Differential:</div>
      <div class='col-xs-2'><input type='text' id='time-nightdiff' name='time-nightdiff' size='3' /></div>
      <div class='col-xs-6'><div id='slider-nightdiff' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Doubletime:</div>
      <div class='col-xs-2'><input type='text' id='time-doubletime' name='time-doubletime' size='3' /></div>
      <div class='col-xs-6' ><div id='slider-doubletime' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Total:</div>
      <div class='col-xs-2'><input type='text' id='timeTotal' name='Total' size='3' value='<?php echo $total;?>' disabled /></div>
      <div class='col-xs-6' id='permaTotal'>&nbsp;</div>
      <script>
      $(document).ready(function(){
        calculate_Total();
      });
      </script>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Expenses</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='row expenses'>
      <div class='col-xs-4'><?php $Icons->Payroll(1);?> Car:</div>
      <div class='col-xs-8'><input type='text' name='CarExpenses' placeholder='$0.00' style='width:100%;' value='<?php echo $Ticket2['Zone'];?>' /></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><?php $Icons->Payroll(1);?> Other:</div>
      <div class='col-xs-8'><input type='text' name='OtherExpenses' placeholder='$0.00' style='width:100%;' value='<?php echo $Ticket2['OtherE'];?>'  /></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><i class="fa fa-subway fa-1x fa-fw" aria-hidden="true"></i> Metro:</div>
      <div class='col-xs-8'><input  name='Metro' value='2.75' type='checkbox' onChange='toggleMetro(this);' /></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><i class="fa fa-paperclip fa-1x fa-fw" aria-hidden="true"></i> Photo:</div>
      <div class='col-xs-8'><form id='Receipt' enctype='multipart/form-data' method='POST'><input type='file' name='Receipt'  style='color:white !important;' /></form></div>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Resolution</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='row resolution'>
      <div class='col-xs-4'><?php $Icons->Blank();?> Chargeable:</div>
      <div class='col-xs-8'><input type='checkbox' value='1' name='Chargeable' style='height:15px;width:15px;' /></div>
    </div>
    <div class='row resolution'>
      <div class='col-xs-4'><?php $Icons->Blank(1);?> Follow Up:</div>
      <div class='col-xs-8'><input type='checkbox' value='1' name='Follow_Up' style='height:15px;width:15px;' /></div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row resolution'>
      <div class='col-xs-4'><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Resolution:</div>
      <div class='col-xs-8'><select onChange='toggle_Resolution_Items(this);' name='Resolutions' style='color:black !important;width:100%;' ><?php
        $r = sqlsrv_query($Portal,"SELECT * FROM Portal.dbo.Resolution ORDER BY Resolution.[Name] ASC;");
        if($r){while($row = sqlsrv_fetch_array($r)){?><option value='<?php echo $row['Name'];?>'><?php echo $row['Name'];?></option><?php }}
      ?></select></div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row Resolution-Items'>
      <div class='col-xs-4'>&nbsp;</div>
      <div class='col-xs-8'><select name='Item_Type' multiple style='color:black !important;width:100%;' ><?php
        $r = sqlsrv_query($Portal,"SELECT * FROM Portal.dbo.Item_Type ORDER BY Item_Type.[Name] ASC;");
        if($r){while($row = sqlsrv_fetch_array($r)){?><option value='<?php echo $row['Name'];?>'><?php echo $row['Name'];?></option><?php }}
      ?></select></div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-4'>&nbsp;</div>
      <div class='col-xs-8'><button style='width:100%;' onClick='add_Resolution(this);'>Add Resolution</button></div>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'><textarea id='TextareaResolution' name='Resolution' style='width:100%;' rows='5' placeholder='Resolution Description'><?php echo $Ticket2['DescRes'];?></textarea></div>
      <!--<div class='col-xs-8'><input id='resolution_codes' style='width:100%;' /></div>
      <div class='col-xs-12'><textarea placeholder='Custom Resolution' style='width:100%;' rows='5' name='Resolution'><?php echo $Ticket2['DescRes'];?></textarea></div>-->
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-pencil fa-1x fa-fw" aria-hidden="true"></i> Signature</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='email row'>
      <div class='col-xs-4'><?php $Icons->Email(1);?> Email:</div>
      <div class='col-xs-8'><input type='checkbox' value='0' onchange='toggle_email_person();' /></div>
    </div>

    <div class='email-person row'>
      <div class='col-xs-4'><?php $Icons->Blank(1);?> Address:</div>
      <div class='col-xs-8'><input type='text' name='Email' style='width:100%;' /></div>
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
    <div class='signature row'>
      <div class='col-xs-4'><?php $Icons->Contract(1);?> Signee:</div>
      <div class='col-xs-8'><input type='text' name='Signature_Name' style='width:100%;' value='<?php echo $Ticket2['SignatureText'];?>' /></div>
      <div class='col-xs-12'><button onClick='clearCanvas();' style='width:100%;'>Clear Signature</button></div>
      <div class='col-xs-12'><canvas id='signature' style='width:100%;touch-action: none;' height='200px'></canvas></div>
      <?php if(strlen($Ticket['WorkOrder']) > 0){?><div class='col-xs-12'><button style='width:100%;' onClick='applySignature(this);'>Apply Signature to Work Order(s)</button></div><?php }?>
      <script>
      var img = new Image();
      img.onload = function() {
          var ctx = document.getElementById('signature').getContext('2d');
          ctx.drawImage(img, 0, 0);
      }
      img.src = 'media/images/signatures/<?php echo $_GET['ID'];?>.jpg';
      </script>
      <script>
      $(document).ready(function(){
        $("canvas#signature").attr('width',$("canvas#signature").parent().width() + "px");
      });
      // Set up mouse events for drawing
      var canvas = document.getElementById("signature");
      var ctx = canvas.getContext("2d");
      function clearCanvas(){
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.beginPath();
        ctx.fillStyle = "white";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
      }

      ctx.strokeStyle = "#222222";
      ctx.lineWith = 2;
      var drawing = false;
      var mousePos = { x:0, y:0 };
      var lastPos = mousePos;
      canvas.addEventListener("mousedown", function (e) {
              drawing = true;
        lastPos = getMousePos(canvas, e);
      }, false);
      canvas.addEventListener("mouseup", function (e) {
        drawing = false;
      }, false);
      canvas.addEventListener("mousemove", function (e) {
        mousePos = getMousePos(canvas, e);
      }, false);

      // Get the position of the mouse relative to the canvas
      function getMousePos(canvasDom, mouseEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
          x: mouseEvent.clientX - rect.left,
          y: mouseEvent.clientY - rect.top
        };
      }
      window.requestAnimFrame = (function (callback) {
              return window.requestAnimationFrame ||
                 window.webkitRequestAnimationFrame ||
                 window.mozRequestAnimationFrame ||
                 window.oRequestAnimationFrame ||
                 window.msRequestAnimaitonFrame ||
                 function (callback) {
              window.setTimeout(callback, 1000/60);
                 };
      })();
      function renderCanvas() {
        if (drawing) {
          ctx.moveTo(lastPos.x, lastPos.y);
          ctx.lineTo(mousePos.x, mousePos.y);
          ctx.stroke();
          lastPos = mousePos;
        }
      }

      // Allow for animation
      (function drawLoop () {
        requestAnimFrame(drawLoop);
        renderCanvas();
      })();
      // Set up touch events for mobile, etc
      canvas.addEventListener("touchstart", function (e) {
        document.getElementById("TextareaResolution").blur();
        e.preventDefault();
              mousePos = getTouchPos(canvas, e);
        var touch = e.touches[0];
        var mouseEvent = new MouseEvent("mousedown", {
          clientX: touch.clientX,
          clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
      }, false);
      canvas.addEventListener("touchend", function (e) {
        var mouseEvent = new MouseEvent("mouseup", {});
        canvas.dispatchEvent(mouseEvent);
      }, false);
      canvas.addEventListener("touchmove", function (e) {
        var touch = e.touches[0];
        var mouseEvent = new MouseEvent("mousemove", {
          clientX: touch.clientX,
          clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
      }, false);

      // Get the position of a touch relative to the canvas
      function getTouchPos(canvasDom, touchEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
          x: touchEvent.touches[0].clientX - rect.left,
          y: touchEvent.touches[0].clientY - rect.top
        };
      }
      // Prevent scrolling when touching the canvas
      document.body.addEventListener("touchstart", function (e) {
        if (e.target == canvas) {
          //e.preventDefault();
        }
      }, false);
      document.body.addEventListener("touchend", function (e) {
        if (e.target == canvas) {
          //e.preventDefault();
        }
      }, false);
      document.body.addEventListener("touchmove", function (e) {
        if (e.target == canvas) {
          //e.preventDefault();
        }
      }, false);
      </script>
      <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
      <div class='row timesheet'>
        <div class='col-xs-12'><button onClick='post_time_allocation(this);' style='width:100%;height:50px;'>Save</div>
      </div>
      <div class='row'>
        <div class='col-xs-12'>&nbsp;</div>
        <div class='col-xs-12'>&nbsp;</div>
        <div class='col-xs-12'>&nbsp;</div>
      </div>
    <?php } elseif((isset($Ticket['TimeSite']) && strlen($Ticket['TimeSite']) > 0 && date("h:i A",strtotime($Ticket['TimeSite'])) != '12:00 AM') || $Ticket['Status'] == 'Completed' || $Ticket['Employee_ID'] != $_SESSION['User']){?>
      <?php if((date("h:i A",strtotime($Ticket['TimeComp'])) != '12:00 AM' && ($Ticket['Table2'] == 'TicketD' || $Ticket['Table2'] == 'TicketDArchive' || ($Ticket['Status'] == 'Reviewing' && $Ticket['Table2'] == 'TicketO'))) || $Ticket['Employee_ID'] != $_SESSION['User'] || $Ticket['Table2'] == 'TicketD' || $Ticket['Table2'] == 'TicketDArchive'){?>
  <?php
  if($Ticket['Level'] == 7 && $Annual_Test_Check == 'true'){?>
    <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Category Test</h4></div>
    <div class='panel-body' style='padding:10px;font-size:14px;'>
      <div class='row'>
        <div class='col-xs-4'>Category Test:</div>
        <?php
        $asdf = false;
        foreach($Category_Test as $ID=>$row){
          if($asdf){
            ?><div class='col-xs-4'>&nbsp;</div><?php
          } else {
            $asdf = true;
          }
          ?><div class='col-xs-8'><button onClick="document.location.href='category_test.php?ID=<?php echo $ID;?>&container=0&header=1';" style='width:100%;height:50px;'>Category Test #<?php echo $ID;?></div><?php
        }
        ?>
      </div>
    </div>
  <?php } elseif($Ticket['Level'] == 7 && $Annual_Test_Check == 'false'){?>
    <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Category Test</h4></div>
    <div class='panel-body' style='padding:10px;font-size:14px;'>
      <div class='row'>
        <div class='col-xs-4'>Category Test:</div>
        <div class='col-xs-8'><button onClick="document.location.href='category-test6.php?Ticket=<?php echo $_GET['ID'];?>';" style='width:100%;height:50px;'>New Category Test(s)</button></button></div>
      </div>
    </div>
  <?php }?>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Time</h4></div>
  <div class='panel-body' style='padding:10px;font-size:14px;'>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Delivery(1);?> En Route:</div>
			<div class='col-xs-8' id='en-route'><?php echo is_null($Ticket['TimeRoute']) ? 'N/A' : date("h:i A",strtotime($Ticket['TimeRoute']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> On Site:</div>
			<div class='col-xs-8' id='on-site'><?php echo is_null($Ticket['TimeSite']) ? 'N/A' : date("h:i A",strtotime($Ticket['TimeSite']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Done:</div>
      <div class='col-xs-8' id='completed'><?php echo is_null($Ticket['TimeComp']) ? 'N/A' : date("h:i A",strtotime($Ticket['TimeComp']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Regular:</div>
      <div class='col-xs-8'><?php echo $Ticket2['Reg'];?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Overtime:</div>
      <div class='col-xs-8'><?php echo $Ticket2['OT'];?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Doubletime:</div>
      <div class='col-xs-8'><?php echo $Ticket2['DT'];?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Doubletime:</div>
      <div class='col-xs-8'><?php echo $Ticket2['TT'];?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Total:</div>
      <div class='col-xs-2'><input type='text' id='timeTotal' name='Total' size='3' value='<?php echo $Ticket2['Total'];?>' disabled /></div>
      <div class='col-xs-6' id='permaTotal'>&nbsp;</div>
      <script>
      $(document).ready(function(){
        calculate_Total();
      });
      </script>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Payroll(1);?> Expenses</h4></div>
  <div class='panel-body' style='padding:10px;font-size:14px;'>
    <div class='row expenses'>
      <div class='col-xs-4'><?php $Icons->Payroll(1);?> Car:</div>
      <div class='col-xs-8'><?php echo $Ticket2['Zone'];?></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><?php $Icons->Payroll(1);?> Other:</div>
      <div class='col-xs-8'><?php echo $Ticket2['OtherE'];?></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><i class="fa fa-paperclip fa-1x fa-fw" aria-hidden="true"></i> Photo:</div>
      <?php
        $r = sqlsrv_query($Portal,'SELECT [TicketPic].[PicData] FROM nei.dbo.TicketPic WHERE [TicketPic].TicketID = ?',array($_GET['ID']));
        $i = 0;
        if($r){while($row = sqlsrv_fetch_array($r)){
          $row['Type'] = 'image/jpeg';
          if($i > 0){?><div class='col-xs-4'>&nbsp;</div><?php }
          ?><div class='col-xs-8'><img width='100%' src="<?php print "data:" . $row['Type'] . ";base64, " . $row['PicData'];?>" /></div><?php
          $i++;
        }}
      ?>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Resolution</h4></div>
  <div class='panel-body' style='padding:10px;font-size:14px;'>
    <div class='row resolution'>
      <div class='col-xs-4'>Chargeable:</div>
      <div class='col-xs-8'><input type='checkbox' disabled <?php echo isset($Ticket2['Charge']) && $Ticket2['Charge'] == 1 ? 'checked' : '';?> /></div>
    </div>
    <div class='row resolution'>
      <div class='col-xs-4'>Work Complete:</div>
      <div class='col-xs-8'><input type='checkbox' disabled <?php echo isset($Ticket2['WorkComplete']) && $Ticket2['WorkComplete'] == 1 ? 'checked' : '';?> /></div>
    </div>
    <div class='row resolution'>
      <div class='col-xs-12'>Resolution:</div>
      <div class='col-xs-12'><textarea style='width:100%;' rows='5' disabled><?php echo $Ticket2['DescRes'];?></textarea></div>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-pencil fa-1x fa-fw" aria-hidden="true"></i> Signature</h4></div>
  <div class='panel-body' style='padding:10px;font-size:14px;'>
    <div class='email row'>
      <div class='col-xs-4'><?php $Icons->Email(1);?> Email:</div>
      <div class='col-xs-8'><?php
      $r = sqlsrv_query($Portal,"SELECT * FROM Ticket_Email WHERE Ticket_Email.Ticket = ?;",array($_GET['ID']));
      $i = 0;
      if($r){while($row = sqlsrv_fetch_array($r)){
        if($i == 0){echo $row['Email'];}
        else {?></div><div class='col-xs-4'>&nbsp;</div><div class='col-xs-8'><?php echo $row['Email'];}
        $i++;
      }}
      ?></div>
    </div>
    <div class='signature row'>
      <div class='col-xs-4'><?php $Icons->Contract(1);?> Signee:</div>
      <div class='col-xs-8'><?php echo isset($Ticket2['SignatureText']) ? $Ticket2['SignatureText'] : '';?></div>
      <div class='col-xs-12'><img style='width:100%;' src="data:image/jpeg;base64,<?php
        //echo file_get_contents("media/images/signatures/index.php?ID={$_GET['ID']}");
        $r = sqlsrv_query($NEI,"SELECT * FROM PDATicketSignature WHERE PDATicketSignature.PDATicketID = ?",array($_GET['ID']));
        if($r){
          echo base64_encode(sqlsrv_fetch_array($r)['Signature']);
        }
      ?>" /></div>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Comments</h4></div>
  <div class='panel-body' style='padding:10px;font-size:14px;'>
    <div class='row' style='margin-top:10px;'>
      <?php if(isset($Ticket2['Comments']) && strlen($Ticket2['Comments']) > 0){?>
      <div class='col-xs-12'><textarea disabled style='width:100%;' rows='5' placeholder='Enter Internal Comments Here'><?php echo $Ticket2['Comments'];?></textarea></div><?php
      } else {
      ?><div class='col-xs-12'><textarea style='width:100%;' rows='5' name='Internal_Comments' placeholder='Enter Internal Comments Here'></textarea></div><div class='col-xs-12'><button style='width:100%;height:45px;' onClick='save_internal_comments(this);'>Save</button></div><?php }?>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
  </div>
  <?php } else {?>
    <?php
    if($Ticket['Level'] == 7 && $Annual_Test_Check == 'true'){?>
      <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Category Test</h4></div>
      <div class='panel-body' style='padding:10px;font-size:14px;'>
        <div class='row'>
          <div class='col-xs-4'>Category Test:</div>
          <?php
          $asdf = false;
          foreach($Category_Test as $ID=>$row){
            if($asdf){
              ?><div class='col-xs-4'>&nbsp;</div><?php
            } else {
              $asdf = true;
            }
            ?><div class='col-xs-8'><button onClick="document.location.href='category_test.php?ID=<?php echo $ID;?>&container=0&header=1';" style='width:100%;height:50px;'>Category Test #<?php echo $ID;?></div><?php
          }
          ?>
        </div>
      </div>
    <?php } elseif($Ticket['Level'] == 7 && $Annual_Test_Check == 'false'){?>
      <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Category Test</h4></div>
      <div class='panel-body' style='padding:10px;font-size:14px;'>
        <div class='row'>
          <div class='col-xs-4'>Category Test:</div>
          <div class='col-xs-8'><button onClick="document.location.href='category-test6.php?Ticket=<?php echo $_GET['ID'];?>';" style='width:100%;height:50px;'>New Category Test(s)</button></button></div>
        </div>
      </div>
    <?php }?>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Time</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='row'>
      <div class='col-xs-12'><button style='width:100%;height:35px;' id='reset_time' onclick='reset_time(this);'>Reset Ticket</button></div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Delivery(1);?> En Route:</div>
			<div class='col-xs-8' id='en-route'><?php echo $Ticket['TimeRoute'] != '' ? date("h:i A",strtotime($Ticket['TimeRoute'])) : date("h:i A",strtotime($Ticket['TimeSite']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Resident(1);?> At Work:</div>
			<div class='col-xs-8' id='on-site'><?php echo date("h:i A",strtotime($Ticket['TimeSite']));?></div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-4'><?php $Icons->Check(1);?> Completed:</div>
      <div class='col-xs-8' id='completed'><button style='width:100%;' onclick='post_time_completed(this);'>Completed Work</button></div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Regular:</div>
      <div class='col-xs-2'><input type='text' id='time-regular' name='time-regular' size='3' /></div>
      <div class='col-xs-6' ><div id='slider-regular' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Overtime:</div>
      <div class='col-xs-2'><input type='text' id='time-overtime'name='time-overtime' size='3' /></div>
      <div class='col-xs-6'><div id='slider-overtime' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Differential:</div>
      <div class='col-xs-2'><input type='text' id='time-nightdiff'name='time-nightdiff' size='3' /></div>
      <div class='col-xs-6'><div id='slider-nightdiff' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Doubletime:</div>
      <div class='col-xs-2'><input type='text' id='time-doubletime'name='time-doubletime' size='3' /></div>
      <div class='col-xs-6' ><div id='slider-doubletime' class='slider'></div></div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-4'><?php $Icons->Timesheet(1);?> Total:</div>
      <div class='col-xs-2'><input type='text' id='timeTotal' name='Total' size='3' value='<?php echo $total;?>' disabled /></div>
      <div class='col-xs-6' id='permaTotal'>&nbsp;</div>
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
    <div class='row'>
      <div class='col-xs-4'>30m Lunch</div>
      <div class='col-xs-2'><input class='lunch' type='checkbox' onChange='time_lunch(this, .5);' /></div>
      <div class='col-xs-4'>60m Lunch</div>
      <div class='col-xs-2'><input class='lunch' type='checkbox' onChange='time_lunch(this, 1);' /></div>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Expenses</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='row expenses'>
      <div class='col-xs-4'><?php $Icons->Payroll(1);?> Car:</div>
      <div class='col-xs-8'><input type='text' name='CarExpenses' placeholder='$0.00' style='width:100%;' /></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><?php $Icons->Payroll(1);?> Other:</div>
      <div class='col-xs-8'><input type='text' name='OtherExpenses' placeholder='$0.00' style='width:100%;' /></div>
    </div>

    <div class='row expenses'>
      <div class='col-xs-4'><i class="fa fa-subway fa-1x fa-fw" aria-hidden="true"></i> Metro:</div>
      <div class='col-xs-8'><input  name='Metro' value='2.75' type='checkbox' onChange='toggleMetro(this);' /></div>
    </div>
    <div class='row expenses'>
      <div class='col-xs-4'><i class="fa fa-paperclip fa-1x fa-fw" aria-hidden="true"></i> Photo:</div>
      <div class='col-xs-8'><form id='Receipt' enctype='multipart/form-data' method='POST'><input type='hidden' name='ID' value='<?php echo $_GET['ID'];?>' /><input type='file' name='Receipt' style='color:white !important;'/></form></div>
    </div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Resolution</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='row resolution'>
      <div class='col-xs-4'><?php $Icons->Blank();?> Chargeable</div>
      <div class='col-xs-8'><input type='checkbox' value='1' name='Chargeable' style='height:15px;width:15px;' /></div>
    </div>
    <div class='row resolution'>
      <div class='col-xs-4'><?php $Icons->Blank(1);?> Follow Up:</div>
      <div class='col-xs-8'><input type='checkbox' value='1' name='Follow_Up' style='height:15px;width:15px;' /></div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row resolution'>
      <div class='col-xs-4'><i class="fa fa-paragraph fa-1x fa-fw" aria-hidden="true"></i> Resolution:</div>
      <div class='col-xs-8'><select onChange='toggle_Resolution_Items(this);' name='Resolutions' style='color:black !important;width:100%;' ><?php
        $r = sqlsrv_query($Portal,"SELECT * FROM Portal.dbo.Resolution ORDER BY Resolution.[Name] ASC;");
        if($r){while($row = sqlsrv_fetch_array($r)){?><option value='<?php echo $row['Name'];?>'><?php echo $row['Name'];?></option><?php }}
      ?></select></div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row Resolution-Items'>
      <div class='col-xs-4'>&nbsp;</div>
      <div class='col-xs-8'><select name='Item_Type' multiple style='color:black !important;width:100%;' ><?php
        $r = sqlsrv_query($Portal,"SELECT * FROM Portal.dbo.Item_Type ORDER BY Item_Type.[Name] ASC;");
        if($r){while($row = sqlsrv_fetch_array($r)){?><option value='<?php echo $row['Name'];?>'><?php echo $row['Name'];?></option><?php }}
      ?></select></div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row'>
      <div class='col-xs-4'>&nbsp;</div>
      <div class='col-xs-8'><button style='width:100%;' onClick='add_Resolution(this);'>Add Resolution</button></div>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'><textarea  id='TextareaResolution' name='Resolution' style='width:100%;' rows='5' placeholder='Resolution Description'></textarea></div>
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
  </div>
  <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><i class="fa fa-pencil fa-1x fa-fw" aria-hidden="true"></i> Signature</h4></div>
  <div class='panel-body' style='padding:10px;'>
    <div class='email row'>
      <div class='col-xs-4'><?php $Icons->Email(1);?> Email:</div>
      <div class='col-xs-8'><input type='checkbox' value='0' onchange='toggle_email_person();' /></div>
    </div>

    <div class='email-person row'>
      <div class='col-xs-4'><?php $Icons->Blank(1);?> Address:</div>
      <div class='col-xs-8'><input type='text' name='Email' style='width:100%;' /></div>
      <script>
      var availableEmails = [
        <?php
          $r = sqlsrv_query($Portal,
          " SELECT Ticket_Email.Email
            FROM (
                    ( SELECT TicketD.Loc AS Location_ID,
                            TicketD.ID  AS ID
                      FROM nei.dbo.TicketD
                      WHERE TicketD.Loc = ?)
                    UNION ALL
                    ( SELECT TicketO.LID AS Location_ID,
                            TicketO.ID  AS ID
                      FROM nei.dbo.TicketO
                      WHERE TicketO.LID = ?)
                    ) AS Tickets
                    INNER JOIN Portal.dbo.Ticket_Email ON Ticket_Email.Ticket = Tickets.ID
            GROUP BY  Ticket_Email.Email;",array($Ticket['Location_ID'],$Ticket['Location_ID']));
          $Emails = array();
          if($r){while($row = sqlsrv_fetch_array($r)){
            $Emails[] = $row['Email'];
          }}
          echo "'" . implode("','",$Emails) . "'";
        ?>
      ];
      $(document).ready(function(){
        $("input[name='Email']").autocomplete({source:availableEmails})
      });
      </script>
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
    <div class='signature row'>
      <div class='col-xs-4'><?php $Icons->Contract(1);?> Signee:</div>
      <div class='col-xs-8'>
        <input type='text' id='Signature_Name' name='Signature_Name' style='width:100%;'  />
      </div>
      <!--<div class='col-xs-4'>&nbsp;</div>-->
      <div class='col-xs-12'><button onClick='clearCanvas();' style='width:100%;'>Clear Signature</button></div>
      <div class='col-xs-12'><canvas id='signature' style='width:100%;' height='200px'></canvas></div>
      <?php if(strlen($Ticket['WorkOrder']) > 0){?><div class='col-xs-12'><button style='width:100%;' onClick='applySignature(this);'>Apply Signature to Work Order(s)</button></div><?php }?>
      <script>
      $(document).ready(function(){
        $("canvas#signature").attr('width',$("canvas#signature").parent().width() + "px");
        clearCanvas();
      });
      // Set up mouse events for drawing
      var canvas = document.getElementById("signature");
      var ctx = canvas.getContext("2d");
      function clearCanvas(){
        ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
        ctx.beginPath();
        ctx.fillStyle = "white";
        ctx.fillRect(0, 0, canvas.width, canvas.height);
      }

      ctx.strokeStyle = "#222222";
      ctx.lineWith = 2;
      var drawing = false;
      var mousePos = { x:0, y:0 };
      var lastPos = mousePos;
      canvas.addEventListener("mousedown", function (e) {
              drawing = true;
        lastPos = getMousePos(canvas, e);
      }, false);
      canvas.addEventListener("mouseup", function (e) {
        drawing = false;
      }, false);
      canvas.addEventListener("mousemove", function (e) {
        mousePos = getMousePos(canvas, e);
      }, false);

      // Get the position of the mouse relative to the canvas
      function getMousePos(canvasDom, mouseEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
          x: mouseEvent.clientX - rect.left,
          y: mouseEvent.clientY - rect.top
        };
      }
      window.requestAnimFrame = (function (callback) {
              return window.requestAnimationFrame ||
                 window.webkitRequestAnimationFrame ||
                 window.mozRequestAnimationFrame ||
                 window.oRequestAnimationFrame ||
                 window.msRequestAnimaitonFrame ||
                 function (callback) {
              window.setTimeout(callback, 1000/60);
                 };
      })();
      function renderCanvas() {
        if (drawing) {
          ctx.moveTo(lastPos.x, lastPos.y);
          ctx.lineTo(mousePos.x, mousePos.y);
          ctx.stroke();
          lastPos = mousePos;
        }
      }

      // Allow for animation
      (function drawLoop () {
        requestAnimFrame(drawLoop);
        renderCanvas();
      })();
      // Set up touch events for mobile, etc
      canvas.addEventListener("touchstart", function (e) {
        document.getElementById("TextareaResolution").blur();
        document.getElementById("Signature_Name").blur();
        e.preventDefault();
              mousePos = getTouchPos(canvas, e);
        var touch = e.touches[0];
        var mouseEvent = new MouseEvent("mousedown", {
          clientX: touch.clientX,
          clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
      }, false);
      canvas.addEventListener("touchend", function (e) {
        var mouseEvent = new MouseEvent("mouseup", {});
        canvas.dispatchEvent(mouseEvent);
      }, false);
      canvas.addEventListener("touchmove", function (e) {
        var touch = e.touches[0];
        var mouseEvent = new MouseEvent("mousemove", {
          clientX: touch.clientX,
          clientY: touch.clientY
        });
        canvas.dispatchEvent(mouseEvent);
      }, false);

      // Get the position of a touch relative to the canvas
      function getTouchPos(canvasDom, touchEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
          x: touchEvent.touches[0].clientX - rect.left,
          y: touchEvent.touches[0].clientY - rect.top
        };
      }
      // Prevent scrolling when touching the canvas
      document.body.addEventListener("touchstart", function (e) {
        if (e.target == canvas) {
          //e.preventDefault();
        }
      }, false);
      document.body.addEventListener("touchend", function (e) {
        if (e.target == canvas) {
          //e.preventDefault();
        }
      }, false);
      document.body.addEventListener("touchmove", function (e) {
        if (e.target == canvas) {
          //e.preventDefault();
        }
      }, false);
      </script>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
    <div class='row timesheet'>
      <div class='col-xs-12'><button onClick='post_time_allocation(this);' style='width:100%;height:50px;'>Save</div>
    </div>
    <div class='row'>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'>&nbsp;</div>
      <div class='col-xs-12'>&nbsp;</div>
    </div>
		<?php }} else {
      if((isset($Ticket['TimeRoute']) && $Ticket['TimeRoute'] == '1899-12-30 00:00:00.000') || !isset($Ticket['TimeRoute']) || $Ticket['TimeRoute'] == NULL){?>
        <?php
        $r = sqlsrv_query($NEI,"SELECT * FROM Portal.dbo.Attendance WHERE Attendance.[User] = ? AND Attendance.[Start] >= ? AND Attendance.[Start] < ? AND Attendance.[End] IS NULL ORDER BY Attendance.[Start] DESC;",array($_SESSION['User'], date("Y-m-d 00:00:00.000"),date("Y-m-d H:i:s")));
        if(!$r || !is_array(sqlsrv_fetch_array($r))){$disabled = true;}
        else {$disabled = false;}
        $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.TicketO LEFT JOIN nei.dbo.Emp ON Emp.fWork = TicketO.fWork WHERE TicketO.Assigned >= 2 AND TicketO.Assigned <= 3 AND Emp.ID = ?;",array($_SESSION['User']));
        if($r && is_array(sqlsrv_fetch_array($r))){$disabled2 = true;}
        else{$disabled2 = false;}
        ?>
      <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Time</h4></div>
      <div class='panel-body' style='padding-top:10px;'>
        <div class='row'>
    			<div class='col-xs-12'><button style='width:100%;height:65px;'
            <?php if($disabled){
              echo 'onClick="alert(\'You must be clocked in to accept work\');"';
            } elseif($disabled2){
              echo 'onClick="alert(\'You cannot be working on two tickets at once.\');"';
            } else {
              echo "onclick='post_time_en_route(this);'";
            }?> id='en_route' >Accept Work</button></div>
        </div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
        <div class='row'>
    			<div class='col-xs-12'><button style='width:100%;height:65px;'
            <?php if($disabled){
              echo 'onClick="alert(\'You must be clocked in to be at work\');"';
            } elseif($disabled2){
              echo 'onClick="alert(\'You cannot be working on two tickets at once.\');"';
            } else {
              echo "onclick='post_time_on_site(this);'";
            }?> id='on_site' >At Work</button></div>
        </div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
      </div>
      <?php } else {?>
      <div class='panel-heading' style='padding:1px;padding-left:10px !important;margin:0px;margin-bottom:5px;'><h4><?php $Icons->Timesheet(1);?> Time</h4></div>
      <div class='panel-body' style='padding-top:10px;'>
        <div class='row'>
          <div class='col-xs-12'><button style='width:100%;height:35px;' id='reset_time' onclick='reset_time(this);'>Reset Ticket</button></div>
          <div class='col-xs-12'>&nbsp;</div>
        </div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Delivery(1);?> En Route:</div>
    			<div class='col-xs-8'><?php echo date("h:i A",strtotime($Ticket['TimeRoute']));?></div>
        </div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
        <div class='row'>
          <div class='col-xs-4'><?php $Icons->Timesheet(1);?> On Site:</div>
    			<div class='col-xs-8'><button style='width:100%;height:50px;' id='on_site' onclick='post_time_on_site(this);'>At Work</button></div>
        </div>
        <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
      </div>
      <?php }?>
    <?php }?>
</div>
<?php require("../../../../cgi-bin/js/pages/ticket12.php");?>
<style>
input.slider {text-align:center;}
div.email-person {display:none;}
div.email-person.active {display:block !important;}
</style>
<?php
		}
    }
} else {?><html><head><script>document.location.href="../login.php?Forward=ticket<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }?>
