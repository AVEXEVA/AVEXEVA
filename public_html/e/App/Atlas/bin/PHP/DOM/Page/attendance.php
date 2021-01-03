<?php
set_time_limit (60);
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'attendance';
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
	   	|| !isset($My_Privileges['Executive'])
	  		|| $My_Privileges['Executive']['User_Privilege']  < 4
	  		|| $My_Privileges['Executive']['Group_Privilege'] < 4
	  	    || $My_Privileges['Executive']['Other_Privilege'] < 4){
				?><?php require('../404.html');?><?php }
    else {
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "accounts_2019.php"));
?><div class="panel panel-primary">
  <div class='panel-heading'>
    <h4>Attendance</h4>
  </div>
  <div class='panel-body'>
    <div class='row'>
      <form action='#'>
        <div class='col-xs-12'>Supervisor: <select name='Supervisor'><?php
            $r = sqlsrv_query($NEI,"SELECT tblWork.Super FROM nei.dbo.tblWork GROUP BY tblWork.Super ORDER BY tblWork.Super ASC;");
            if($r){while($row = sqlsrv_fetch_array($r)){
              ?><option value='<?php echo $row['Super'];?>' <?php if(isset($_GET['Supervisor']) && $_GET['Supervisor'] == $row['Super']){?>selected<?php }?>><?php echo $row['Super'];?></option><?php
            }}
          ?></select>
          <script>
          function refresh(){
            open_page($("a[page-target='attendance']"), {Supervisor:$("select[name='Supervisor']").val()});
          }
          </script>
          <div class='col-xs-12'><button type='button' onClick="refresh();">Search</button></div>
        </div>
      </form>
    </div>
  </div>
  <div class='panel-body'>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
    <div class='row'>
      <div class='col-xs-4'>&nbsp;</div>
      <div class='col-xs-1' style='background-color:gold;'>Clocked In</div>
      <div class='col-xs-1' style='background-color:green;color:white;'>Worked Day</div>
      <div class='col-xs-1' style='background-color:red;'>PTO</div>
      <div class='col-xs-1' style='background-color:#282828;color:white;'>Weekend</div>
    </div>
    <div class='row'><div class='col-xs-12'>&nbsp;</div></div>
  </div>
  <div class='panel-body'>
    <div style='margin-left:100px;'>
    <table id='attendance' style=''>
      <!--<?php $i = 1;?><colgroup><?php while($i < 32){?><col style='<?php if($i == intval(date("d"))){?>border:5px solid black !important;<?php }?>'></col><?php $i++;}?></colgroup>-->
      <thead><tr>
        <th>Attendance Sheet - <?php echo isset($_GET['Supervisor']) ? $_GET['Supervisor'] : 'All';?></th>
        <?php $i = 1;
        while($i < 32){?><th><?php echo $i;?></th><?php $i++;}?></tr></thead>
        <thead><tr>
          <th>Name</th>
          <?php $i = 1;
          while($i < 32){?><th><?php echo $i > 10 ? date("D",strtotime(date("Y-m-{$i} 00:00:00.000"))) : date("D",strtotime(date("Y-m-0{$i} 00:00:00.000")));?></th><?php $i++;}?></tr></thead>
        <tbody style=''>
          <?php
          if(isset($_GET['Supervisor'])  && strlen($_GET['Supervisor']) > 0) {
            //$_GET['Start'] = date('Y-m-d H:i:s',strtotime($_GET['Start']));
            //$_GET['End'] = date('Y-m-d H:i:s',strtotime($_GET['End']));
            $r = sqlsrv_query($Portal,"
              SELECT Emp.ID AS ID,
                     Emp.fWork AS fWork,
                     Emp.fFirst,
                     Emp.Last
              FROM   nei.dbo.Emp
                     LEFT JOIN nei.dbo.tblWork ON 'A' + convert(varchar(10),Emp.ID) + ',' = tblWork.Members
              WHERE  tblWork.Super = ?
                     AND Emp.Status = 0
              ORDER BY Emp.Last ASC
            ;",array($_GET['Supervisor']));
          } else {
            $_GET['Start'] = date('Y-m-d H:i:s',strtotime($_GET['Start']));
            $_GET['End'] = date('Y-m-d H:i:s',strtotime($_GET['End']));
            $r = sqlsrv_query($Portal,"
              SELECT Top 25 Emp.ID AS ID,
                     Emp.fWork AS fWork,
                     Emp.fFirst,
                     Emp.Last
              FROM   nei.dbo.Emp
              ORDER BY Emp.Last ASC
            ;",array());
          }
          $data = array();
          $sQuery = "SELECT Attendance.[Start], Attendance.[End] FROM Portal.dbo.Attendance WHERE Attendance.[User] = ? ORDER BY Attendance.[ID] DESC;";
          if($r){while($row = sqlsrv_fetch_array($r)){
            $r2 = sqlsrv_query($Portal, $sQuery, array($row['ID']));
            if($r2){
              $row2 = sqlsrv_fetch_array($r2);
              $row2 = is_array($row2) ? $row2 : array('Start'=>'1899-12-30 00:00:00.000', 'End'=>'1899-12-30 00:00:00.000');
            }
            $row['Start'] = $row2['Start'] == '1899-12-30 00:00:00.000' ? '' : date("m/d/Y H:i A",strtotime($row2['Start']));
            $row['End'] = $row2['End'] == '1899-12-30 00:00:00.000' ? '' : date("m/d/Y H:i A",strtotime($row2['End']));
            $data[] = $row;
          }}
          $User_ID = NULL;
          $User_fWork = NULL;
          $Today = NULL;
          $Tomorrow = NULL;
          $prepared_statement = sqlsrv_prepare($NEI, "SELECT Top 1 * FROM Portal.dbo.Attendance WHERE Attendance.[User] = ? AND Attendance.[Start] >= ? AND Attendance.[Start] < ? AND (Attendance.[End] < ? OR Attendance.[End] IS NULL) ORDER BY Attendance.ID DESC", array(&$User_ID,&$Today, &$Tomorrow, &$Tomorrow, &$Tomorrow));
          $prepared_statement2 = sqlsrv_prepare($NEI, "SELECT Top 1 * FROM nei.dbo.Unavailable WHERE Unavailable.Worker = ? AND Unavailable.fDate >= ? AND Unavailable.fDate < ?", array(&$User_fWork, &$Today, &$Tomorrow));
          foreach($data as $user){?><tr>
            <td style='border:1px solid black;text-align:right;'><a href='user.php?ID=<?php echo $user['ID'];?>'><?php echo $user['Last'] . ", " . $user['fFirst'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td><?php
            $i = 1;
            while($i < 32){
              $today = $i < 10 ? date("Y-m-0{$i} 00:00:00.000") : date("Y-m-{$i} 00:00:00.000");
              if($i + 1 < 10){
                $i2 = $i + 1;
                $tomorrow = date("Y-m-0{$i2} 00:00:00.000");
              } elseif($i == 30){
                $month = date("m") + 1;
                $tomorrow = date("Y-{$month}-01 00:00:00.000");
              } else {
                $i2 = $i + 1;
                $tomorrow = date("Y-m-{$i2} 00:00:00.000");
              }
              $User_ID = $user['ID'];
              $User_fWork = $user['fWork'];
              $Today = $today;
              $Tomorrow = $tomorrow;
              sqlsrv_execute($prepared_statement);
              $r = $prepared_statement;
              //$r = sqlsrv_query($NEI,"SELECT Top 1 * FROM Portal.dbo.Attendance WHERE Attendance.[User] = ? AND Attendance.[Start] >= ? AND Attendance.[Start] < ? AND (Attendance.[End] < ? OR Attendance.[End] IS NULL) ORDER BY Attendance.ID DESC;",array($user['ID'],$today, $tomorrow, $tomorrow, $tomorrow));
              if($r){$row = sqlsrv_fetch_array($r);}
              if(is_array($row)){
                if(isset($row['End']) && !is_null($row['End']) && date("N",strtotime($today)) >= 6){
                  $color = 'darkgreen';
                } elseif(isset($row['End']) && !is_null($row['End'])){
                  $color = 'green';
                } else {
                  $color = 'yellow';
                }
              } else {
                sqlsrv_execute($prepared_statement2);
                $r = $prepared_statement2;
                if($r){$row = sqlsrv_fetch_array($r);}
                if(is_array($row)){
                  $color = 'red';
                } elseif(intval(date("d")) != $i) {
                  if(date("d") > $i){
                    if(date("N",strtotime($today)) >= 6){
                      $color = '#282828';
                    } else {
                      $color = 'gray';
                    }
                  } else {
                    if(date("N",strtotime($today)) >= 6){
                      $color = '#282828';
                    } else {
                      $color = 'lightgray';
                    }
                  }
                } else {
                  $color = 'white';
                }
              }
              ?><td onClick="<?php if($color != 'green' && $color !='yellow' && $color != 'darkgreen' && $today >= date("Y-m-d 00:00:00.000",strtotime('yesterday'))){?>schedule_pto('<?php echo $today;?>','<?php echo $user['fWork'];?>','<?php echo $user['fFirst'];?>','<?php echo $user['Last'];?>');<?php }?>" style="border:1px solid black;background-color:<?php echo $color;?>;width:35px !important;<?php echo isset($border) ? $border : '';?>">&nbsp;</td><?php
              $i++;
            }
          }
          ?></tr>
        </tbody>
    </table>
    </div>
    <style>
    .popup input, .popup select {
      color:black !important;
    }
    </style>
    <script>
    $("div#container").on('click',function(e){
      if($(e.target).closest('.popup').length === 0 && $(e.target).closest('td').length === 0){
        $('.popup').fadeOut(300);
        $('.popup').remove();
      }
    });
      function schedule_pto(fDate, fWork, fFirst, Last){
        var pto = "<div class='popup' style=''><form action='#' method='POST'><div class='panel panel-primary'><div class='panel-heading'>Schedule Paid Time Off</div><div class='panel-body' style='padding:10px;'><div class='row'><div class='col-xs-4'>First Name:</div><div class='col-xs-8'>" + fFirst + "</div><div class='col-xs-4'>Last Name:</div><div class='col-xs-8'>" + Last + "</div><input type='hidden' value='" + fWork + "' name='fWork' /><input type='hidden' value='" + fDate + "' name='fDate' /><div class='col-xs-4'>All Day</div><div class='col-xs-8'><select name='AllDay'><option value='Yes'>Yes</option><option value='No'>No</option></select></div><div class='col-xs-4'>Start Time:</div><div class='col-xs-8'><input type='text' name='StartTime' /></div><div class='col-xs-4'>End Time</div><div class='col-xs-8'><input type='text' name='EndTime' /></div><div class='col-xs-4'>Description</div><div class='col-xs-8'><input type='hidden' name='Remarks' value='OUT' /><select name='fDesc'><option value='Sick'>Sick</option><option value='Vacation'>Vacation</option><option value='No Pay'>No Pay</option><option value='Personal Day'>Personal Day</option><option value='En Lieu'>En Lieu</option><option value='Medical Day'>Medical Day</option><option value='Other'>Other</option></select></div><div class='col-xs-4'>&nbsp;</div><div class='col-xs-8'><input type='submit' value='Submit' /></div></div></div></div></form></div>";
        $("body").append(pto);
        $("input[name='StartTime']").timepicker();
        $("input[name='EndTime']").timepicker();

      }
    </script>
    <style>
      .popup {
        position:absolute;
        z-index:99;
        left:20%;
        right:20%;
        top:20%;
        bottom:20%;
        height:60%;
        width:60%;
        background-color:white;
        padding:0px;
      }
    </style>
  </div>
</div>
<link href="cgi-bin/libraries/fixedHeader.css" rel="stylesheet" type="text/css" media="screen">
<script src="cgi-bin/libraries/fixedHeader.js"></script>
<script>
  $(document).ready(function(){
    $('#attendance').fixedHeaderTable({height: '650', width:'1500' });
    $(".fht-table-wrapper").css("height","100%");
  });
</script>
<style>
.popup input, .popup select {
  color:black !important;
}
</style>
<script>
$("div#container").on('click',function(e){
  if($(e.target).closest('.popup').length === 0 && $(e.target).closest('td').length === 0){
    $('.popup').fadeOut(300);
    $('.popup').remove();
  }
});
  function schedule_pto(fDate, fWork, fFirst, Last){
    var pto = "<div class='popup' style=''><form action='#' method='POST'><div class='panel panel-primary'><div class='panel-heading'>Schedule Paid Time Off</div><div class='panel-body' style='padding:10px;'><div class='row'><div class='col-xs-4'>First Name:</div><div class='col-xs-8'>" + fFirst + "</div><div class='col-xs-4'>Last Name:</div><div class='col-xs-8'>" + Last + "</div><input type='hidden' value='" + fWork + "' name='fWork' /><input type='hidden' value='" + fDate + "' name='fDate' /><div class='col-xs-4'>All Day</div><div class='col-xs-8'><select name='AllDay'><option value='Yes'>Yes</option><option value='No'>No</option></select></div><div class='col-xs-4'>Start Time:</div><div class='col-xs-8'><input type='text' name='StartTime' /></div><div class='col-xs-4'>End Time</div><div class='col-xs-8'><input type='text' name='EndTime' /></div><div class='col-xs-4'>Description</div><div class='col-xs-8'><input type='hidden' name='Remarks' value='OUT' /><select name='fDesc'><option value='Sick'>Sick</option><option value='Vacation'>Vacation</option><option value='No Pay'>No Pay</option><option value='Personal Day'>Personal Day</option><option value='En Lieu'>En Lieu</option><option value='Medical Day'>Medical Day</option><option value='Other'>Other</option></select></div><div class='col-xs-4'>&nbsp;</div><div class='col-xs-8'><input type='submit' value='Submit' /></div></div></div></div></form></div>";
    $("body").append(pto);
    $("input[name='StartTime']").timepicker();
    $("input[name='EndTime']").timepicker();

  }
</script>
<style>
  .popup {
    position:absolute;
    z-index:99;
    left:20%;
    right:20%;
    top:20%;
    bottom:20%;
    height:60%;
    width:60%;
    background-color:white;
    padding:0px;
  }
</style>
</body>
</html>
<?php
    }
} else {?><html><head><script>document.location.href='../login.php?Forward=accounts_2019.php';</script></head></html><?php }?>
