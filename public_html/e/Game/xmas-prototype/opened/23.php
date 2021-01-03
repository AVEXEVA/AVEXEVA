<?php 
$csv = array_map('str_getcsv', file('gifts/index.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);
?>
<div class='card' rel='23'>
  <div class='card-header'>$24.99 Hammock</div>
  <img class='card-image-top' src='media/hammock.jpg' width='100%' />
  <div class='card-body'>Kootek Camping Hammock Double & Single Portable Hammocks with 2 Tree Straps, Lightweight Nylon Parachute Hammocks for Backpacking, Travel, Beach, Backyard, Patio, Hiking</div>
  <div class='card-footer'>
    <div class='row'>
      <div class='col'><button onClick='' disabled>Claimed by <?php
        if(count($csv) > 0){foreach($csv as $row){
          if($row['Gift'] == 23){echo $row['Person'];}
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

    if(count($csv) > 0){foreach($csv as $row){
      if($row['Person'] == $_SESSION['Person']){$check = false;}
    }}  

    $csv = array_map('str_getcsv', file('gifts/index.csv'));
    array_walk($csv, function(&$a) use ($csv) {
      $a = array_combine($csv[0], $a);
    });
    array_shift($csv);

    if(count($csv) > 0 && $check){foreach($csv as $row){
      if($row['Gift'] == 23 && $row['Person'] == $_SESSION['Person']){?>
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
      if($row['Gift'] == 23 && $row['Person'] != $_SESSION['Person']){?>
        <div class='row'>
          <div class='col'><button onClick='stealGift(this);'>Steal Gift from <?php 
            if(count($csv) > 0){foreach($csv as $row){
              if($row['Gift'] == 23){echo $row['Person'];}
            }}
          ?></button></div>
        </div>
      <?php }
    }}
    ?></div>
</div>