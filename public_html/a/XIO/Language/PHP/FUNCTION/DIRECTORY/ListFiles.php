<?php
function ListFiles($f, $page = 'index'){
	if(isset($_GET['Folder']) && substr($_GET['Folder'], 0, 1) == $f){
		ListFiles($_GET['Folder']);
	} else {
		if(isset($_GET['File']) && strrpos($_GET['File'], '/') > 0 && strlen(substr($_GET['File'], 0, strrpos($_GET['File'], '/'))) > 0 && substr($_GET['File'], 0, strrpos($_GET['File'], '/')) != substr($_GET['File'], 0, strrpos($_GET['File'], '/'))){
			ListFiles(substr($_GET['File'], 0, strrpos($_GET['File'], '/')));
		} else {
			?><LI Class='Parent'><a href='<?php echo $page;?>.php?Folder=<?php echo substr($f, 0, strrpos($f, '/'));?>'><?php echo substr($f, strrpos($f, '/'));?></a></LI><?php
			foreach(scandir($f) as $fileordirectory){
				if(in_array($f . '/' . $fileordirectory, array('a/X', 'a/t'))){continue;}
				if($fileordirectory == '..'){continue;}
				if($fileordirectory == '.'){continue;}
				if(is_dir($f . "/" . $fileordirectory)){
					if($fileordirectory == 'Love'){continue;}
					//ListFiles($f . "/" . $fileordirectory);
					?><LI Class='Folder'><a href='<?php echo$page;?>.php?Folder=<?php echo htmlspecialchars(str_replace('//', '/', $f)) . "/" . str_replace('//', '/', $fileordirectory);?>'><?php echo $fileordirectory;?></a></LI><?php
				}
				else {if(filesize($f . '/' . $fileordirectory) > 0){?><LI Class='File'><a href="<?php echo $page;?>.php?File=<?php echo htmlspecialchars(str_replace('//', '/', $f) . "/" . $fileordirectory);?>"><?php echo $fileordirectory;?></a></LI><?php }}
			}
		}
	}
}
?>