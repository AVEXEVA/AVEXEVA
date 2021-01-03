<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require('../../../cgi-bin/libraries/PHPMailer-master/src/Exception.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/PHPMailer.php');
require('../../../cgi-bin/libraries/PHPMailer-master/src/SMTP.php');
function uploadFile($access_token, $file, $mime_type, $name, $folder) {
    //global $GAPIS;
    $GAPIS = 'https://www.googleapis.com/';

    $ch1 = curl_init();

    // don't do ssl checks
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);

    $my_beautiful_body = '
--joes_awesome_divider
Content-Type: application/json; charset=UTF-8

{
"name": "'.$name.'",
"parents": ["'.$folder.'"]
}

--joes_awesome_divider
Content-Type: '.$mime_type.'

'.file_get_contents($file).'

--joes_awesome_divider--
';

    // do other curl stuff
    curl_setopt($ch1, CURLOPT_URL, $GAPIS . 'upload/drive/v3/files?uploadType=multipart');
    curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS, $my_beautiful_body);
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);

    // set authorization header
    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: multipart/related; boundary=joes_awesome_divider', 'Authorization: Bearer ' . $access_token) );

    // execute cURL request
    $response=curl_exec($ch1);
    if($response === false){
        $output = 'ERROR: '.curl_error($ch1);
    } else{
        $output = $response;
    }

    // close first request handler
    curl_close($ch1);

    return $output;
}
session_start();
require('../index.php');
//require('../Google/autoload.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_SESSION['User']));
  $My_User = sqlsrv_fetch_array($r);
  $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
  $r = sqlsrv_query($Portal,"
      SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
      FROM   Portal.dbo.Privilege
      WHERE  User_ID = ?
  ;",array($_SESSION['User']));
  $My_Privileges = array();
  while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
  $Privileged = FALSE;
  if(isset($My_Privileges['Code']) && $My_Privileges['Code']['Other_Privilege'] >= 4){$Privileged = TRUE;}
  if(!$Privileged || count($_GET) == 0){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {
    $GAPIS = 'https://www.googleapis.com/';
    $GAPIS_AUTH = $GAPIS . 'auth/';
    $GOAUTH = 'https://accounts.google.com/o/oauth2/';
    $REDIRECT_URI = 'http' . ($_SERVER['SERVER_PORT'] == 80 ? '' : 's') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

    // specific to this app
    $CLIENT_ID = '537233097283-brun3p868lmrgj5b2sjprhqoe596h22o.apps.googleusercontent.com';   // expecting *.apps.googleusercontent.com
    $CLIENT_SECRET = 'xCGfxptiECCz8zAoxh6gWhUy';   // expecting alphanumeric string
    $STORE_PATH = '../../../../../token_' . $_SESSION['User'] . '.json';   // expecting *.json - needs to be writeable by system account
    $SCOPES = array($GAPIS_AUTH . 'drive');  // add in your other scopes as needed
    function generateMessageID()
    {
      return sprintf(
        "<%s.%s@%s>",
        base_convert(microtime(), 10, 36),
        base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
        $_SERVER['SERVER_NAME']
      );
    }
    // retrieve from credentials file
    function getStoredCredentials($path) {
        $credentials = json_decode(file_get_contents($path), true);
        $expire_date = new DateTime();
        $current_time = new DateTime();
        $expire_date->setTimestamp($credentials['created']-300);
        $expire_date->add(new DateInterval('PT' . $credentials['expires_in'] . 'S'));
        if ($current_time->getTimestamp() >= $expire_date->getTimestamp()) {
            $credentials = null;
            unlink($path);
        }
        return $credentials;
    }

    // store new credentials in file
    function storeCredentials($path, $credentials) {
        $credentials['created'] = (new DateTime())->getTimestamp(); file_put_contents($path, json_encode($credentials));
        return $credentials;
    }

    // get authorization code
    function requestAuthCode() {
        global $GOAUTH, $CLIENT_ID, $REDIRECT_URI, $SCOPES;
        $url = sprintf($GOAUTH . 'auth?scope=%s&redirect_uri=%s&response_type=code&client_id=%s&approval_prompt=force&access_type=offline', urlencode(implode(' ', $SCOPES)), urlencode($REDIRECT_URI), urlencode($CLIENT_ID) );
        header('Location:' . $url);
        $_SESSION['Deficiencies'] = $_GET['Deficiencies'];
        exit();
    }

    // request access token
    function requestAccessToken($access_code) {
        global $GAPIS, $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
        $url = $GAPIS . 'oauth2/v4/token';
        $post_fields = 'code=' . $access_code . '&client_id=' . urlencode($CLIENT_ID) . '&client_secret=' . urlencode($CLIENT_SECRET) . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $response=curl_exec($ch);
        if ($response === false) {
            return curl_error($ch);
        } else {
            return json_decode($response, true);
        }
        curl_close($ch);
    }

    // get token (access or refresh)
    function getAccessToken($credentials) {
        $expire_date = new DateTime();
        $expire_date->setTimestamp($credentials['created']);
        $expire_date->add(new DateInterval('PT' . $credentials['expires_in'] . 'S'));
        $current_time = new DateTime();
        if ($current_time->getTimestamp() >= $expire_date->getTimestamp())
            return $credentials['refresh_token'];
        else
            return $credentials['access_token'];
    }

    // manage tokens
    function authenticate() {
        global $STORE_PATH;
        $credentials = (file_exists($STORE_PATH)) ? getStoredCredentials($STORE_PATH) : null;
        if (!(isset($_GET['code']) || isset($credentials))) requestAuthCode();
        if (!isset($credentials)) $credentials = requestAccessToken($_GET['code']);
        if (isset($credentials) && isset($credentials['access_token']) && !file_exists($STORE_PATH)) $credentials = storeCredentials($STORE_PATH, $credentials);
        return $credentials;
    }
    $credentials = authenticate();
    if(headers_sent()){
      $_SESSION['Deficiencies'] = $_GET['Deficiencies'];
      exit;
    }
    if(isset($_GET['code']) && !isset($_GET['Deficiencies'])){
      $_GET['Deficiencies'] = $_SESSION['Deficiencies'];
      unset($_SESSION['Deficiencies']);
    }
    echo 'AUTHENTICATED TO GOOGLE DRIVE</br>';
    if($_SESSION['User'] == 895 || TRUE){
      if(isset($_GET['Deficiencies'])){
        $Defiencies = explode(",", $_GET['Deficiencies']);
        $Category_Tests = array();
        foreach($Defiencies as $index=>$id){
          $r = sqlsrv_query($Portal,
            " SELECT  [Deficiency].*,
                      Category_Test.Start AS Start,
                      Category_Test.Type AS Category_Test_Type,
                      Category_Elevator_Part.Name AS Part_Name,
                      Category_Elevator_Part.External_ID AS Part_External_ID,
                      Category_Violation_Condition.Name AS Condition_Name,
                      Category_Violation_Condition.External_ID AS Condition_External_ID,
                      Category_Remedy.Name AS Remedy_Name,
                      Category_Remedy.External_ID AS Remedy_External_ID,
                      Category_Test.General_Comments AS General_Comments,
                      Loc.Loc AS Location_Loc,
                      Loc.ID AS Location_ID,
                      Loc.Tag AS Location_Tag,
                      Loc.Owner AS Customer_ID,
                      Elev.ID AS Unit_ID,
                      Elev.State + ' (' + Elev.Unit + ')' AS Unit_Label,
                      Elev.State AS Unit_State,
                      Elev.Unit AS Unit_Unit,
                      Zone.Name AS Zone,
                      Route.Mech AS Mech,
                      Emp.CallSign AS CallSign,
                      Terr.Name AS Territory,
                      Emp.fFirst + ' ' + Emp.Last AS Mechanic_Name
              FROM    [Deficiency]
                      LEFT JOIN Category_Elevator_Part ON Category_Elevator_Part.ID = Deficiency.Elevator_Part
                      LEFT JOIN Category_Violation_Condition ON Category_Violation_Condition.ID = Deficiency.Condition
                      LEFT JOIN Category_Remedy ON Category_Remedy.ID = Deficiency.Remedy
                      LEFT JOIN Category_Test ON Category_Test.ID = Deficiency.Category_Test
                      LEFT JOIN nei.dbo.Loc ON Category_Test.Location = Loc.Loc
                      LEFT JOIN nei.dbo.Elev ON Category_Test.Unit = Elev.ID
                      LEFT JOIN nei.dbo.Zone ON Loc.Zone = Zone.ID
                      LEFT JOIN nei.dbo.Route ON Route.ID = Loc.Route
                      LEFT JOIN nei.dbo.Terr ON Terr.ID = Loc.Terr
                      LEFT JOIN nei.dbo.Emp ON Emp.fWork = Route.Mech
              WHERE   [Deficiency].ID = ?
            ;", array($id));

          if($r){
            $row = sqlsrv_fetch_array($r);
            if(!is_array($row) || count($row) == 0){continue;}
            if(isset($row['Action']) && !is_null($row['Action'])){
              sqlsrv_query($Portal, "UPDATE Deficiency SET Processed = 1 WHERE ID = ?;", array($id));
            }
            if(isset($row['Category_Test'])){
              $Category_Tests[$row['Category_Test']] = isset($Category_Tests[$row['Category_Test']]) ? array_merge($Category_Tests[$row['Category_Test']], array($row)) : array($row);
            }
          }
        }

        $B = array();
        $P = array();
        foreach($Category_Tests as $key=>$Defs){
          echo 'PROCESSING CATEGORY TEST #' . $key . '</br>';
          $NC = array();
          $M = array();
          $R = array();
          $B = array();
          $P = array();
          $MR = array();
          $D = NULL;
          foreach($Defs as $index=>$Def){
            $D = $Def;
            switch($Def['Action']){
              case 'NC':array_push($NC, $Def);break;
              case 'M':array_push($M, $Def);break;
              case 'P':array_push($P, $Def);break;
              case 'R':array_push($R, $Def);break;
              case 'B':array_push($B, $Def);break;
              case 'MR':array_push($MR, $Def);break;
            }
          }
          $Due_Date = date('m/d/Y',strtotime('+120 days',strtotime($D['Start'])));
          $Start = date('m/d/Y', strtotime($D['Start']));
          $fDesc =   "{$D['Location_Tag']}\nCITY ID: {$D['Unit_State']}\nVIOLATION TYPE: " . date("Y", strtotime($D['Start'])) . " CAT DEF\nISSUED:{$Start}\nDUE:{$Due_Date}\nNOTES:{$D['General_Comments']}\n";
          $checker = "{$D['Location_Tag']}\nCITY ID: {$D['Unit_State']}\nVIOLATION TYPE: " . date("Y", strtotime($D['Start'])) . " CAT DEF\nISSUED:{$Start}\nDUE:{$Due_Date}\nNOTES:{$D['General_Comments']}\n";
          $rDesc =   "{$D['Location_Tag']}\nCITY ID: {$D['Unit_State']}\nVIOLATION TYPE: " . date("Y", strtotime($D['Start'])) . " CAT DEF\nISSUED:{$Start}\nDUE:{$Due_Date}\nNOTES:{$D['General_Comments']}\n";
          $mrDesc =  "{$D['Location_Tag']}\nCITY ID: {$D['Unit_State']}\nVIOLATION TYPE: " . date("Y", strtotime($D['Start'])) . " CAT DEF\nISSUED:{$Start}\nDUE:{$Due_Date}\nNOTES:{$D['General_Comments']}\n";
          $Remarks = "{$D['Location_Tag']}\nCITY ID: {$D['Unit_State']}\nVIOLATION TYPE: " . date("Y", strtotime($D['Start'])) . " CAT DEF\nISSUED:{$Start}\nDUE:{$Due_Date}\nNOTES:{$D['General_Comments']}\n";
          $i  = 1;
          $i2 = 1;
          $D = $Defs[array_rand($Defs)];
          foreach($NC as $index=>$Def){
            $D = $Def;
            $Def['Comments'] = strlen($Def['Comments']) > 0 ? $Def['Comments'] : '';
            $fDesc .= strlen($Def['Comments']) > 0 ? "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']}" : "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']}";
            $fDesc .= strlen($Def['Notes']) > 0 ? " - " . $Def['Notes'] . "\n" : "\n";
            $Remarks .= strlen($Def['Comments']) > 0 ? "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']} - FOR MAINTENANCE" : "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - FOR MAINTENANCE";
            $Remarks .= "\n";
            $i++;$i2++;
          }
          foreach($M as $index=>$Def){
            $D = $Def;
            $Def['Comments'] = strlen($Def['Comments']) > 0 ? $Def['Comments'] : '';
            $fDesc .= strlen($Def['Comments']) > 0 ? "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']}" : "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']}";
            $fDesc .= strlen($Def['Notes']) > 0 ? " - " . $Def['Notes'] . "\n" : "\n";
            $Remarks .= strlen($Def['Comments']) > 0 ? "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']} - FOR MAINTENANCE" : "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - FOR MAINTENANCE";
            $Remarks .= "\n";
            $i++;$i2++;
          }
          $i = 1;
          foreach($R as $index=>$Def){
            $D = $Def;
            $Def['Comments'] = strlen($Def['Comments']) > 0 ? $Def['Comments'] : '';
            $rDesc .= strlen($Def['Comments']) > 0 ? "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']}" : "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']}";
            $rDesc .= strlen($Def['Notes']) > 0 ? " - " . $Def['Notes'] . "\n" : "\n";
            $Remarks .= strlen($Def['Comments']) > 0 ? "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']} - FOR REPAIR" : "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - FOR REPAIR";
            $Remarks .= "\n";
            $i++;$i2++;
          }
          $i = 1;
          foreach($MR as $index=>$Def){
            $D = $Def;
            $Def['Comments'] = strlen($Def['Comments']) > 0 ? $Def['Comments'] : '';
            $mrDesc .= strlen($Def['Comments']) > 0 ? "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']}" : "[{$i}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']}";
            $mrDesc .= strlen($Def['Notes']) > 0 ? " - " . $Def['Notes'] . "\n" : "\n";
            $Remarks .= strlen($Def['Comments']) > 0 ? "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']} - FOR M&R" : "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - FOR M&R";
            $Remarks .= "\n";
            $i++;$i2++;
          }

          $bCheck = FALSE;
          $i = 1;
          foreach($B as $index=>$Def){
            $D = $Def;
            $bCheck = TRUE;
            $Remarks .= strlen($Def['Comments']) > 0 ? "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']} - FOR BUILDING" : "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - FOR BUILDING";
            $Remarks .= "\n";
            $i++;$i2++;
          }
          $pCheck = FALSE;
          $i = 1;
          foreach($P as $index=>$Def){
            $D = $Def;
            $pCheck = TRUE;
            $Remarks .= strlen($Def['Comments']) > 0 ? "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - {$Def['Comments']} - FOR ESTIMATING" : "[{$i2}] - {$Def['Remedy_Name']} {$Def['Condition_Name']} {$Def['Part_Name']} - FOR ESTIMATING";
            $Remarks .= "\n";
            $i++;$i2++;
          }
          $Def = $D;
          if( $Remarks != $checker ){
            //$Def = $D;

            $r9 = sqlsrv_query($NEI,"SELECT * FROM Violation WHERE ID = ?", array($Def['Violation']));
            $Remarks2 = NULL;
            if($r9){
              $row9 = sqlsrv_fetch_array($r9);
              if(is_array($row9) && isset($row9['Remarks'])){
                $Remarks2 = $row9['Remarks'];
              }
            }


            $r = sqlsrv_query($NEI,
      				"	SELECT 	Max(ID) AS ID
      					FROM 	 	Job
      				;", array());
      			if(!$r){continue;}
      			$Job_ID = sqlsrv_fetch_array($r)['ID'] + 1;
            $Zone = array(
              "DIVISION #1" => "DIV 1",
              "DIVISION #2" => "DIV 2",
              "DIVISION #3" => "DIV 3",
              "DIVISION #4" => "DIV 4",
              "REPAIR" => "REPAIR",
              "BASE"  =>  "BASE"
            );
            $Zone = isset($Zone[$Def['Zone']]) ? $Zone[$Def['Zone']] : $Def['Zone'];
            sqlsrv_query($NEI,
      				"	INSERT INTO Job(ID, fDesc, Type, Loc, Owner, Elev, Status, Remarks, Rev, Mat, Labor, Cost, Profit, Ratio, Reg, OT, DT, TT, Hour, BRev, BMat, BLabor, BCost, BProfit, BRatio, BHour, Template, fDate, NT, CType, GL, GLRev, PO, Post, WageC, EN, Certified, Elevs, RateTravel, RateOT, RateNT, RateDT, RateMileage, SPHandle, CreditCard,Source, Audit, fInt, Level, TFMCustom4, TFMCustom5, TechAlert, Custom1, Custom2, Custom6, Custom16, Custom18)
      					VALUES(?, ?, 4, ?, ?, ?, 0, ?, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 26, ?, 0.00, 'REPAIR', 90, 51, ' ', 0, 2, 1, 1, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 'None', 0, 0, 4, 0, 0, ' ', ?, ?, ?, ?, ?);",
      				array($Job_ID, date("Y", strtotime($Def['Start'])) . " CAT DEF - {$Def['Unit_Label']}", $Def['Location_Loc'], $Def['Customer_ID'], $Def['Unit_ID'], $Remarks, date("Y-m-d H:i:s"), $Zone, $Def['Unit_State'], date("m/d/Y"), date("m/d/Y", strtotime("+120 days", strtotime($Def['Start']))), date("Y", strtotime($Def['Start'])) . " CAT DEF"));
              if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
            echo 'MADE JOB #' . $Job_ID . '</br>';
            $Remarks2 = $My_User['fFirst'] . " " . $My_User['Last'] . " JOB #{$Job_ID} CREATED @ " . date("m/d/Y h:i A") . "\n" . $Remarks2;
            sqlsrv_query($NEI, "UPDATE Violation SET Remarks = ?, Custom13 = 1, Remarks2 = ?, Job = ? WHERE ID = ?;", array($Remarks2, $Zone, $Job_ID, $Def['Violation']));
            $r = sqlsrv_query($NEI,
              "	SELECT 	Max(ID) AS ID
                FROM 	 	JobTItem
              ;", array());
            if(!$r){continue;}
            $JobTItem_ID = sqlsrv_fetch_array($r)['ID'];
            $JobTItem_ID++;
            sqlsrv_query($NEI,
              " INSERT INTO [JobTItem](ID, JobT, Job, Type, fDesc, Code, Actual, Budget, [Line], [Percent], Comm, Stored, Modifier, ETC, ETCMod, THours, FC, Labor, BHours, GL)
                VALUES(?, 26, ?, 0, 'Revenue', '', 0.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 0.00, 0.00, NULL);
              ;", array($JobTItem_ID, $Job_ID));
              if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
            $JobTItem_ID++;
            sqlsrv_query($NEI,
              " INSERT INTO [JobTItem](ID, JobT, Job, Type, fDesc, Code, Actual, Budget, [Line], [Percent], Comm, Stored, Modifier, ETC, ETCMod, THours, FC, Labor, BHours, GL)
                VALUES(?, 26, ?, 1, 'Labor', '', 0.00, 0.00, 1, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 0.00, 0.00, NULL);
              ;", array($JobTItem_ID, $Job_ID));
              if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }
            $JobTItem_ID++;
            sqlsrv_query($NEI,
              " INSERT INTO [JobTItem](ID, JobT, Job, Type, fDesc, Code, Actual, Budget, [Line], [Percent], Comm, Stored, Modifier, ETC, ETCMod, THours, FC, Labor, BHours, GL)
                VALUES(?, 26, ?, 1, 'Materials', '', 0.00, 0.00, 2, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 0.00, 0.00, NULL);
              ;", array($JobTItem_ID, $Job_ID));
              if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".$error[ 'message']."<br />";
        }
    }

            if($fDesc != $checker){
              $r = sqlsrv_query($NEI,
        				"	SELECT 	Max(Tickets.ID) AS ID
        					FROM 		(
        						(SELECT 	Max(ID) AS ID	FROM 	TicketO)
        						UNION ALL
        						(SELECT 	Max(ID) AS ID	FROM 	TicketD)
        					) AS Tickets
        				;");
        			if(!$r){continue;}
        			$row = sqlsrv_fetch_array($r);
        			if(!is_array($row) || count($row) == 0){continue;}
              $Ticket_ID = $row['ID'] + 1;
              $values = array_fill(0, count(array($Ticket_ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), null, 10, NULL, NULL, 0, 'None', null, 'Who', 'fBy', 0, null, mull, null, null, 'LDesc3', 'LDesc4', 0, null, 1, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', $ID, 0, 1, 0)), '?');
      			  $values = implode(",",$values);

      			  sqlsrv_query($NEI,
      				  "	INSERT INTO TicketO(ID, CDate, DDate, EDate, Level, fWork, DWork, Type, Cat, fDesc, Who, fBy, LType, LID, LElev, LDesc1, LDesc2, LDesc3, LDesc4, Nature, Job, Assigned, City, State, Zip, Owner, Route, Terr,  Latt, fLong, CallIn, SpecType, SpecID, EN, Notes, fGroup, Source, High, Confirmed, Phone, Phone2, WorkOrder, idRolCustomContact, Internet, Est)
      					  VALUES({$values})
      				  ;", array($Ticket_ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), 4, $Def['Mech'], $Def['CallSign'], 0, 'None', $fDesc, 'Who', 'fBy', 0, $Def['Location_Loc'], $Def['Unit_ID'], $Def['Location_ID'], $Def['Location_Tag'], 'LDesc3', 'LDesc4', 0, $Job_ID, 1, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', $Ticket_ID, 0, 1, 0));
                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }
              foreach($Category_Tests[$key] as $Defiency_ROW){
                if(in_array($Defiency_ROW['Action'], array('M', 'NC'))){
                  sqlsrv_query($NEI, "UPDATE Portal.dbo.Deficiency SET Deficiency.Ticket = ? WHERE Deficiency.ID = ?", array($Ticket_ID, $Defiency_ROW['ID']));
                }
              }
              echo 'CREATED TICKET #' . $Ticket_ID . ' FOR MAINTENANCE</br>';
              $Remarks2 = $My_User['fFirst'] . " " . $My_User['Last'] . " CREATED TICKET # " . $Ticket_ID . " FOR MAINTENANCE @ " . date("m/d/Y h:i A") . "\n" . $Remarks2;
              sqlsrv_query($NEI, "UPDATE Violation SET Remarks = ? WHERE ID = ?;", array($Remarks2, $Def['Violation']));
              try {
                  $mail = new PHPMailer(true);

                  $from = "WebServices@NouveauElevator.com";
                  $replyto = $from;
                  //$Start_Time = $_POST['Start_Time'];
                  $date = date("Y-m-d H:i:s");
                  $_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "Nouveau_Elevator_Portal";
                  //Server settings
                  $mail->SMTPDebug = 2;                                       // Enable verbose debug output
                  $mail->isSMTP();                                            // Set mailer to use SMTP
                  $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                  $mail->Username   = 'webservices@nouveauelevator.com';                     // SMTP username
                  $mail->Password   = 'daxlxnzndgvwczth';                               // SMTP password
                  $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                  $mail->Port       = 587;                                    // TCP port to connect to

                  //Recipients
                  $mail->setFrom('webservices@nouveauelevator.com', 'Web Services');
                  $email_array = array(
                    "DIV 1"=> "division1@nouveauelevator.com",
                    "DIV 2"=> "division2@nouveauelevator.com",
                    "DIV 3"=> "division3@nouveauelevator.com",
                    "DIV 4"=> "division4@nouveauelevator.com"
                  );
                  $_POST['Email'] = isset($email_array[$Zone]) ? $email_array[$Zone] : "psperanza@nouveauelevator.com";
                  $_POST['Email'] .= ";code@nouveauelevator.com";
                  $Emails = explode(";", $_POST['Email']);
                  if(count($Emails) > 0){
                    foreach($Emails as $Email){
                        $mail->addAddress($Email);     // Add a recipient
                    }
                  } else {
                    $mail->addAddress($_POST['Email']);
                  }
                  //$mail->addCC('cc@example.com');

                  //$mail->addAddress('ellen@example.com');               // Name is optional
                  $mail->addReplyTo('webservices@nouveauelevator.com', 'NoReply');
                  //$mail->addBCC('bcc@example.com');

                  // Attachments
                  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                  $subject = "CAT DEF " . date("Y", strtotime($Def['Start'])) . " : {$Job_ID} :  {$Def['Location_Tag']} : {$Def['Unit_Label']}";
                  $message = "<html>
<head>
<style>
td {padding:5px;}
tr {border-bottom:#555555;}
</style>
</head>
<body>
<table width='700px' style='background-color:white;color:black;border:1px solid black;'><tbody>
<tr><td colspan='10' style='font-size:18px;background-color:black;color:white;border:1px solid black;'><img src='https://www.nouveauelevator.com/Images/Icons/logo.png' width='25px' /> Nouveau Elevator</td></tr>
<tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>Ticket Created</td></tr>
<tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>{$Def['Zone']} - {$Def['Location_Tag']} - {$Def['Unit_Label']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Territory:</td><td colspan='3'  style='border:1px solid black;'>{$Def['Territory']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Division:</td><td colspan='3'  style='border:1px solid black;'>{$Def['Zone']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Location:</td><td colspan='3' style='border:1px solid black;'>{$Def['Location_Tag']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Unit:</td><td colspan='3' style='border:1px solid black;'>{$Def['Unit_Label']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Mechanic:</td><td colspan='3' style='border:1px solid black;'>{$Def['Mechanic_Name']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Category Date:</td><td colspan='3' style='border:1px solid black;'>{$Def['Start']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Due:</td><td colspan='3' style='border:1px solid black;'>{$Due_Date}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Test Type:</td><td colspan='3' style='border:1px solid black;'>{$Def['Category_Test_Type']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Ticket #:</td><td colspan='3' style='border:1px solid black;'>{$Ticket_ID}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Ticket Description:</td><td colspan='3' style='border:1px solid black;'><pre>{$fDesc}</pre></td></tr>
</tbody></table>
</body>
</html>";

                  // Content
                  $mail->isHTML(true);                                  // Set email format to HTML
                  $mail->Subject = $subject;
                  $mail->Body = $message;
                  ob_start();
                  $mail->send();
                  ob_end_clean();
                  echo 'SENT MAIL TO SUPERVISOR: psperanza@nouveauelevator.com</br>';
                  //echo 'Message has been sent';
              } catch (Exception $e) {
                  //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
              }
            }
            if($rDesc != $checker){
              $r = sqlsrv_query($NEI,
        				"	SELECT 	Max(Tickets.ID) AS ID
        					FROM 		(
        						(SELECT 	Max(ID) AS ID	FROM 	TicketO)
        						UNION ALL
        						(SELECT 	Max(ID) AS ID	FROM 	TicketD)
        					) AS Tickets
        				;");
        			if(!$r){continue;}
        			$row = sqlsrv_fetch_array($r);
        			if(!is_array($row) || count($row) == 0){continue;}
              $Ticket_ID = $row['ID'] + 1;
              $values = array_fill(0, count(array($Ticket_ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), null, 10, NULL, NULL, 0, 'None', null, 'Who', 'fBy', 0, null, mull, null, null, 'LDesc3', 'LDesc4', 0, null, 1, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', $ID, 0, 1, 0)), '?');
      			  $values = implode(",",$values);

      			  sqlsrv_query($NEI,
      				  "	INSERT INTO TicketO(ID, CDate, DDate, EDate, Level, fWork, DWork, Type, Cat, fDesc, Who, fBy, LType, LID, LElev, LDesc1, LDesc2, LDesc3, LDesc4, Nature, Job, Assigned, City, State, Zip, Owner, Route, Terr,  Latt, fLong, CallIn, SpecType, SpecID, EN, Notes, fGroup, Source, High, Confirmed, Phone, Phone2, WorkOrder, idRolCustomContact, Internet, Est)
      					  VALUES({$values})
      				  ;", array($Ticket_ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), 6, NULL, NULL, 0, 'None', $rDesc, 'Who', 'fBy', 0, $Def['Location_Loc'], $Def['Unit_ID'], $Def['Location_ID'], $Def['Location_Tag'], 'LDesc3', 'LDesc4', 0, $Job_ID, 1, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', $Ticket_ID, 0, 1, 0));
                foreach($Category_Tests[$key] as $Defiency_ROW){
                  if(in_array($Defiency_ROW['Action'], array('R'))){
                    sqlsrv_query($NEI, "UPDATE Portal.dbo.Deficiency SET Deficiency.Ticket = ? WHERE Deficiency.ID = ?", array($Ticket_ID, $Defiency_ROW['ID']));
                  }
                }
                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }

              echo 'CREATED TICKET #' . $Ticket_ID . ' FOR REPAIR</br>';
              $Remarks2 = $My_User['fFirst'] . " " . $My_User['Last'] . " CREATED TICKET # " . $Ticket_ID . " FOR REPAIR @ " . date("m/d/Y h:i A") . "\n" . $Remarks2;
              sqlsrv_query($NEI, "UPDATE Violation SET Remarks = ? WHERE ID = ?;", array($Remarks2, $Def['Violation']));
              try {
                  $mail = new PHPMailer(true);
                  $from = "WebServices@NouveauElevator.com";
                  $replyto = $from;
                  //$Start_Time = $_POST['Start_Time'];
                  $date = date("Y-m-d H:i:s");
                  $_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "Nouveau_Elevator_Portal";
                  //Server settings
                  $mail->SMTPDebug = 2;                                       // Enable verbose debug output
                  $mail->isSMTP();                                            // Set mailer to use SMTP
                  $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                  $mail->Username   = 'webservices@nouveauelevator.com';                     // SMTP username
                  $mail->Password   = 'iudsnnlilgersxba';                               // SMTP password
                  $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                  $mail->Port       = 587;                                    // TCP port to connect to

                  //Recipients
                  $mail->setFrom('webservices@nouveauelevator.com', 'Web Services');
                  $_POST['Email'] = "repair@nouveauelevator.com;code@nouveauelevator.com";
                  $Emails = explode(";", $_POST['Email']);
                  if(count($Emails) > 0){
                    foreach($Emails as $Email){
                        $mail->addAddress($Email);     // Add a recipient
                    }
                  } else {
                    $mail->addAddress($_POST['Email']);
                  }
                  //$mail->addCC('cc@example.com');

                  //$mail->addAddress('ellen@example.com');               // Name is optional
                  $mail->addReplyTo('webservices@nouveauelevator.com', 'NoReply');
                  //$mail->addBCC('bcc@example.com');

                  // Attachments
                  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                  $subject = "CAT DEF " . date("Y", strtotime($Def['Start'])) . " : {$Job_ID} :  {$Def['Location_Tag']} : {$Def['Unit_Label']}";
                  $message = "<html>
<head>
<style>
td {padding:5px;}
tr {border-bottom:#555555;}
</style>
</head>
<body>
<table width='700px' style='background-color:white;color:black;border:1px solid black;'><tbody>
<tr><td colspan='10' style='font-size:18px;background-color:black;color:white;border:1px solid black;'><img src='https://www.nouveauelevator.com/Images/Icons/logo.png' width='25px' /> Nouveau Elevator</td></tr>
<tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>Ticket Created</td></tr>
<tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>{$Def['Zone']} - {$Def['Location_Tag']} - {$Def['Unit_Label']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Territory:</td><td colspan='3'  style='border:1px solid black;'>{$Def['Territory']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Division:</td><td colspan='3'  style='border:1px solid black;'>{$Def['Zone']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Location:</td><td colspan='3' style='border:1px solid black;'>{$Def['Location_Tag']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Unit:</td><td colspan='3' style='border:1px solid black;'>{$Def['Unit_Label']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Mechanic:</td><td colspan='3' style='border:1px solid black;'>{$Def['Mechanic_Name']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Category Date:</td><td colspan='3' style='border:1px solid black;'>{$Def['Start']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Due:</td><td colspan='3' style='border:1px solid black;'>{$Due_Date}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Test Type:</td><td colspan='3' style='border:1px solid black;'>{$Def['Category_Test_Type']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Ticket #:</td><td colspan='3' style='border:1px solid black;'>{$Ticket_ID}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Ticket Description:</td><td colspan='3' style='border:1px solid black;'><pre>{$fDesc}</pre></td></tr>
</tbody></table>
</body>
</html>";

                  // Content
                  $mail->isHTML(true);                                  // Set email format to HTML
                  $mail->Subject = $subject;
                  $mail->Body = $message;
                  ob_start();
                  $mail->send();
                  ob_end_clean();
                  echo 'SENT MAIL TO SUPERVISOR: repair@nouveauelevator.com</br>';
                  //echo 'Message has been sent';
              } catch (Exception $e) {
                  //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
              }
            }
            if($mrDesc != $checker){
              $r = sqlsrv_query($NEI,
        				"	SELECT 	Max(Tickets.ID) AS ID
        					FROM 		(
        						(SELECT 	Max(ID) AS ID	FROM 	TicketO)
        						UNION ALL
        						(SELECT 	Max(ID) AS ID	FROM 	TicketD)
        					) AS Tickets
        				;");
        			if(!$r){continue;}
        			$row = sqlsrv_fetch_array($r);
        			if(!is_array($row) || count($row) == 0){continue;}
              $Ticket_ID = $row['ID'] + 1;
              $values = array_fill(0, count(array($Ticket_ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), null, 10, NULL, NULL, 0, 'None', null, 'Who', 'fBy', 0, null, mull, null, null, 'LDesc3', 'LDesc4', 0, null, 1, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', $ID, 0, 1, 0)), '?');
      			  $values = implode(",",$values);

      			  sqlsrv_query($NEI,
      				  "	INSERT INTO TicketO(ID, CDate, DDate, EDate, Level, fWork, DWork, Type, Cat, fDesc, Who, fBy, LType, LID, LElev, LDesc1, LDesc2, LDesc3, LDesc4, Nature, Job, Assigned, City, State, Zip, Owner, Route, Terr,  Latt, fLong, CallIn, SpecType, SpecID, EN, Notes, fGroup, Source, High, Confirmed, Phone, Phone2, WorkOrder, idRolCustomContact, Internet, Est)
      					  VALUES({$values})
      				  ;", array($Ticket_ID, date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), date("Y-m-d H:i:s"), 14, NULL, NULL, 0, 'None', $mrDesc, 'Who', 'fBy', 0, $Def['Location_Loc'], $Def['Unit_ID'], $Def['Location_ID'], $Def['Location_Tag'], 'LDesc3', 'LDesc4', 0, $Job_ID, 1, 'City', 'NY', '00000', '0', '0', '0', '0', '0', '0', '0', '0', 1, '', '', 'Reference', 0, 0, '(', '(', $Ticket_ID, 0, 1, 0));
                if( ($errors = sqlsrv_errors() ) != null) {
                    foreach( $errors as $error ) {
                        echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
                        echo "code: ".$error[ 'code']."<br />";
                        echo "message: ".$error[ 'message']."<br />";
                    }
                }
                foreach($Category_Tests[$key] as $Defiency_ROW){
                  if(in_array($Defiency_ROW['Action'], array('MR'))){
                    sqlsrv_query($NEI, "UPDATE Portal.dbo.Deficiency SET Deficiency.Ticket = ? WHERE Deficiency.ID = ?", array($Ticket_ID, $Defiency_ROW['ID']));
                  }
                }
              echo 'CREATED TICKET #' . $Ticket_ID . ' FOR M/R</br>';
              $Remarks2 = $My_User['fFirst'] . " " . $My_User['Last'] . " CREATED TICKET # " . $Ticket_ID . " FOR M/R @ " . date("m/d/Y h:i A") . "\n" . $Remarks2;
              sqlsrv_query($NEI, "UPDATE Violation SET Remarks = ? WHERE ID = ?;", array($Remarks2, $Def['Violation']));
              try {
                  $mail = new PHPMailer(true);
                  $from = "WebServices@NouveauElevator.com";
                  $replyto = $from;
                  //$Start_Time = $_POST['Start_Time'];
                  $date = date("Y-m-d H:i:s");
                  $_SERVER['SERVER_NAME'] = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "Nouveau_Elevator_Portal";
                  //Server settings
                  $mail->SMTPDebug = 2;                                       // Enable verbose debug output
                  $mail->isSMTP();                                            // Set mailer to use SMTP
                  $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                  $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                  $mail->Username   = 'webservices@nouveauelevator.com';                     // SMTP username
                  $mail->Password   = 'iudsnnlilgersxba';                               // SMTP password
                  $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
                  $mail->Port       = 587;                                    // TCP port to connect to

                  //Recipients
                  $mail->setFrom('webservices@nouveauelevator.com', 'Web Services');
                  $_POST['Email'] = "mandr_request@nouveauelevator.com;code@nouveauelevator.com";
                  $Emails = explode(";", $_POST['Email']);
                  if(count($Emails) > 0){
                    foreach($Emails as $Email){
                        $mail->addAddress($Email);     // Add a recipient
                    }
                  } else {
                    $mail->addAddress($_POST['Email']);
                  }
                  //$mail->addCC('cc@example.com');

                  //$mail->addAddress('ellen@example.com');               // Name is optional
                  $mail->addReplyTo('webservices@nouveauelevator.com', 'NoReply');
                  //$mail->addBCC('bcc@example.com');

                  // Attachments
                  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                  $subject = "CAT DEF " . date("Y", strtotime($Def['Start'])) . " : {$Job_ID} :  {$Def['Location_Tag']} : {$Def['Unit_Label']}";
                  $message = "<html>
<head>
<style>
td {padding:5px;}
tr {border-bottom:#555555;}
</style>
</head>
<body>
<table width='700px' style='background-color:white;color:black;border:1px solid black;'><tbody>
<tr><td colspan='10' style='font-size:18px;background-color:black;color:white;border:1px solid black;'><img src='https://www.nouveauelevator.com/Images/Icons/logo.png' width='25px' /> Nouveau Elevator</td></tr>
<tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>Ticket Created</td></tr>
<tr><td colspan='10' style='text-decoration:underline;font-weight:bold;font-size:18px;background-color:#2d2d2d;color:white;border:1px solid black;'>{$Def['Zone']} - {$Def['Location_Tag']} - {$Def['Unit_Label']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Territory:</td><td colspan='3'  style='border:1px solid black;'>{$Def['Territory']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Division:</td><td colspan='3'  style='border:1px solid black;'>{$Def['Zone']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Location:</td><td colspan='3' style='border:1px solid black;'>{$Def['Location_Tag']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Unit:</td><td colspan='3' style='border:1px solid black;'>{$Def['Unit_Label']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Mechanic:</td><td colspan='3' style='border:1px solid black;'>{$Def['Mechanic_Name']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Category Date:</td><td colspan='3' style='border:1px solid black;'>{$Def['Start']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Due:</td><td colspan='3' style='border:1px solid black;'>{$Due_Date}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Test Type:</td><td colspan='3' style='border:1px solid black;'>{$Def['Category_Test_Type']}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Ticket #:</td><td colspan='3' style='border:1px solid black;'>{$Ticket_ID}</td></tr>
<tr><td colspan='1' style='font-weight:bold;border:1px solid black;'>Ticket Description:</td><td colspan='3' style='border:1px solid black;'><pre>{$fDesc}</pre></td></tr>
</tbody></table>
</body>
</html>";

                  // Content
                  $mail->isHTML(true);                                  // Set email format to HTML
                  $mail->Subject = $subject;
                  $mail->Body = $message;
                  ob_start();
                  $mail->send();
                  ob_end_clean();
                  echo 'SENT MAIL TO SUPERVISOR: mandr_request@nouveauelevator.com</br>';
                  //echo 'Message has been sent';
              } catch (Exception $e) {
                  //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
              }
            }
            sqlsrv_query($NEI,"UPDATE Violation SET Job = ?, Ticket = ?, Status = 'Job Created', Custom3 = ? WHERE ID = ?", array($Job_ID, $Ticket_ID, date("Y-m-d 00:00:00.000"), $Def['Violation']));
            echo 'UPDATED VIOLATION DATA #' . $Def['Violation'] . '</br>';
            if(TRUE){
            }
            //break;
          }
          if($bCheck == TRUE){
            sqlsrv_query($NEI,"UPDATE Violation SET Status = 'Building Letter Req' WHERE ID = ?", array($Def['Violation']));
            $Remarks2 = $My_User['fFirst'] . " " . $My_User['Last'] . " NEEDS BUILDING LETTER @ " . date("m/d/Y h:i A") . "\n" . $Remarks2;
            sqlsrv_query($NEI, "UPDATE Violation SET Remarks = ? WHERE ID = ?;", array($Remarks2, $Def['Violation']));
          }
          if($pCheck == TRUE){
            sqlsrv_query($NEI,"UPDATE Violation SET Status = 'To Estimating' WHERE ID = ?", array($Def['Violation']));
            $Remarks2 = $My_User['fFirst'] . " " . $My_User['Last'] . " NEEDS ESTIMATE @ " . date("m/d/Y h:i A") . "\n" . $Remarks2;
            sqlsrv_query($NEI, "UPDATE Violation SET Remarks = ? WHERE ID = ?;", array($Remarks2, $Def['Violation']));
          }
          if(TRUE){
            //$GAPIS = 'https://www.googleapis.com/';


            // generic
            $mime_type = 'text/html';  // could be 'image/jpeg', etc.
            $Job_ID_Label = isset($Job_ID) ? " - " . $Job_ID : NULL;
            $new_name = str_replace(".", "", "{$Def['Location_Tag']} - CAT DEF " . date("Y", strtotime($Def['Start'])) . " - {$Def['Unit_State']}{$Job_ID_Label}");
            $the_file_and_path = "https://www.nouveauelevator.com/portal/short_category_test.php?Token=jghkb423hg234g234kjhgawergladgh42kg423kgjakqgjsdkjhg1khg24kjghasdgkj12kgj3qhg54235jkh2315jkh2gasfasdgjkhasdfghkjasdfhkgjfs&ID={$key}";
            $my_access_token = json_decode(file_get_contents('../../../../../token_' . $_SESSION['User'] . '.json'), true)['access_token'];
            $result = uploadFile($my_access_token, $the_file_and_path, $mime_type, $new_name, '0ByFVts4kTa6_cHRPYXRWRmVFbkU');

            /*$mime_type = 'text/html';  // could be 'image/jpeg', etc.
            $new_name = $Def['Location_Tag'] . " - CAT DEF 2019 - " . $Def['Unit_State'];
            $the_file_and_path = "https://www.nouveauelevator.com/portal/short_category_test.php?Token=jghkb423hg234g234kjhgawergladgh42kg423kgjakqgjsdkjhg1khg24kjghasdgkj12kgj3qhg54235jkh2315jkh2gasfasdgjkhasdfghkjasdfhkgjfs&ID=" . $key;
            $my_access_token = json_decode(file_get_contents('../../../../../token.json'), true)['access_token'];
            $result = uploadFile($my_access_token, $the_file_and_path, $mime_type, $new_name, '0ByFVts4kTa6_ellRbnRlWEM5SEk');*/
            echo $result ."</br>";
            echo "UPLOADED FILE CATEGORY TEST #{$key}</br> NAME: {$new_name}</br>";
            unset($Job_ID);
          }
        }
      }
    }
  }
}?>
