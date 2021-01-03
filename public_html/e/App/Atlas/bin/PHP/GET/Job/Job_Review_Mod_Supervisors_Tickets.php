<?php
session_start();
require('index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    $r = sqlsrv_query($Portal,"
        SELECT User_Privilege, Group_Privilege, Other_Privilege
        FROM   Portal.dbo.Privilege
        WHERE User_ID = ? AND Access_Table='Job'
    ;",array($_SESSION['User']));
    $My_Privileges = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
    if(!isset($array['ID']) || !is_array($My_Privileges)){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
        if($My_Privileges['User_Privilege'] >= 4 && $My_Privileges['Group_Privilege'] >= 4 && $My_Privileges['Other_Privilege'] >= 4){
          $Start = is_null($_GET['Start']) ? "2019-01-01 00:00:00.000" : date("Y-m-d 00:00:00.000", strtotime($_GET['Start']));
          $End =  is_null($_GET['End']) ? "2022-01-01 00:00:00.000" : date("Y-m-d 00:00:00.000", strtotime($_GET['End']));
          $Supervisor = is_null($_GET['Supervisor']) ? '' : $_GET['Supervisor'];
          $ID = isset($_GET['ID']) && is_numeric($_GET['ID']) ? $_GET['ID'] : NULL;
          $r = sqlsrv_query($NEI,
            " SELECT  Job.ID AS Job_ID,
                      Tickets.OT,
                      Tickets.DT,
                      Tickets.ID AS ID,
                      Tickets.fDesc AS Description,
                      Tickets.DescRes AS Account,
                      Tickets.EDate as Date_Created,
                      Owner.Name AS Customer,
                      Location.Tag AS Location,
                      'Ticket' AS Class,
                      Employee.fFirst + ' ' + Employee.Last AS Supervisor
              FROM    nei.dbo.TicketD AS Tickets
                      LEFT JOIN nei.dbo.Job AS Job ON Job.ID = Tickets.Job
                      LEFT JOIN nei.dbo.Loc AS Location ON Location.Loc = Tickets.Loc
                      LEFT JOIN nei.dbo.OwnerWithRol AS Owner ON Owner.ID = Job.Owner
                      LEFT JOIN nei.dbo.Emp AS Employee ON Employee.fWork = Tickets.fWork
              WHERE   Tickets.EDate >= ? AND Tickets.EDate < ? AND (Tickets.OT > 0 OR Tickets.DT > 0) AND Tickets.Job = ?
            ;", array($Start, $End, $ID));
          if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
          $data = array();
    			if($r){while($row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){
            $row['Date_Created'] = is_null($row['Date_Created']) ? '' : date('m/d/Y h:i A', strtotime($row['Date_Created']));
            /*$row['Date_Created'] = is_null($row['Date_Created']) ? '' : date('m/d/Y', strtotime($row['Date_Created']));
            $row['Last_Invoice_Date'] = is_null($row['Last_Invoice_Date']) ? '' : date('m/d/Y', strtotime($row['Last_Invoice_Date']));
            $row['Billing_Amount'] = "$" . number_format($row['Billing_Amount'], 2);
            $row['Contract_Amount'] = "$" . number_format($row['Contract_Amount'], 2);
            $row['Labor_Costs'] = "$" . number_format($row['Labor_Costs'], 2);
            $row['Material_Costs'] = "$" . number_format($row['Material_Costs'], 2);
            $row['Total_Costs'] = "$" . number_format($row['Total_Costs'], 2);
            $row['Last_Ticket_Date'] = is_null($row['Last_Ticket_Date']) ? '' : date("m/d/Y", strtotime($row['Last_Ticket_Date']));*/
            //$row['Date'] = date('m/d/Y h:i A', strtotime($row['Date']));
    				$data[] = $row;
    			}}
          foreach($data AS $row){
            /*?><tr class='ticket'>
              <td class='hidden'><?php echo $row['ID'];?></td>
              <td><?php echo $row['Employee'];?></td>
              <td><?php echo $row['ID'];?></td>
              <td><?php echo $row['Date'];?></td>
              <td><?php echo $row['Description'];?></td>
              <td><?php echo $row['Resolution'];?></td>
              <td><?php echo $row['OT'];?></td>
              <td><?php  echo $row['DT'];?></td>
            </tr><?php*/
          }
        }
        print json_encode(array('data'=>$data));
    }
}
