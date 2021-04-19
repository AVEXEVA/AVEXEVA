<style>
body,
html {
  font-family: "Raleway", sans-serif;
  font-size: 0.875rem;
  background-color: #e9dfd3;
}

h2 {
  font-size: 2rem;
  margin: 0.2em 0;
}

.cd-switch {
  padding: 50px 0;
  text-align: center;
}

.switchFilter {
  width: 0;
  display: inline-block;
  background-color: #db6576;
  position: absolute;
  left: 0;
  opacity: 0;
  top: 0;
  bottom: 0;
  z-index: -1;
  -webkit-transition: all 0.4s cubic-bezier(0, 0, 0.25, 1);
  -moz-transition: all 0.4s cubic-bezier(0, 0, 0.25, 1);
  transition: all 0.4s cubic-bezier(0, 0, 0.25, 1);
}

label {
  cursor: pointer;
  text-transform: uppercase;
  border: 1px solid #3d4349;
  width: 85px;
  padding: 15px 0;
  text-align: center;
  display: inline-block;
  -webkit-transition: all 0.4s;
  -moz-transition: all 0.4s;
  transition: all 0.4s;
  margin-right: 10px;
}

.switch {
  position: absolute;
  display: inline-block;
  left: 50%;
  right: auto;
  -webkit-transform: translateX(-50%);
  -moz-transform: translateX(-50%);
  -ms-transform: translateX(-50%);
  -o-transform: translateX(-50%);
  transform: translateX(-50%);
  z-index: 1;
  margin: 2em 0;
}
.switch input[type=radio] {
  visibility: hidden;
  position: absolute;
  height: 100%;
}
.switch input[type=radio]#yes:checked ~ label[for=yes] {
  color: white;
  border: 1px solid #db6576;
}
.switch input[type=radio]#yes:checked ~ .switchFilter {
  left: 0;
  opacity: 1;
  width: 87px;
}
.switch input[type=radio]#no:checked ~ label[for=no] {
  color: white;
  border: 1px solid #db6576;
}
.switch input[type=radio]#no:checked ~ .switchFilter {
  left: 100px;
  width: 87px;
  opacity: 1;
}
</style>
<div id='f21' style='position:absolute;top:10%;left:10%;width:80%;height:35%;background-color:rgba(25,25,25,.9);z-index:9;display:none;'>
  <div class="cd-switch">
    <h2 style='color:white;'>Are you 21 or older?</h2>
    <div class="switch">
      <input type="radio" name="choice" id="yes" onClick="f21();">
      <label for="yes">Yes</label>
      <input type="radio" name="choice" id="no" checked>
      <label for="no">No</label>
      <span class="switchFilter"></span>
    </div>
  </div>
</div>
<script>
function f21(){
  document.getElementById('f21').remove();
  $.ajax({
    url:'bin/php/post/is21.php',
    method:'POST',
    success:function( code ){
      console.log( code );
    }
  });
}
</script>
