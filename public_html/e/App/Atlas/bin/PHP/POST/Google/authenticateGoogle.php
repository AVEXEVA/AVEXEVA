<?php
session_start();
require('../index.php');
//require('../Google/autoload.php');
if(isset($_SESSION['User'],$_SESSION['Hash'])){
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Connection WHERE Connector = ? AND Hash = ?;",array($_SESSION['User'],$_SESSION['Hash']));
  $array = sqlsrv_fetch_array($r);
  $Privileged = FALSE;
  $r = sqlsrv_query($NEI,"SELECT * FROM nei.dbo.Emp WHERE ID = ?",array($_SESSION['User']));
  $My_User = sqlsrv_fetch_array($r);
  $Field = ($User['Field'] == 1 && $User['Title'] != "OFFICE") ? True : False;
  $r = sqlsrv_query($Portal,"
      SELECT Access_Table, User_Privilege, Group_Privilege, Other_Privilege
      FROM   Portal.dbo.Privilege
      WHERE  User_ID = ?
  ;",array($_SESSION['User']));
  $My_Privileges = array();
  while($array2 = sqlsrv_fetch_array($r)){$My_Privileges[$array2['Access_Table']] = $array2;}
  $Privileged = FALSE;
  if(isset($My_Privileges['Code']) && $My_Privileges['Code']['Other_Privilege'] >= 4){$Privileged = TRUE;}
  if(!$Privileged || count($_GET) == 0){?><html><head><script>document.location.href='../login.php';</script></head></html><?php }
  else {


$GAPIS = 'https://www.googleapis.com/';
$GAPIS_AUTH = $GAPIS . 'auth/';
$GOAUTH = 'https://accounts.google.com/o/oauth2/';
$REDIRECT_URI = 'http' . ($_SERVER['SERVER_PORT'] == 80 ? '' : 's') . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['SCRIPT_NAME'];

// specific to this app
$CLIENT_ID = '537233097283-brun3p868lmrgj5b2sjprhqoe596h22o.apps.googleusercontent.com';   // expecting *.apps.googleusercontent.com
$CLIENT_SECRET = 'xCGfxptiECCz8zAoxh6gWhUy';   // expecting alphanumeric string
$STORE_PATH = '../../../../../token.json';   // expecting *.json - needs to be writeable by system account
$SCOPES = array($GAPIS_AUTH . 'drive');  // add in your other scopes as needed
function generateMessageID()
{
  return sprintf(
    "<%s.%s@%s>",
    base_convert(microtime(), 10, 36),
    base_convert(bin2hex(openssl_random_pseudo_bytes(8)), 16, 36),
    $_SERVER['SERVER_NAME']
  );
}
// retrieve from credentials file
function getStoredCredentials($path) {
    $credentials = json_decode(file_get_contents($path), true);
    $expire_date = new DateTime();
    $current_time = new DateTime();
    $expire_date->setTimestamp($credentials['created']-300);
    $expire_date->add(new DateInterval('PT' . $credentials['expires_in'] . 'S'));
    if ($current_time->getTimestamp() >= $expire_date->getTimestamp()) {
        $credentials = null;
        unlink($path);
    }
    return $credentials;
}

// store new credentials in file
function storeCredentials($path, $credentials) {
    $credentials['created'] = (new DateTime())->getTimestamp(); file_put_contents($path, json_encode($credentials));
    return $credentials;
}

// get authorization code
function requestAuthCode() {
    global $GOAUTH, $CLIENT_ID, $REDIRECT_URI, $SCOPES;
    $url = sprintf($GOAUTH . 'auth?scope=%s&redirect_uri=%s&response_type=code&client_id=%s&approval_prompt=force&access_type=offline', urlencode(implode(' ', $SCOPES)), urlencode($REDIRECT_URI), urlencode($CLIENT_ID) );
    header('Location:' . $url);
    $_SESSION['Deficiencies'] = $_GET['Deficiencies'];
    exit();
}

// request access token
function requestAccessToken($access_code) {
    global $GAPIS, $CLIENT_ID, $CLIENT_SECRET, $REDIRECT_URI;
    $url = $GAPIS . 'oauth2/v4/token';
    $post_fields = 'code=' . $access_code . '&client_id=' . urlencode($CLIENT_ID) . '&client_secret=' . urlencode($CLIENT_SECRET) . '&redirect_uri=' . urlencode($REDIRECT_URI) . '&grant_type=authorization_code';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response=curl_exec($ch);
    if ($response === false) {
        return curl_error($ch);
    } else {
        return json_decode($response, true);
    }
    curl_close($ch);
}

// get token (access or refresh)
function getAccessToken($credentials) {
    $expire_date = new DateTime();
    $expire_date->setTimestamp($credentials['created']);
    $expire_date->add(new DateInterval('PT' . $credentials['expires_in'] . 'S'));
    $current_time = new DateTime();
    if ($current_time->getTimestamp() >= $expire_date->getTimestamp())
        return $credentials['refresh_token'];
    else
        return $credentials['access_token'];
}

// manage tokens
function authenticate() {
    global $STORE_PATH;
    $credentials = (file_exists($STORE_PATH)) ? getStoredCredentials($STORE_PATH) : null;
    if (!(isset($_GET['code']) || isset($credentials))) requestAuthCode();
    if (!isset($credentials)) $credentials = requestAccessToken($_GET['code']);
    if (isset($credentials) && isset($credentials['access_token']) && !file_exists($STORE_PATH)) $credentials = storeCredentials($STORE_PATH, $credentials);
    return $credentials;
}
$credentials = authenticate();
if(headers_sent()){
  $_SESSION['Deficiencies'] = $_GET['Deficiencies'];
  exit;
} else {
  echo 'TRUE';
}

}}
?>
