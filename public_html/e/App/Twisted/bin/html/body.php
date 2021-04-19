<style>
.card-body {
	font-size:18px;
}
.card-footer button {
	width:100%;
	height:35px;
	background-color:black;
	color:white;
}
.card-footer button:hover {
	background-color:transparent;
	color:black;
}
</style>
<div id='container' style='display:flex;background-color:whitesmoke;'>
  <div class='delivery'>
	  <div class='menu' style='width:20%;padding:25px;'>
	    <div class='card'>
	    	<div class='card-header'><h2>Location</h2></div>
	    	<div class='card-body'>Zip Code: <input type='text' name='Zip' /></div>
	    	<div class='card-footer'><button onClick='zipcode();'>Submit</button></div>
	    </div>	    
	  </div>
  </div>
  <?php require('bin/html/map.php');?>
</div>
