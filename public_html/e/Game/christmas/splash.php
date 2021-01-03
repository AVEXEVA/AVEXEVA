<?php 
$_SESSION['Splash'] = true;
?><html>
<head>
	<style>
body {
  background-color: #00509d;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.christmas {
  position: relative;
}

.tree {
  position: relative;
  background-color: #685044;
  width: 30px;
  height:80px;
  top:100px;
  transform-style: preserve-3d;
}

.tree:before {
  content:"";
  position: relative;
  width: 0;
  height: 0;
  border-left: 90px solid transparent;
  border-right: 90px solid transparent;
  border-bottom: 270px solid #127475;
  border-radius: 30px;
  top:-250px;
  left:-75px;
}

.chain {
  width: 85px;
  height: 85px;
  border: solid 3px #333;
  border-radius: 50%;
  top: -185px; 
  left: -35px; 
  position: absolute;
  transform: rotate3d(8, 0.1, -5, 75deg); 
  box-sizing: border-box;
  backface-visibility: visible !important;
  z-index:5;
}

.chain2 {
  width: 145px;
  height: 135px;
  border: solid 3px #333;
  border-radius: 50%;
  top: -115px; 
  left: -65px; 
  position: absolute;
  transform: rotate3d(8, 0.1, -5, 75deg); 
  box-sizing: border-box;
  backface-visibility: visible !important;
  z-index:5;
  
}

.shadow {
  background-color: rgba(0,0,0,0.07);
  position: absolute;
  width: 250px;
  height: 30px;
  border-radius:50%;
  top:170px;
  left:-115px;
  z-index:-1;
}

.star {
  margin: 50px 0;
  position: absolute;
  display: block;
  width: 0px;
  height: 0px;
  border-right: 25px solid transparent;
  border-bottom: 17.5px solid #f9dc5c;
  border-left: 25px solid transparent;
  transform: rotate(35deg);
  top:-190px;
  left:-9px;
    }

.star:before {
      border-bottom: 20px solid #f9dc5c;
      border-left: 7.5px solid transparent;
      border-right: 7.5px solid transparent;
      position: absolute;
      height: 0;
      width: 0;
      top: -12.5px;
      left: -17.5px;
      display: block;
      content: '';
      transform: rotate(-35deg);
    }

.star:after {
      position: absolute;
      display: block;
      top: 0.75px;
      left: -26.25px;
      width: 0px;
      height: 0px;
      border-right: 25px solid transparent;
      border-bottom: 17.5px solid #f9dc5c;
      border-left: 25px solid transparent;
      transform: rotate(-70deg);
      content: '';
    }

.lights {
  position: absolute;
  
}

.light1 {
   position: absolute;
   width: 15px; 
   height: 15px;
   border-radius: 10px 150px 30px 150px;
}

.light1 {
  background-color: #ff595e;
  top:-100px;
  left:-35px;
  transform: rotate(40deg);
  box-shadow: 1px 1px 15px #faf3dd;
}

.light2 {
  position: absolute;
  background-color: #ffca3a;
  top:-95px;
  left:-10px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light3 {
  position: absolute;
  background-color: #6a4c93;
  top:-105px;
  left:15px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light4 {
  position: absolute;
  background-color: #1982c4;
  top:-118px;
  left:35px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light5 {
  position: absolute;
  background-color: #1982c4;
  top:12px;
  left:-55px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light6 {
  position: absolute;
  background-color: #8ac926;
  top:15px;
  left:-25px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light7 {
  position: absolute;
  background-color: #ff595e;
  top:10px;
  left:2px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light8 {
  position: absolute;
  background-color: #ffca3a;
  top:-2px;
  left:27px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light9 {
  position: absolute;
  background-color: #9e0059;
  top:-17px;
  left:50px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.light10 {
  position: absolute;
  background-color: #4361ee;
  top:-40px;
  left:68px;
  box-shadow: 1px 1px 15px #faf3dd;
  width: 15px; 
  height: 15px;
  border-radius: 10px 150px 30px 150px;
  transform: rotate(40deg);
}

.gift {
  position: absolute;
  width: 60px;
  height: 50px;
  background-color: #ffc857;
  top:130px;
  left:30px;
  box-shadow: inset -8px 0 0 rgba(0,0,0,0.07);
  
}

.gift:before {
  content:"";
  position: absolute;
  width:70px;
  height:15px;
  background-color: #ffc857;
  left:-5px;
  box-shadow: inset -8px -4px 0 rgba(0,0,0,0.07);
  
}

.gift:after {
  content:"";
  background-color: #db3a34;
  width: 10px;
  height:50px;
  position: absolute;
  left:25px;
}

.ribbon {
  position: absolute;
  width: 20px;
  height: 10px;
  border: 3px solid #db3a34;
  border-radius:50%;
  transform: skew(15deg, 15deg);
  top:116px;
  left:35px;
}

.ribbon:before {
  content:"";
  position: absolute;
  width: 20px;
  height: 10px;
  border: 3px solid #db3a34;
  border-radius:50%;
  transform: skew(-15deg, -20deg);
  left:22px;
  top:-8px;
}

.gift2 {
  position: absolute;
  width: 50px;
  height: 40px;
  background-color: #08bdbd;
  top:140px;
  left:-65px;
  box-shadow: inset -8px 0 0 rgba(0,0,0,0.07);
  
}

.gift2:before {
  content:"";
  position: absolute;
  width:60px;
  height:15px;
  background-color: #08bdbd;
  left:-5px;
  box-shadow: inset -8px -4px 0 rgba(0,0,0,0.07);
  
}

.gift2:after {
  content:"";
  background-color: #abff4f;
  width: 10px;
  height:40px;
  position: absolute;
  left:15px;
}

.gift3 {
  position: absolute;
  width: 40px;
  height: 30px;
  background-color: #7678ed;
  top:150px;
  left:-85px;
  box-shadow: inset -8px 0 0 rgba(0,0,0,0.07);
  
}

.gift3:before {
  content:"";
  position: absolute;
  width:50px;
  height:10px;
  background-color: #7678ed;
  left:-5px;
  box-shadow: inset -8px -4px 0 rgba(0,0,0,0.07);
  
}

.gift3:after {
  content:"";
  background-color: #f7b801;
  width: 7px;
  height:30px;
  position: absolute;
  left:15px;
}

.ribbon2 {
  position: absolute;
  width: 15px;
  height: 7px;
  border: 3px solid #abff4f;
  border-radius:50%;
  transform: skew(15deg, 15deg);
  top:129px;
  left:-65px;
}

.ribbon2:before {
  content:"";
  position: absolute;
  width: 15px;
  height: 7px;
  border: 3px solid #abff4f;
  border-radius:50%;
  transform: skew(-15deg, -20deg);
  left:15px;
  top:-8px;
}

.ribbon3 {
  position: absolute;
  width: 12px;
  height: 5px;
  border: 3px solid #f7b801;
  border-radius:50%;
  transform: skew(15deg, 15deg);
  top:142px;
  left:-85px;
}

.ribbon3:before {
  content:"";
  position: absolute;
  width: 12px;
  height: 5px;
  border: 3px solid #f7b801;
  border-radius:50%;
  transform: skew(-15deg, -20deg);
  left:15px;
  top:-8px;
}

.balls {
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #db3a34;
  top:15px;
  left: -15px;
}

.balls:before {
  content:"";
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #ffc857;
  top:35px;
  left: -15px;
}

.balls:after {
  content:"";
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #f07167;
  top:20px;
  left: 45px;
}

.ball1 {
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #fae588;
  top:-90px;
  left:20px;
  
}

.ball1:before {
  position: absolute;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  background-color: #fae588;
  content:"";
  top:170px;
  left:50px
}

.light1, .light2, .light3, .light4, .light5, .light6, .light7, .light8, .light9, .light10 {
  -webkit-animation: flash 6s infinite;
}

@-webkit-keyframes flash {
  20%, 24%, 55% {box-shadow: none;}
 0%, 19%, 21%, 23%, 25%, 54%, 56%, 100% {
 box-shadow: 0 0 5px #f5de93, 0 0 15px #f5de93, 0 0 20px #f5de93, 0 0 40px #f5de93, 0 0 60px #decea4, 0 0 10px #d6c0a5, 0 0 98px #ff0000;
  }
}
	</style>
</head>
<body>
<div id='container' onClick="location.reload();">
	<div class="christmas">
	  <div class="tree">
	    <div class="chain"></div>
	    <div class="chain2"></div>
	  </div>
	  <div class="lights">
	    <div class="light1"></div>
	    <div class="light2"></div>
	    <div class="light3"></div>
	    <div class="light4"></div>
	    <div class="light5"></div>
	    <div class="light6"></div>
	    <div class="light7"></div>
	    <div class="light8"></div>
	    <div class="light9"></div>
	    <div class="light10"></div>
	  </div>
	  <div class="balls">
	    <div class="ball1"></div>  
	  </div>
	  <div class="star"></div>
	  <div class="gift"></div>
	  <div class="ribbon"></div>
	  <div class="gift2"></div>
	  <div class="ribbon2"></div>
	  <div class="gift3"></div>
	  <div class="ribbon3"></div>
	  <div class="shadow"></div>
	</div>	
</div>
</body>
</html>