<?PHP
SESSION_START();
$_GET['Folder'] = $_GET['Folder'] == 'a' ? 'a/' : $_GET['Folder'];
$_GET['Folder'] = $_GET['Folder'] == 'v' ? 'v/' : $_GET['Folder'];
if(isset($_GET['File'])){if(strpos($_GET['File'], '.') && strpos($_GET['File'], '.') < strrpos($_GET['File'], '/')){unset($_GET['File']);}}
if(isset($_GET['Image'])){if(strpos($_GET['Image'], '.') && strpos($_GET['Image'], '.') < strrpos($_GET['Image'], '/')){unset($_GET['Image']);}}
if(isset($_GET['Folder'])){if(strpos($_GET['Folder'], '.')){unset($_GET['Folder']);}}
REQUIRE('a/XIO/Language/PHP/INDEX.php');
?>
