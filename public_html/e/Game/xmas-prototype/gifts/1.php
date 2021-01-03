<div class='card' rel='1'>
  <div class='card-header'>Cryptocurrency</div>
  <img class='card-image-top' src='media/cryptocurrencies.jpg' width='100%' />
  <div class='card-body'>Mystery Amount of Cryptocurrency</div>
  <div class='card-footer'>
    <div class='row'>
      <div class='col'><button onClick='openGift(this);'>Claim and Open</div>
    </div>
    <?php 
		$csv = array_map('str_getcsv', file('previews.csv'));
		array_walk($csv, function(&$a) use ($csv) {
		  $a = array_combine($csv[0], $a);
		});
		array_shift($csv);

		$check = true;

		$i = 0;
		if(count($csv) > 0){foreach($csv as $row){
			if($row['Person'] == $_SESSION['Person']){$i++;}
			if($i == 2){$check = false;}
		}}
		if($check){?><div class='row'><div class='col'><button onClick='previewGift(this);'>Preview Price</button></div></div><?php }
      ?>
  </div>
</div>
