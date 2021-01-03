<?php
session_start();
require('../index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"
		SELECT *
		FROM   nei.dbo.Connection
		WHERE  Connection.Connector = ?
			   AND Connection.Hash = ?
	;", array($_SESSION['User'],$_SESSION['Hash']));
    $Connection = sqlsrv_fetch_array($r);
	$My_User    = sqlsrv_query($NEI,"
		SELECT Emp.*,
			   Emp.fFirst AS First_Name,
			   Emp.Last   AS Last_Name
		FROM   nei.dbo.Emp
		WHERE  Emp.ID = ?
	;", array($_SESSION['User']));
	$My_User = sqlsrv_fetch_array($My_User);
	$My_Field = ($My_User['Field'] == 1 && $My_User['Title'] != "OFFICE") ? True : False;
	$r = sqlsrv_query($Portal,"
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
	 if( isset($My_Privileges['Ticket'])
        && (
				  $My_Privileges['Ticket']['User_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Group_Privilege'] >= 4
			||	$My_Privileges['Ticket']['Other_Privilege'] >= 4)){
            $Privileged = True;}
    if(!isset($Connection['ID']) || !$Privileged){print json_encode(array('data'=>array()));}
	else {
    sqlsrv_query($NEI,"INSERT INTO Portal.dbo.Activity([User], [Date], [Page]) VALUES(?,?,?);",array($_SESSION['User'],date("Y-m-d H:i:s"), "paid_time_off.php"));
    $r = sqlsrv_query($NEI,
      " SELECT  Emp.Ref AS Employee_ID,
                Emp.fFirst AS First_Name,
                Emp.Last AS Last_Name,
                Sick_2014.Count AS Sick_2014,
                Vacation_2014.Count AS Vacation_2014,
                Unpaid_2014.Count AS Unpaid_2014,
                Lieu_2014.Count AS Lieu_2014,
                Medical_2014.Count AS Medical_2014,
                Total_2014.Count AS Total_2014,
                Sick_2015.Count AS Sick_2015,
                Vacation_2015.Count AS Vacation_2015,
                Unpaid_2015.Count AS Unpaid_2015,
                Lieu_2015.Count AS Lieu_2015,
                Medical_2015.Count AS Medical_2015,
                Total_2015.Count AS Total_2015,
                Sick_2016.Count AS Sick_2016,
                Vacation_2016.Count AS Vacation_2016,
                Unpaid_2016.Count AS Unpaid_2016,
                Lieu_2016.Count AS Lieu_2016,
                Medical_2016.Count AS Medical_2016,
                Total_2016.Count AS Total_2016,
                Sick_2017.Count AS Sick_2017,
                Vacation_2017.Count AS Vacation_2017,
                Unpaid_2017.Count AS Unpaid_2017,
                Lieu_2017.Count AS Lieu_2017,
                Medical_2017.Count AS Medical_2017,
                Total_2017.Count AS Total_2017
        FROM  nei.dbo.Emp
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2014-01-01 00:00:00.000'
                  AND DateTaken < '2015-01-01 00:00:00.000'
                  AND DayType = 1
          GROUP BY EmpID
        ) AS Sick_2014 ON Sick_2014.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2014-01-01 00:00:00.000'
                  AND DateTaken < '2015-01-01 00:00:00.000'
                  AND DayType = 2
          GROUP BY EmpID
        ) AS Vacation_2014 ON Vacation_2014.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2014-01-01 00:00:00.000'
                  AND DateTaken < '2015-01-01 00:00:00.000'
                  AND DayType = 3
          GROUP BY EmpID
        ) AS Unpaid_2014 ON Unpaid_2014.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2014-01-01 00:00:00.000'
                  AND DateTaken < '2015-01-01 00:00:00.000'
                  AND DayType = 4
          GROUP BY EmpID
        ) AS Lieu_2014 ON Lieu_2014.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2014-01-01 00:00:00.000'
                  AND DateTaken < '2015-01-01 00:00:00.000'
                  AND DayType = 5
          GROUP BY EmpID
        ) AS Medical_2014 ON Medical_2014.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2015-01-01 00:00:00.000'
                  AND DateTaken < '2016-01-01 00:00:00.000'
                  AND DayType = 1
          GROUP BY EmpID
        ) AS Sick_2015 ON Sick_2015.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2015-01-01 00:00:00.000'
                  AND DateTaken < '2016-01-01 00:00:00.000'
                  AND DayType = 2
          GROUP BY EmpID
        ) AS Vacation_2015 ON Vacation_2015.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2015-01-01 00:00:00.000'
                  AND DateTaken < '2016-01-01 00:00:00.000'
                  AND DayType = 3
          GROUP BY EmpID
        ) AS Unpaid_2015 ON Unpaid_2015.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2015-01-01 00:00:00.000'
                  AND DateTaken < '2016-01-01 00:00:00.000'
                  AND DayType = 4
          GROUP BY EmpID
        ) AS Lieu_2015 ON Lieu_2015.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2015-01-01 00:00:00.000'
                  AND DateTaken < '2016-01-01 00:00:00.000'
                  AND DayType = 5
          GROUP BY EmpID
        ) AS Medical_2015 ON Medical_2015.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2016-01-01 00:00:00.000'
                  AND DateTaken < '2017-01-01 00:00:00.000'
                  AND DayType = 1
          GROUP BY EmpID
        ) AS Sick_2016 ON Sick_2016.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2016-01-01 00:00:00.000'
                  AND DateTaken < '2017-01-01 00:00:00.000'
                  AND DayType = 2
          GROUP BY EmpID
        ) AS Vacation_2016 ON Vacation_2016.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2016-01-01 00:00:00.000'
                  AND DateTaken < '2017-01-01 00:00:00.000'
                  AND DayType = 3
          GROUP BY EmpID
        ) AS Unpaid_2016 ON Unpaid_2016.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2016-01-01 00:00:00.000'
                  AND DateTaken < '2017-01-01 00:00:00.000'
                  AND DayType = 4
          GROUP BY EmpID
        ) AS Lieu_2016 ON Lieu_2016.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2016-01-01 00:00:00.000'
                  AND DateTaken < '2017-01-01 00:00:00.000'
                  AND DayType = 5
          GROUP BY EmpID
        ) AS Medical_2016 ON Medical_2016.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2017-01-01 00:00:00.000'
                  AND DateTaken < '2018-01-01 00:00:00.000'
                  AND DayType = 1
          GROUP BY EmpID
        ) AS Sick_2017 ON Sick_2017.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2017-01-01 00:00:00.000'
                  AND DateTaken < '2018-01-01 00:00:00.000'
                  AND DayType = 2
          GROUP BY EmpID
        ) AS Vacation_2017 ON Vacation_2017.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2017-01-01 00:00:00.000'
                  AND DateTaken < '2018-01-01 00:00:00.000'
                  AND DayType = 3
          GROUP BY EmpID
        ) AS Unpaid_2017 ON Unpaid_2017.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2017-01-01 00:00:00.000'
                  AND DateTaken < '2018-01-01 00:00:00.000'
                  AND DayType = 4
          GROUP BY EmpID
        ) AS Lieu_2017 ON Lieu_2017.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2017-01-01 00:00:00.000'
                  AND DateTaken < '2018-01-01 00:00:00.000'
                  AND DayType = 5
          GROUP BY EmpID
        ) AS Medical_2017 ON Medical_2017.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2014-01-01 00:00:00.000'
                  AND DateTaken < '2015-01-01 00:00:00.000'
                  AND DayType <> 3
          GROUP BY EmpID
        ) AS Total_2014 ON Total_2014.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2015-01-01 00:00:00.000'
                  AND DateTaken < '2016-01-01 00:00:00.000'
                  AND DayType <> 3
          GROUP BY EmpID
        ) AS Total_2015 ON Total_2015.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2016-01-01 00:00:00.000'
                  AND DateTaken < '2017-01-01 00:00:00.000'
                  AND DayType <> 3
          GROUP BY EmpID
        ) AS Total_2016 ON Total_2016.EmpID = CAST(Emp.Ref AS int)
        LEFT JOIN (
          SELECT  EmpID,
                  Count(HoursTaken) AS Count
          FROM    Attendance.dbo.DaysTakenOff
          WHERE   DateTaken >= '2017-01-01 00:00:00.000'
                  AND DateTaken < '2018-01-01 00:00:00.000'
                  AND DayType <> 3
          GROUP BY EmpID
        ) AS Total_2017 ON Total_2017.EmpID = CAST(Emp.Ref AS int)
      ;",array(),array("Scrollable"=>SQLSRV_CURSOR_KEYSET));
    $data = array();
    $row_count = sqlsrv_num_rows( $r );
    if($r){
      while($i < $row_count){
        $row = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC);
        if(is_array($row) && $row != array()){
          $data[] = $row;
        }
        $i++;
      }
    }
    print json_encode(array('data'=>$data));
  }
}?>
