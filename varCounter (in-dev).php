<?php
const PRINT_OUTPUT = True;
const THRESHOLD = 30;


/* 
VarCounterPHP tool 2023
by Bill Lugo

How to use:
Upload to server's public html directory. Run from browser.
You'll see a list of .php files and the number of variable references (including declarations). The scope includes all subdirectories.

Caution:
Since this tool lists other files in your server environment, make sure that it's not publicly linked. Consider not leaving it on the web server, but rather upload it to temporarily run it before removing. Also consider changing the name of this script to throw off any attackers.
*/


e("<div style='font-weight:bold;'>Files with too many $'s:</div><br>");

e("<table><tr style='background-color:#888;color:#eee;'><td>Path</td><td>#</td></tr>");
e("<tr>");


$it = new RecursiveDirectoryIterator(getcwd());
foreach(new RecursiveIteratorIterator($it) as $file) {
	
	switch(substr($file,-2)){
		case "..";
		case "\\.";
		break;
		
		default:
			if(strtolower(substr($file,-4))==".php"){
				e("<td>" . $file . "</td>");
				$fileHandle = fopen($file,"r");
				$DCount = countVars($fileHandle);
				e("<td>" . $DCount);
				if($DCount>THRESHOLD) e("!");
				fclose($fileHandle);
				e("</td></tr>");
			}
	}
}

echo "</table><br><br>";



//e("<table><tr style='background-color:&888'><td>Path</td><td>#</td></tr>");
//e("<tr>");


/*
foreach(glob("*", GLOB_ONLYDIR) as $d){
	processFilesInDirectory("");
    chdir($d);
	processFilesInDirectory($d);
    chdir("..");
}*/


function countVars($fileHandle){
    $count=0;
    while(!feof($fileHandle)) {
        $char = fread($fileHandle,1);
        if($char=="$") $count++; 
    }
    return $count;
}


/*
function processFilesInDirectory($directory){
	    foreach(glob("*.[Pp][Hh][Pp]") as $fileName){
		e("<td>" . getcwd() . "\\" . $fileName . "</td>");
        $fileHandle = fopen($fileName,"r");
		$DCount = countVars($fileHandle);
        e("<td>" . $DCount);
        if($DCount>THRESHOLD) e("!");
        fclose($fileHandle);
		e("</td></tr>");
    }
}*/

function e($string){
	if(PRINT_OUTPUT) echo $string;
	
	return;
}

?>