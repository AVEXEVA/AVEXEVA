<?php 
session_start();
if( ( isset( $_POST ) && count( $_POST ) > 0 ) || isset( $_SESSION[ 'Username' ] ) ){
    $_SESSION[ 'Username' ] = isset( $_SESSION[ 'Username' ] ) ? $_SESSION[ 'Username' ] : $_POST[ 'Username' ];
    echo $_SESSION[ 'Username' ];
	?><script>document.location.href='index.php';</script><?php
} else {
?><html>
<head>
	<title>Twisted</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link href='https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<style>
body {
    background: #222D32;
    font-family: 'Roboto', sans-serif;
}
 
.login-box {
    margin-top: 75px;
    height: auto;
    background: #1A2226;
    text-align: center;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
}
 
.login-key {
    height: 100px;
    font-size: 80px;
    line-height: 100px;
    background: -webkit-linear-gradient(#27EF9F, #0DB8DE);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
 
.login-title {
    margin-top: 15px;
    text-align: center;
    font-size: 30px;
    letter-spacing: 2px;
    margin-top: 15px;
    font-weight: bold;
    color: #ECF0F5;
}
 
.login-form {
    margin-top: 25px;
    text-align: left;
}
 
input[type=text] {
    background-color: #1A2226;
    border: none;
    border-bottom: 2px solid #0DB8DE;
    border-top: 0px;
    border-radius: 0px;
    font-weight: bold;
    outline: 0;
    margin-bottom: 20px;
    padding-left: 0px;
    color: #ECF0F5;
}
 
input[type=password] {
    background-color: #1A2226;
    border: none;
    border-bottom: 2px solid #0DB8DE;
    border-top: 0px;
    border-radius: 0px;
    font-weight: bold;
    outline: 0;
    padding-left: 0px;
    margin-bottom: 20px;
    color: #ECF0F5;
}
 
.form-group {
    margin-bottom: 40px;
    outline: 0px;
}
 
.form-control:focus {
    border-color: inherit;
    -webkit-box-shadow: none;
    box-shadow: none;
    border-bottom: 2px solid #0DB8DE;
    outline: 0;
    background-color: #1A2226;
    color: #ECF0F5;
}
 
input:focus {
    outline: none;
    box-shadow: 0 0 0;
}
 
label {
    margin-bottom: 0px;
}
 
.form-control-label {
    font-size: 10px;
    color: #6C6C6C;
    font-weight: bold;
    letter-spacing: 1px;
}
 
.btn-outline-primary {
    border-color: #0DB8DE;
    color: #0DB8DE;
    border-radius: 0px;
    font-weight: bold;
    letter-spacing: 1px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
}
 
.btn-outline-primary:hover {
    background-color: #0DB8DE;
    right: 0px;
}
 
.login-btm {
    float: left;
}
 
.login-button {
    padding-right: 0px;
    text-align: right;
    margin-bottom: 25px;
}
 
.login-text {
    text-align: left;
    padding-left: 0px;
    color: #A2A4A4;
}
 
.loginbttm {
    padding: 0px;    
}
button.btn {
    width:100%;
}
</style>
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-2"></div>
            <div class="col-lg-6 col-md-8 login-box">
                <div class="col-lg-12 login-title">
                    <!--<i class="fa fa-key" aria-hidden="true"></i>-->
                    <img src='https://de6nrc5r8j-flywheel.netdna-ssl.com/wp-content/uploads/2018/06/twisted-extracts-logo.png' />
                </div>
                <div class="col-lg-12 login-form">
                    <div class="col-lg-12 login-form">
                        <form action='login.php' method='POST'>
                            <div class="form-group">
                                <label class="form-control-label">USERNAME</label>
                                <input name='Username' type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">PASSWORD</label>
                                <input name='Password' type="password" class="form-control" i>
                            </div>
                            <div class="col-lg-12 loginbttm">
                                <div class="col-lg-6 login-btm login-button">
                                    <button type="submit" class="btn btn-outline-primary">LOGIN</button>
                                </div>
                                <div class="col-lg-6 login-btm login-text">
                                    <button type="button" class="btn btn-outline-primary">FORGOT PASSWORD</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2"></div>
            </div>
        </div>
</body>
</html>
<?php }?>