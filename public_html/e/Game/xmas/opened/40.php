<?php
$csv = array_map('str_getcsv', file('gifts/index.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);
?>
<div class='card' rel='40'>
  <div class='card-header'>$89.99 Video Camera</div>
  <img class='card-image-top' src='media/camera.jpg' width='100%' />
  <div class='card-body'>Video Camera Camcorder for YouTube, Aasonida Digital Vlogging Camera FHD 1080P 30FPS 24MP 16X Digital Zoom 3.0 Inch 270° Rotation Screen Video Recorder with Microphone, Remote Control, 2 Batteries</div>
  <div class='card-footer'>
    <div class='row'>
      <div class='col'><button onClick='' disabled>Claimed by <?php
        if(count($csv) > 0){foreach($csv as $row){
          if($row['Gift'] == 40){echo $row['Person'];}
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
      if($row['Gift'] == 40 && $row['Person'] == $_SESSION['Person']){?>
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
      if($row['Gift'] == 40 && $row['Person'] != $_SESSION['Person']){?>
        <div class='row'>
          <div class='col'><button onClick='stealGift(this);'>Steal Gift from <?php
            if(count($csv) > 0){foreach($csv as $row){
              if($row['Gift'] == 40){echo $row['Person'];}
            }}
          ?></button></div>
        </div>
      <?php }
    }}
    ?></div>
</div>
