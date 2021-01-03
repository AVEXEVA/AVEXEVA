<?php
session_start();
if(!isset($_SESSION['Splash'])){require('splash.php');}
else {
  if(isset($_GET['Person']) || isset($_SESSION['Person'])){
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  $_SESSION['Person'] = isset($_GET['Person']) ? $_GET['Person'] : $_SESSION['Person'];
  ?><html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' type='text/css' href='css/card.css' />
    <link rel='stylesheet' type='text/css' href='css/columns.css' />
    <link rel='stylesheet' type='text/css' href='css/tree.css' />
    <script src="js/jquery.min.js"></script>
    <script src='js/snow.js'></script>
    <style>
    html {
      width:100%;
    }
    header {
      background-color:rgba(0,0,0,.85);
      color:white;
      font-size:36px;
      text-align:center;
    }
    body {
      background: #111;
      margin:0;
      width:100%;
      height:100%;
      color:white;
    }
    div.card:nth-child(odd) .card-header {
      background-color:green;
      color:black;
    }
    div.card:nth-child(even) .card-header {
      background-color:red;
      color:black;
    }
    @media only screen and (max-width:500px){
      div.card{ 
        flex: 1 1 calc(100%);
        max-width:100%;
      }
      
    }
    @media only screen and (min-width:500px){
      div.card {
        flex:1 1 calc(25%);
        max-width:25%;
      }
    }

    .card-header {
      font-size:36px;
      padding:10px;
    }
    .card-body {
      font-size:24px;
      padding:25px;
    }
    .card-url {
      width:100%;
      box-sizing:border-box;
      height:35px;
      background-color:gold;
      color:black;
      text-align:center;
      font-size:24px;
    }
    button {
      width:100%;
      height:35px;
      font-size:22px;
    }
    .popup {
      position:absolute;
      top:0;
      left:0;
      width:100%;
      height:100%;
      margin:auto;
    }
    </style>
  </head>
  <body>
    <header style='position:fixed;top:0;height:50px;z-index:999;width:100%;padding:10px;'>Merry Christmas!</header>
    <div style='position:fixed;top:0px;right:0px;background-color:transparent;color:white;height:25px;z-index:1000;width:100%;'><?php
      $csv = array_map('str_getcsv', file('gifts/index.csv'));
      array_walk($csv, function(&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
      });
      array_shift($csv);

      $amounts = array_map('str_getcsv', file('amounts.csv'));
      array_walk($amounts, function(&$a) use ($amounts) {
        $a = array_combine($amounts[0], $a);
      });
      array_shift($amounts);

      $owned = array();
      if(count($csv) > 0){foreach($csv as $row){
        if($row['Person'] == $_SESSION['Person']){
          $owned[] = $row['Gift'];
        }
      }}
      $budget = 0;
      if(count($amounts) > 0 && count($owned) > 0){foreach($amounts as $row){
        if(in_array($row['Gift'], $owned) ){
          $budget = $budget + $row['Amount'];
        }
      }}
      echo 'Points:' . $budget . ' / 150';
    ?></div>
    <script>
      function openGift(link){
        if(confirm('Are you sure you want to open this gift?')){
          $.ajax({
            url:'open.php?Person=<?php echo $_SESSION['Person'];?>&Gift=' + $(link).parent().parent().parent().parent().attr('rel'),
            success:function(){ location.reload(); }
          })
        }
      }
      function stealGift(link){
        if(confirm('Are you sure you want to steal this gift?')){
          $.ajax({
            url:'steal.php?Person=<?php echo $_SESSION['Person'];?>&Gift=' + $(link).parent().parent().parent().parent().attr('rel'),
            success:function(){ location.reload(); }
          });
        }
      }
      function throwbackGift(link){
        if(confirm('Are you sure you want to throwback this gift?')){
          $.ajax({
            url:'throwback.php?Person=<?php echo $_SESSION['Person'];?>&Gift=' + $(link).parent().parent().parent().parent().attr('rel'),
            success:function(){ location.reload(); }
          });
        }
      }
      function previewGift(link){
        $.ajax({
          url:'preview.php?Person=<?php echo $_SESSION['Person'];?>&Gift=' + $(link).parent().parent().parent().parent().attr('rel'),
          success:function(amount){ alert(amount); location.reload(); }
        })
      }
    </script>
    <div style='position:relative;padding-top:75px;margin:auto;display:flex;flex-wrap:wrap;width:100%;'>
    <?php
      $Files = scandir('gifts');

      $csv2 = array_map('str_getcsv', file('gifts/index.csv'));
      array_walk($csv2, function(&$a) use ($csv2) { 
        $a = array_combine($csv2[0], $a);  
      });
      array_shift($csv2);

      //shuffle($Files);

      foreach($Files as $File){
        if($File == '.' || $File == '..' || $File == 'index.csv'){continue;}
        $Status = false;
        if(count($csv2) > 0){foreach($csv2 as $row){
          if(isset($row['Gift']) && strlen($row['Gift']) > 0 && $row['Gift'] == substr($File, 0, strlen($File) - 4)){$Status = true;}
        }}
        if(!$Status){
          require('gifts/' . $File);
        } else {
          require('opened/' . $File);
        }
      }
    ?></div>
  </body>
  </html>
  <?php }
}?>