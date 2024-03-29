<?php 
session_start();
require('../get/index.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
    $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
    $array = sqlsrv_fetch_array($r);
    $Privileged = FALSE;
    if(!isset($_SESSION['Branch']) || $_SESSION['Branch'] == 'Nouveau Elevator'){
        $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_GET['User']));
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
        if(isset($My_Privileges['Job']) && $My_Privileges['Job']['User_Privilege'] >= 4 && $My_Privileges['Job']['Group_Privilege'] >= 4 && $My_Privileges['Job']['Other_Privilege'] >= 4){$Privileged = TRUE;}
    }
    if(!isset($array['ID'],$_GET['ID']) || !$Privileged){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
    else {
   		//$r = sqlsrv_query($NEI, "SELECT Loc.Loc AS ID, Loc.Tag AS Name FROM Loc WHERE Loc.Owner = ?",array($_GET['ID']));
   		?><script>
      var availableLocations = [<?php 
        if($_GET['ID'] == ''){$SQL = "'1' = '1'";}
        else{$SQL = "Loc.Owner = '{$_GET['ID']}'";}
        $r = sqlsrv_query($NEI,"SELECT Loc.Loc AS ID, Loc.Tag as Name FROM Loc WHERE {$SQL} ORDER BY Loc.Tag ASC");
        $Locations = array();
        if($r){while($Location = sqlsrv_fetch_array($r)){$Locations[$Location['ID']] = $Location['Name'];}}
        $data = array();
        if(count($Locations) > 0){foreach($Locations as $id=>$name){
          $name = str_replace("'","",$name);
          $data[] = '{value:' . '"'. $id . '"' . ', label:' . '"' . $name . '"' . '}';
        }}
        if(count($data) > 0){echo implode(",",$data);}
        ?>
      ];
      $(document).ready(function(){
        $("input#Locations").autocomplete({
          minLength: <?php if(is_numeric($_GET['ID'])){?>0<?php } else {?>3<?php }?>,
          source: availableLocations,
          focus: function( event, ui ) {
            $("input#Locations").val( ui.item.label );
            return false;
          },
          select: function( event, ui ) {
            $("input#Locations").val( ui.item.label );
            $("input#Location").val( ui.item.value );
            return false;
          }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
          return $( "<li>" )
            .append( "<div>" + item.label + "</div>" )
            .appendTo( ul );
        };
      });
    </script><input id='Locations' placeholder='Location' type='text' />
                                  <input id='Location' name='Location' type='hidden' /><?php
    }
}?>