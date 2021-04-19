<?php 
$csv = array_map('str_getcsv', file('gifts/index.csv'));
array_walk($csv, function(&$a) use ($csv) {
  $a = array_combine($csv[0], $a);
});
array_shift($csv);
?>
<div class='card' rel='2'>
  <div class='card-header'>$25 Litecoin</div>
  <img class='card-image-top' src='media/cryptocurrencies.jpg' width='100%' />
  <div class='card-body'>Litecoin is a peer-to-peer Internet currency that enables instant, near-zero cost payments to anyone in the world. Litecoin is an open source, global payment network that is fully decentralized without any central authorities. </div>
  <div class='card-footer'>
    <div class='row'>
      <div class='col'><button onClick='' disabled>Claimed by <?php
        $csv = array_map('str_getcsv', file('gifts/index.csv'));
        array_walk($csv, function(&$a) use ($csv) {
          $a = array_combine($csv[0], $a);
        });
        array_shift($csv);

        if(count($csv) > 0){foreach($csv as $row){
          if($row['Gift'] == 2){echo $row['Person'];}
        }}
      ?></button></div>
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
      if($row['Gift'] == 2 && $row['Person'] == $_SESSION['Person']){?>
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
      if($row['Gift'] == 2 && $row['Person'] != $_SESSION['Person']){?>
        <div class='row'>
          <div class='col'><button onClick='stealGift(this);'>Steal Gift from <?php 
            if(count($csv) > 0){foreach($csv as $row){
              if($row['Gift'] == 2){echo $row['Person'];}
            }}
          ?></button></div>
        </div>
      <?php }
    }}
    ?></div>
</div>