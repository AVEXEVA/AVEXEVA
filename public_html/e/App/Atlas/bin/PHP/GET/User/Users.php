<?php
require('../../index.php');
$Session = new Session();
if($Session->__validate() && $Session->access( 'User' )){
  $r = sqlsrv_query( $Session->__get( 'Database' ),
    " SELECT Emp.ID            AS ID,
             Emp.fFirst        AS First_Name,
             Emp.Last          AS Last_Name,
             tblWork.Super     AS Supervisor,
	           Portal_User.Email AS Email,
	           ''                AS Phone,
	           Emp.Custom3       AS Roles
      FROM   nei.dbo.Emp
             LEFT JOIN nei.dbo.tblWork   ON 'A' + convert(varchar(10),Emp.ID) + ',' = tblWork.Members
	           LEFT JOIN (
	   		           SELECT   Portal.Branch_ID AS Branch_ID,
			                      Portal.Email     AS Email
			             FROM     Portal.dbo.Portal
			             WHERE    Portal.Branch = 'Nouveau Elevator'
			             GROUP BY Portal.Branch_ID, Portal.Email
	           ) AS Portal_User      ON Emp.ID = Portal_User.Branch_ID
	           LEFT JOIN nei.dbo.Rol ON Emp.Rol = Rol.ID
      WHERE  Emp.Status = 0
  ;");
  $data = array();
  if($r){while($array = sqlsrv_fetch_array($r,SQLSRV_FETCH_ASSOC)){$data[] = $array;}}
  print json_encode(array('data'=>$data));
}?>
