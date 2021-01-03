<?php
session_start();
require('../../index.php');
setlocale(LC_MONETARY, 'en_US');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($Databases['NEI'],"
		SELECT *
		FROM   nei.dbo.Connection
		WHERE  Connection.Connector = ?
			   AND Connection.Hash = ?
	;", array($_SESSION['User'],$_SESSION['Hash']));
  $Connection = sqlsrv_fetch_array($r);
	$My_User    = sqlsrv_query($Databases['NEI'],"
		SELECT Emp.*,
			   Emp.fFirst AS First_Name,
			   Emp.Last   AS Last_Name
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;", array($_SESSION['User']));
	$My_User = sqlsrv_fetch_array($My_User);
	$My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
	$r = sqlsrv_query($Databases[ 'Portal' ],"
		SELECT Privilege.Access_Table,
			   Privilege.User_Privilege,
			   Privilege.Group_Privilege,
			   Privilege.Other_Privilege
		FROM   Portal.dbo.Privilege
		WHERE  Privilege.User_ID = ?
	;",array($_SESSION['User']));
	$My_Privileges = array();
	while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
	$Privileged = False;
	if( isset($My_Privileges['Location'], $My_Privileges['Unit'])
	   	&& $My_Privileges['Unit']['Other_Privilege'] >= 4
	  	&& $My_Privileges['Location']['Other_Privilege'] >= 4){
			$Privileged = True;}
	elseif(isset($My_Privileges['Location'], $My_Privileges['Unit'])
		&& $My_Privileges['Unit']['Group_Privilege'] >= 4
		&& $My_Privileges['Location']['Group_Privilege'] >= 4
		&& is_numeric($_GET['ID'])){
			$Location_ID = $_GET['ID'];
			$r = sqlsrv_query($Databases['NEI'],"
				SELECT Tickets.ID
				FROM
				(
					(
						SELECT TicketO.ID
						FROM   nei.dbo.TicketO
						WHERE  TicketO.LID       = ?
						       AND TicketO.fWork = ?
					)
					UNION ALL
					(
						SELECT TicketD.ID
						FROM   nei.dbo.TicketD
						WHERE  TicketD.Loc       = ?
						       AND TicketD.fWork = ?
					)
					UNION ALL
					(
						SELECT TicketDArchive.ID
						FROM   nei.dbo.TicketDArchive
						WHERE  TicketDArchive.Loc       = ?
						       AND TicketDArchive.fWork = ?
					)
				) AS Tickets
			;", array($Location_ID, $My_User['fWork'],$Location_ID, $My_User['fWork'],$Location_ID, $My_User['fWork']));
			$Privileged = is_array(sqlsrv_fetch_array($r)) ? True : False;}
	elseif(isset($My_Privileges['Location'], $My_Privileges['Unit'])
		&& $My_Privileges['Unit']['User_Privilege'] >= 4
		&& $My_Privileges['Location']['User_Privilege'] >= 4
		&& is_numeric($_GET['ID'])){
			$Location_ID = $_GET['ID'];
			$r = sqlsrv_query($Databases['NEI'],"
				SELECT Tickets.ID
				FROM
				(
					(
						SELECT TicketO.ID
						FROM   nei.dbo.TicketO
						WHERE  TicketO.LID       = ?
						       AND TicketO.fWork = ?
					)
					UNION ALL
					(
						SELECT TicketD.ID
						FROM   nei.dbo.TicketD
						WHERE  TicketD.Loc       = ?
						       AND TicketD.fWork = ?
					)
					UNION ALL
					(
						SELECT TicketDArchive.ID
						FROM   nei.dbo.TicketDArchive
						WHERE  TicketDArchive.Loc       = ?
						       AND TicketDArchive.fWork = ?
					)
				) AS Tickets
			;", array($_GET['ID'], $My_User['fWork'],$_GET['ID'], $My_User['fWork'],$_GET['ID'], $My_User['fWork']));
			$Privileged = is_array(sqlsrv_fetch_array($r)) ? True : False;}
  if(!isset($Connection['ID'])  || !is_numeric($_GET['ID']) || !$Privileged){
    ?><html><head><script>document.location.href="../login.php?Forward=job<?php echo (!isset($_GET['ID']) || !is_numeric($_GET['ID'])) ? "s.php" : ".php?ID={$_GET['ID']}";?>";</script></head></html><?php }
  else {
		$data = array();
		$r = sqlsrv_query($Databases['NEI'],"
			SELECT Elev.ID     AS ID,
				   Elev.State  AS State,
				   Elev.Unit   AS Unit,
				   Elev.Type   AS Type,
				   Loc.Tag     AS Location,
				   Elev.Status AS Status,
				   Elev.fDesc  AS Description,
				   Elev.Building AS Building
			FROM   nei.dbo.Elev
				   LEFT JOIN nei.dbo.Loc ON Loc.Loc = Elev.Loc
			WHERE  Loc.Loc = ?
		;",array($_GET['ID']));
		$data = array();
		if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
			$Unit = $array;
			$r2 = sqlsrv_query($Databases['NEI'],"
				SELECT *
				FROM   nei.dbo.ElevTItem
				WHERE  ElevTItem.ElevT    = 1
					   AND ElevTItem.Elev = ?
			;",array($Unit['ID']));
			if($r2){while($array2 = sqlsrv_fetch_array($r2,SQLSRV_FETCH_ASSOC)){$Unit[$array2['fDesc']] = $array2['Value'];}}
			$r3 = sqlsrv_query($Databases['NEI'],"
				SELECT *
				FROM   nei.dbo.ElevTItem
				WHERE  ElevTItem.ElevT    = 1
					   AND ElevTItem.Elev = ?
			;",array(0));
			if($r3){while($array3 = sqlsrv_fetch_array($r3,SQLSRV_FETCH_ASSOC)){if(!isset($Unit[$array3['fDesc']])){$Unit[$array3['fDesc']] = '';}}}
			$data[] = $Unit;
		}}
		print json_encode(array('data'=>$data));
  }
} elseif(isset($_SESSION['Customer'],$_SESSION['Hash'])){
  $r = sqlsrv_query($Databases['NEI'],"
    SELECT *
    FROM   nei.dbo.Connection
    WHERE  Connection.Connector = ?
         AND Connection.Hash = ?
  ;", array($_SESSION['Customer'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
  if(isset($Connection['ID'])){
    $r = sqlsrv_query($Databases['NEI'],
      " SELECT  Elev.ID     AS ID,
      				  Elev.State  AS State,
      				  Elev.Unit   AS Unit,
      				  Elev.Type   AS Type,
      				  Loc.Tag     AS Location,
      				  Elev.Status AS Status,
      				  Elev.fDesc  AS Description,
      				  Elev.Building AS Building
        FROM    nei.dbo.Elev
                LEFT JOIN nei.dbo.Loc ON Loc.Loc = Elev.Loc
                LEFT JOIN nei.dbo.OwnerWithRol ON Loc.Owner = OwnerWithRol.ID
        WHERE   OwnerWithRol.ID = ?
      ;",array($_SESSION['Customer']));
    $rows = array();
    if($r){while($row = sqlsrv_fetch_array($r)){
      $rows[] = $row;
    }}
    print json_encode(array("data"=>$rows));
  }
}?>
