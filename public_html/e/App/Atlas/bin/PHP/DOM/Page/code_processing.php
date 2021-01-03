<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'code_processing';
    unset($_SESSION['page-id']);
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
    else {
?><div class="panel panel-primary">
  <div class="panel-heading"><h4><div style='float:left;' onClick="document.location.href='home.php';"><?php $Icons->Customer();?> Batch Process Deficiencies</div><div style='clear:both;'></div></h4></div>
  <div class="panel-body">
    <script>
    function Process_Defiencies(link){
      $(link).html("Saving <img src='media/images/spinner.gif' height='25px' width='auto' />");
      $(link).attr('disabled','disabled');
      var ar = new Array();
      $("input:checked").each(function(){
        ar.push($(this).attr('rel'));
      });
      var Deficiencies = ar.join(',');
      $.ajax({
        url:'cgi-bin/php/post/deficiency_process.php?Deficiencies=' + Deficiencies,
        success:function(code){
          //alert(code);
          alert("You're deficiencies have been processed.");
          document.location.href='category_tests2.php';
        }
      })

    }

    </script>
    <table id='Table_Category_Tests' class='display' cellspacing='0' width='100%' style='font-size:12px;margin:10px;'>
      <thead>
        <th>Category Test</th>
        <th>Location</th>
        <th>Unit</th>
        <th>Part</th>
        <th>Condition</th>
        <th>Remedy</th>
        <th>Comments</th>
        <th>Action</th>
        <th>Notes</th>
        <th>User</th>
        <th>Process</th>
      </thead>
      <tbody style='color:white;'><?php
        $DIV_SQL = "";
        switch($_SESSION['User']){
          case 1272:$DIV_SQL = "AND (Zone.Name = 'DIVISION #2' OR Zone.Name = 'DIVISION #4' OR Zone.Name = 'DIVISION #3' OR Zone.Name = 'DIVISION #1')";break;
          case 1136:$DIV_SQL = "AND (Zone.Name = 'DIVISION #2' OR Zone.Name = 'DIVISION #4')";break;
          case 1373:$DIV_SQL = "AND Zone.Name = 'DIVISION #1'";break;
          case 446:$DIV_SQL = "AND (Zone.Name = 'DIVISION #2' OR Zone.Name = 'DIVISION #4' OR Zone.Name = 'DIVISION #3' OR Zone.Name = 'DIVISION #1') ";break;
          case 1477:$DIV_SQL = "AND Zone.Name = 'DIVISION #4'";break;
          case 1492:$DIV_SQL = "AND Zone.Name = 'DIVISION #1'";break;
          case 895:break;
        }
        $r = sqlsrv_query($Portal,
          " SELECT  Deficiency.*,
                    Loc.Tag AS Location_Name,
                    Elev.State + ' - ' + Elev.Unit AS Unit_Name,
                    Emp.fFirst + ' ' + Emp.Last AS User_Full_Name,
                    Category_Elevator_Part.External_ID AS Part_External_ID,
                    Category_Elevator_Part.Name AS Part_Name,
                    Category_Violation_Condition.External_ID AS Condition_External_ID,
                    Category_Violation_Condition.Name AS Condition_Name,
                    Category_Remedy.External_ID AS Remedy_External_ID,
                    Category_Remedy.Name AS Remedy_Name
            FROM    Portal.dbo.Deficiency
                    LEFT JOIN Portal.dbo.Category_Test ON Deficiency.Category_Test = Category_Test.ID
                    LEFT JOIN nei.dbo.Loc ON Category_Test.Location = Loc.Loc
                    LEFT JOIN nei.dbo.Elev ON Category_Test.Unit = Elev.ID
                    LEFT JOIN nei.dbo.Emp ON Deficiency.Action_User = Emp.ID
                    LEFT JOIN Portal.dbo.Category_Elevator_Part ON Deficiency.Elevator_Part = Category_Elevator_Part.ID
                    LEFT JOIN Portal.dbo.Category_Violation_Condition ON Deficiency.Condition = Category_Violation_Condition.ID
                    LEFT JOIN Portal.dbo.Category_Remedy ON Deficiency.Remedy = Category_Remedy.ID
                    LEFT JOIN nei.dbo.Zone ON Loc.Zone = Zone.ID
            WHERE   Deficiency.Action IS NOT NULL
                    AND (Deficiency.Processed <> 1 OR Deficiency.Processed IS NULL OR Deficiency.Processed = 0)
                    AND [Category_Test].[Status] = 'Complete'
                    {$DIV_SQL}
            ORDER BY Category_Test.ID ASC
          ;", array());
        if($r){
          while($row = sqlsrv_fetch_array($r)){?><tr><?php
            ?><td><?php echo $row['Category_Test'];?></td><?php
            ?><td><?php echo $row['Location_Name'];?></td><?php
            ?><td><?php echo $row['Unit_Name'];?></td><?php
            ?><td><?php echo "({$row['Part_External_ID']}) {$row['Part_Name']}";?></td><?php
            ?><td><?php echo "({$row['Condition_External_ID']}) {$row['Condition_Name']}";?></td><?php
            ?><td><?php echo "({$row['Remedy_External_ID']}) {$row['Remedy_Name']}";?></td><?php
            ?><td><?php echo "{$row['Comments']}";?></td><?php
            ?><td><?php echo $row['Action'];?></td><?php
            ?><td><?php echo "{$row['Notes']}";?></td><?php
            ?><td><?php echo $row['User_Full_Name'];?></td><?php
            ?><td><input rel='<?php echo $row['ID'];?>' type='checkbox' name='Process[<?php echo $row['ID'];?>]' value='1' style='height:25px;width:25px;' /></td><?php
          ?></tr><?php }
        }
      ?>
      <tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>
      <tr><td colspan='9'><button onClick='Process_Defiencies(this);' type='button' style='width:100%;height:50px;'>Process Deficiencies</button></td></tr></tbody>
    </table>
  </div>
</div><?php
    }
} else {}?>
