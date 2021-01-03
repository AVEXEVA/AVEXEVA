<?php
if(session_id() == '' || !isset($_SESSION)) {
    session_start();
    $_SESSION['page-target'] = 'units';
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
		sqlsrv_query($NEI,"
			INSERT INTO Portal.dbo.Activity([User], [Date], [Page])
			VALUES(?,?,?)
		;",array($_SESSION['User'],date("Y-m-d H:i:s"), "locations.php"));
?><div class="panel panel-primary" style='margin-bottom:0px;'>
  <div class="panel-heading">
    <div style='float:left;'>
      <h3><?php $Icons->Ticket();?> <?php echo $Location['Tag'];  ?> Ticket #<?php echo $Ticket['ID'];?></h3>
    </div>
    <div style='clear:both;'></div>
  </div>
  <div class="panel-body print" style='background-color:rgba(255,255,255,.9) !important;'>
    <div class="row">
      <div class='col-md-6' style=''>
        <div class="panel panel-primary">
          <div class="panel-heading">Basic Information</div>
          <div class='panel-body'>
            <div style='font-size:24px;text-decoration:underline;'><b>
              <?php /*Need to make one big row and multiple cols*/?>
              <div class='row'><div class='col-xs-12'><a href='ticket.php?ID=<?php echo $Ticket['ID'];?>'><?php $Icons->Ticket();?> Ticket #<?php echo $Ticket['ID'];?></a></div></div>
              <div class='row'><div class='col-xs-12'><a href='location.php?ID=<?php echo $Ticket['Location_ID'];?>'><?php $Icons->Location();?> <?php echo $Ticket['Tag'];?></a></div></div>
              <div class='row'><div class='col-xs-12'><a href='job.php?ID=<?php echo $Ticket['Job_ID'];?>'><?php $Icons->Job();?> <?php echo $Ticket['Job_Description'];?></a></div></div>
              <div class='row'><div class='col-xs-12'><?php $Icons->User();?> <?php echo proper($Ticket['First_Name'] . " " . $Ticket['Last_Name']);?></div></div>
            </b></div>
          </div>
        </div>
        <div class='row'>
          <div class='col-md-12' style=''>
            <div class="panel panel-primary">
              <div class="panel-heading">Ticket Information</div>
              <div class='panel-body'>
                <div class='row'>
                  <div class='col-xs-4'><b>Total Hours</b></div>
                  <div class='col-xs-8'><pre><?php
                  if(isset($Ticket['Total']) && strlen($Ticket['Total']) > 0){
                    echo $Ticket['Total'];
                  } else {
                    if($Ticket['Status'] != 'Assigned'){
                      if($Ticket['TimeRoute'] == "1899-12-30 00:00:00.000" || $Ticket['TimeRoute'] == ""){ $Start_Time = intval(substr($Ticket['TimeSite'],11,2)) + (intval(substr($Ticket['TimeSite'],14,2)) / 60); }
                      else { $Start_Time = intval(substr($Ticket['TimeRoute'],11,2)) + (intval(substr($Ticket['TimeRoute'],14,2)) / 60); }
                      if($Ticket['TimeComp'] ==  "" || $Ticket['TimeComp'] == "1899-12-30 00:00:00.000"){$End_Time=intval(substr(date("Y-m-d H:i:s", strtotime('+0 hours')),11,2)) + (intval(substr(date("Y-m-d H:i:s", strtotime('+3 hours')),14,2)) / 60);}
                      else {$End_Time = intval(substr($Ticket['TimeComp'],11,2)) + (intval(substr($Ticket['TimeComp'],14,2)) / 60);}

                      echo $End_Time - $Start_Time;?> hours<?php
                    } else {
                      echo "Unlisted";
                    }
                  }?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Status:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket["Status"]) > 1 ? $Ticket["Status"] : "None";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Description:</b></div>
                  <div class='col-xs-8'><pre style='max-height:300px;overflow:scroll;'><?php echo strlen($Ticket['fDesc']) > 1 ? $Ticket["fDesc"] : "None";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Resolution:</b></div>
                  <div class='col-xs-8'><pre style='max-height:300px;overflow:scroll;'><?php echo strlen($Ticket['DescRes']) > 1 ? $Ticket['DescRes'] : "None";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Internal Comments:</b></div>
                  <div class='col-xs-8'><pre style='max-height:300px;overflow:scroll;'><?php echo strlen($Ticket['Comments']) > 1 ? $Ticket['Comments'] : "None";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Zone Expenses:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket['Zone']) > 0 ? $Ticket['Zone'] : "0.00";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Other Expenses:</b></div>
                  <div class='col-xs-8'><pre>$<?php echo strlen($Ticket['OtherE']) > 1 ? $Ticket['OtherE'] : "0.00";?></pre></div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <div class='col-md-6'>
        <div class='row' >
          <div class='col-md-6' >
            <div class="panel panel-primary">
              <div class="panel-heading">
                <i class="fa fa-map fa-fw"></i> Location Details
              </div>
              <div class="panel-body">
                <div class='row'>
                  <div class='col-xs-4'><b>Customer:</b></div>
                  <div class='col-xs-8'><?php if(!$Field){?><a href="<?php echo (strlen($Ticket['Owner_ID']) > 0) ? 'customer.php?ID=' . $Ticket['Owner_ID'] : '#';?>"><pre><?php echo (strlen($Ticket['Customer']) > 0) ? $Ticket["Customer"] : 'Unlisted';?></pre></a><?php } else {?><pre><?php echo (strlen($Ticket['Customer']) > 0) ? $Ticket["Customer"] : 'Unlisted';?><?php }?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Name:</b></div>
                  <div class='col-xs-8'><a href="<?php echo (strlen($Ticket['Loc']) > 0) ? 'location.php?ID=' . $Ticket['Loc'] : '#';?>"><pre><?php echo (strlen($Ticket['Tag']) > 0) ? $Ticket["Tag"] : 'Unlisted';?></pre></a></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Street:</b></div>
                  <div class='col-xs-8'><pre><?php echo (strlen($Ticket['Address']) > 0) ? proper($Ticket["Address"]) : 'Unlisted';?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>City:</b></div>
                  <div class='col-xs-8'><pre><?php echo (strlen($Ticket['City']) > 0) ? proper($Ticket["City"]) : 'Unlisted';?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>State:</b></div>
                  <div class='col-xs-8'><pre><?php echo (strlen($Ticket['State']) > 0) ? $Ticket["State"] : 'Unlisted';?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Zip:</b></div>
                  <div class='col-xs-8'><pre><?php echo (strlen($Ticket['Zip']) > 0) ? $Ticket["Zip"] : 'Unlisted';?></pre></div>

                </div>
              </div>
            </div>
          </div>
          <div class='col-md-6' >
            <div class="panel panel-primary">
              <div class="panel-heading"><i class="fa fa-map fa-fw"></i> Map</div>
              <div class="panel-body">
                <style>#map {height:100%;}</style>
                                <div id="map" style='height:300px;overflow:visible;width:100%;'></div>
              </div>
            </div>
          </div>
          <div class='col-md-6' style=''>
            <div class="panel panel-primary">
              <div class="panel-heading">Job Information</div>
              <div class='panel-body'>
                  <div class='row'>
                    <div class='col-xs-4'><b>Job:</b></div>
                    <div class='col-xs-8'><a href="<?php echo (strlen($Ticket['Job_ID']) > 0) ? 'job.php?ID=' . $Ticket['Job_ID'] : '#';?>"><pre><?php echo strlen($Ticket['Job_ID']) ? $Ticket['Job_ID'] : "Unlisted";?></pre></a></div>
                  </div>
                  <div class='row'>
                    <div class='col-xs-4'><b>Type:</b></div>
                    <div class='col-xs-8'><pre><?php echo strlen($Ticket["Job_Type"]) ? proper($Ticket['Job_Type']) : "Unlisted";?></pre></div>
                  </div>
                  <div class='row'>
                    <div class='col-xs-4'><b>Division:</b></div>
                    <div class='col-xs-8'><pre><?php echo strlen($Ticket['Division']) > 0 ? proper($Ticket["Division"]) : "Unlisted";?></pre></div>
                  </div>
                  <div class='row'>
                    <div class='col-xs-4'><b>Description:</b></div>
                    <div class='col-xs-8'><pre><?php echo strlen($Ticket['Job_Description']) > 0 ? $Ticket['Job_Description'] : "Unlisted";?></pre></div>
                  </div>
                  <div class='row'>
                    <div class='col-xs-4'><b>Unit:</b></div>
                    <div class='col-xs-8'><a href="<?php echo (strlen($Ticket['Unit_ID']) > 0) ? 'unit.php?ID=' . $Ticket['Unit_ID'] : '#';?>"><pre><?php echo strlen($Ticket["Unit_Label"]) > 0 ? $Ticket['Unit_Label'] : "Unlisted";?></pre></a></div>
                  </div>
                  <div class='row'>
                    <div class='col-xs-4'><b>State:</b></div>
                    <div class='col-xs-8'><a href="<?php echo (strlen($Ticket['Unit_ID']) > 0) ? 'unit.php?ID=' . $Ticket['Unit_ID'] : '#';?>"><pre><?php echo strlen($Ticket["Unit_State"]) > 0 ? $Ticket['Unit_State'] : "Unlisted";?></pre></a></div>
                  </div>
                  <div class='row'>
                    <div class='col-xs-4'><b>Type:</b></div>
                    <div class='col-xs-8'><a href="<?php echo (strlen($Ticket['Unit_ID']) > 0) ? 'unit.php?ID=' . $Ticket['Unit_ID'] : '#';?>"><pre><?php echo strlen($Ticket["Unit_Type"]) > 0 ? proper($Ticket['Unit_Type']) : "Unlisted";?></pre></a></div>
                  </div>
              </div>
            </div>
          </div>
          <div class='col-md-6' style=''>
            <div class="panel panel-primary">
              <div class="panel-heading">Clock Information</div>
              <div class='panel-body'>
                <div class='row'>
                  <div class='col-xs-4'><b>Creation:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket["CDate"]) > 0 ? date("m/d/Y h:i:s a", strtotime($Ticket["CDate"])) : "Unlisted";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Dispatched:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket["DDate"]) > 0 ? date("m/d/Y h:i:s a", strtotime($Ticket['DDate'])) : "Unlisted";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Worked:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket["EDate"]) > 0 ? date("m/d/Y h:i:s a", strtotime($Ticket['EDate'])) : "Unlisted";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>En Route:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket["TimeRoute"]) > 0 && date("h:i:s a",strtotime(substr($Ticket['TimeRoute'],11,8))) != '12:00:00 am' ? date("h:i:s a",strtotime(substr($Ticket['TimeRoute'],11,8))) : "None";?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>On Site:</b></div>
                  <div class='col-xs-8'><pre><?php
                    if(strlen($Ticket['TimeSite']) > 0){
                      if(date("h:i:s a",strtotime($Ticket['TimeSite'])) == '12:00 AM'){?><button onClick='post_on_site();'>Work Accepted</button><?php }
                      else {echo date("h:i:s a",strtotime($Ticket['TimeSite']));}
                    } else {
                      echo 'N/A';
                    }
                    //echo strlen($Ticket["TimeSite"]) > 0 ? date("h:i:s a",strtotime(substr($Ticket['TimeSite'],11,8))) : "Unlisted";
                  ?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-4'><b>Completed:</b></div>
                  <div class='col-xs-8'><pre><?php echo strlen($Ticket["TimeComp"]) > 0 ? date("h:i:s a",strtotime(substr($Ticket['TimeComp'],11,8))) : "Unlisted";?></pre></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class='row'>
      <div class='col-lg-12'>
        <div class='panel panel-primary'>
          <div class='panel-heading'>
            <i class='fa fa-map fa-fw'></i> GPS Details
          </div>
          <div class='panel-body' style='height:200px;overflow-y:scroll;'>
            <?php
              $r = sqlsrv_query($NEI,"
                SELECT TOP 100 TechLocation.*
                FROM TechLocation
                WHERE TicketID = '" . $Ticket['ID'] . "'
                ORDER BY TechLocation.DateTimeRecorded ASC;");
              $GPS_Locations = array();
              while($array = sqlsrv_fetch_array($r)){$GPS_Locations[] = $array;}
              foreach($GPS_Locations as $GPS_Location){?>
                <h4 style='background-color:#9e9e9e !important;;color:blackgin:0px;padding:5px;'><?php echo $GPS_Location['ActionGroup'];?></h4>
                <div class='row'>
                  <div class='col-xs-2'>Timestamp:</div>
                  <div class='col-xs-8'><pre><?php echo $GPS_Location['DateTimeRecorded'];?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-2'>Latitude:</div>
                  <div class='col-xs-8'><pre><?php echo substr($GPS_Location['Latitude'],0,7);?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-2'>Longitude:</div>
                  <div class='col-xs-8'><pre><?php echo substr($GPS_Location['Longitude'],0,8);?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-2'>Accuracy:</div>
                  <div class='col-xs-8'><pre><?php echo $GPS_Location['Accuracy'];?></pre></div>
                </div>
                <div class='row'>
                  <div class='col-xs-2'>Action:</div>
                  <div class='col-xs-8'><pre><?php echo $GPS_Location['Action'];?></pre></div>
                </div>
              <?php }?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
    </div>
    <div class='no-print'>
        <div class='row shadower' style='text-align:center;'>
            <div><b>Nouveau Elevator Industries Inc.</b></div>
            <div>47-55 37th Street</div>
            <div>Tel:(718) 349-4700 | Fax:(718)383-3218</div>
            <div>Email:Operations@NouveauElevator.com</div>
        </div>
        <hr />
        <h3 style='text-align:center;'><b><?php echo $Ticket['Status'];?> Service Ticket #<?php echo $_GET['ID'];?></b></h3>
        <hr />
        <div class='row shadower'>
            <div class='col-xs-2' style='text-align:right;'><b>Customer</b></div>
            <div class='col-xs-2'><?php echo $Ticket['Customer'];?></div>
        </div>
        <div class='row shadwer'>
            <!--<div class='col-xs-2' style='text-align:right;'><b>ID#</b></div>
            <div class='col-xs-2'><?php echo $Ticket['Location_ID'];?></div>-->
            <div class='col-xs-2' style='text-align:right;'><b>Location</b></div>
            <div class='col-xs-2'><?php echo $Ticket['Tag'];?></div>
            <div class='col-xs-2' style='text-align:right;'><b>Job</b></div>
            <div class='col-xs-2'><?php echo $Ticket['Job_Description'];?></div>
        </div>
        <div class='row shadower'>
            <div class='col-xs-2'>&nbsp;</div>
            <div class='col-xs-2'><?php echo $Ticket['Address'];?></div>
            <div class='col-xs-2' style='text-align:right;'><b>Unit ID</b></div>
            <div class='col-xs-2'><?php echo strlen($Ticket['Unit_State'] > 0) ? $Ticket['Unit_State'] : $Ticket['Unit_Label'];?></div>
        </div>
        <div class='row shadower'>
            <div class='col-xs-2'>&nbsp;</div>
            <div class='col-xs-2'><?php echo $Ticket['City'];?>, <?php echo $Ticket['State'];?> <?php echo $Ticket['Zip'];?></div>
        </div>

        <div class='row shadower'>
            <div class='col-xs-2' style='text-align:right;'><b>Customer Signature</b></div>
            <div class='col-xs-2'><img id='Ticket_Signature' width='100%' src='data:image/jpeg;base64,<?php echo base64_encode($Ticket['Signature']);?>' /></div>
  <script>
  $(document).ready(function(){
    //$("img#Ticket_Signature").src = 'data:image/bmp;base64,' + "";
  });
  </script>
        </div>
        <hr />
        <div class='row shadower'>
            <div class='col-xs-2' style='text-align:right;'><b>Serviced</b></div>
            <div class='col-xs-2'><?php echo substr($Ticket['EDate'],0,10);?></div>
            <div class='col-xs-2' style='text-align:right;'><b>Regular</b></div>
            <div class='col-xs-2'><?php echo $Ticket['Reg'] == '' ? '0.00' : $Ticket['Reg'];?> hrs</div>
            <div class='col-xs-2' style='text-align:right;'><b>Worker</b></div>
            <div class='col-xs-2'><?php echo strlen($Ticket['First_Name']) > 0 ? proper($Ticket["First_Name"] . " " . $Ticket['Last_Name']) : "None";;?></div>
        </div>
        <div class='row shadower'>
            <div class='col-xs-2' style='text-align:right;'><b>Dispatched</b></div>
            <div class='col-xs-2'><?php echo substr($Ticket['TimeRoute'],11,99);?></div>
            <div class='col-xs-2' style='text-align:right;'><b>O.T.</b></div>
            <div class='col-xs-2'><?php echo $Ticket['OT'] == '' ? '0.00' : $Ticket['OT']?> hrs</div>
            <div class='col-xs-2' style='text-align:right;'><b>Role</b></div>
            <div class='col-xs-2'><?php echo proper($Ticket['Role']);?></div>
        </div>
        <div class='row shadower'>
            <div class='col-xs-2' style='text-align:right;'><b>On Site</b></div>
            <div class='col-xs-2'><?php echo substr($Ticket['TimeSite'],11,99);?></div>
            <div class='col-xs-2' style='text-align:right;'><b>D.T.</b></div>
            <div class='col-xs-2'><?php echo $Ticket['DT'] == '' ? '0.00' : $Ticket['DT'];?> hrs</div>
        </div>
        <div class='row shaodwer'>
            <div class='col-xs-2' style='text-align:right;'><b>Completed</b></div>
            <div class='col-xs-2'><?php echo substr($Ticket['TimeComp'],11,99);?></div>
            <div class='col-xs-2' style='text-align:right;'><b>Total</b></div>
            <div class='col-xs-2'><?php echo $Ticket['Total'] == '' ? '0.00' : $Ticket['Total'];?> hrs</div>
        </div>
        <hr />
        <div class='row shadower'>
            <div class='col-xs-2' style='text-align:right;'><b>Scope of Work</b></div>
            <div class='col-xs-10'><pre><?php echo $Ticket['fDesc'];?></pre></div>
            <div class='col-xs-2' style='text-align:right;'><b>Resolution of Work</b></div>
            <div class='col-xs-10'><pre><?php echo $Ticket['DescRes'];?></pre></div>
        </div>
    </div><?php
    }
} else {}?>
