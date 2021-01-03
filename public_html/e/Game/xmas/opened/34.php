<?php
$csv = array_map('str_getcsv', file('gifts/index.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);
?>
<div class='card' rel='34'>
  <div class='card-header'>$36.99 Survival Kit</div>
  <img class='card-image-top' src='media/kit.jpg' width='100%' />
  <div class='card-body'>ETROL First Aid Kit, Upgrade Emergency Survival Kit - 22 in 1 Compatible IFAK - 3L Military Tactical Molle Pouch - Camping Gear Trauma kit for Hiking Car Home Fishing Earthquake Travel Supplies</div>
  <div class='card-footer'>
    <div class='row'>
      <div class='col'><button onClick='' disabled>Claimed by <?php
        if(count($csv) > 0){foreach($csv as $row){
          if($row['Gift'] == 34){echo $row['Person'];}
        }}
      ?></div>
    </div>
    <?php
    $csv = array_map('str_getcsv', file('throwbacks.csv'));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);

    $check = true;

    $i = 0;

    if(count($csv) > 0){foreach($csv as $row){
      if($row['Person'] == $_SESSION['Person']){$i++;}
    }}
    if($i > 7){$check = false;}

    $csv = array_map('str_getcsv', file('gifts/index.csv'));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);

    if(count($csv) > 0 && $check){foreach($csv as $row){
      if($row['Gift'] == 34 && $row['Person'] == $_SESSION['Person']){?>
        <div class='row'>
          <div class='col'><button onClick='throwbackGift(this);'>Throwback Gift</button></div>
        </div>
      <?php }
    }}
    ?>
    <?php
    $csv = array_map('str_getcsv', file('steals.csv'));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);

    $check = true;

    if(count($csv) > 0){foreach($csv as $row){
      if($row['Person'] == $_SESSION['Person']){$check = false;}
    }}

    $csv = array_map('str_getcsv', file('gifts/index.csv'));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);

    if(count($csv) > 0 && $check){foreach($csv as $row){
      if($row['Gift'] == 34 && $row['Person'] != $_SESSION['Person']){?>
        <div class='row'>
          <div class='col'><button onClick='stealGift(this);'>Steal Gift from <?php
            if(count($csv) > 0){foreach($csv as $row){
              if($row['Gift'] == 34){echo $row['Person'];}
            }}
          ?></button></div>
        </div>
      <?php }
    }}
    ?></div>
</div>
