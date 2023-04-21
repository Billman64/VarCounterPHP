<?php
const THRESHOLD = 30;
const PRINT_OUTPUT = True;
const EMAIL_OUTPUT = True;
const EMAIL = "";
const CC = "";
const EXT = "php";	// lowercase only
const SEARCH_SYMBOL = "$";	// alternative: "="
const FILTER_ON = True;
const ONLY_OVER_THRESHOLD = False;


/* 
VarCounterPHP tool 2023
by Bill Lugo

How to use:
Upload to server's public html directory. Run from browser.
You'll see a list of .php files and the number of variable references (including declarations). The scope includes all subdirectories.

Caution:
Since this tool lists other files in your server environment, make sure that it's not publicly linked. Consider not leaving it on the web server, but rather upload it to temporarily run it before removing. Also consider changing the name of this script to throw off any attackers.
*/

class FileListing {
	public $filePath="";
	public $numSymbols=0;
}

/*
$a = new FileListing;
$a->filePath = "asdf";
$a->numSymbols = 2;
echo "aaaaa " . $a->filePath ." ". $a->numSymbols; */


$emailMsg="";
e("<div style='font-weight:bold;'>Files with too many ". SEARCH_SYMBOL ."'s (over ". THRESHOLD ." ):</div><br>");
e("<table><tr style='background-color:#888;color:#eee;'><td>Path\\file</td><td># ". SEARCH_SYMBOL ."'s</td></tr>");


$fileCounter=0;
$thresholdFileCounter=0;
$fl = new FileListing;
$overThresholdListings = array();
$otherListings = array();
$it = new RecursiveDirectoryIterator(getcwd());
foreach(new RecursiveIteratorIterator($it) as $file) {
	
	switch(substr($file,-2)){
		case "..";
		case "\\.";
		break;
		
		default:
			if(strtolower(substr($file,-4))=="." . EXT){
				$fileCounter++;
				
				$posLastSlash = strripos(getcwd(), "/");
				if($posLastSlash==False) $posLastSlash = strripos(getcwd(), "\\");
				
				
				$fileDisplay = substr($file, $posLastSlash, strlen($file) - $posLastSlash);
				
				$fileHandle = fopen($file,"r");
				$DCount = countVars($fileHandle);
				fclose($fileHandle);
				$fl->filePath = $fileDisplay;
				$fl->numSymbols = $DCount;
				
				if($DCount>THRESHOLD){
					$thresholdFileCounter++;
					array_push($overThresholdListings, $fl);
					e("<tr>");
					e("<td>" . $fl->filePath . "</td>");
					e("<td>" . $fl->numSymbols);
					e("</td></tr>");
				} else {
					array_push($otherListings, $fl);
					//e("<tr><td>" . $otherListings[count($otherListings)-1]->filePath ."</td></tr>");
				}
			}
	}
}
e("<tr><td style='text-align:right;'>Files over threshold: " . $thresholdFileCounter . "</td></tr>");
e("</table><br>");




e("aaa ". $otherListings[2]->filePath . " count: ". count($otherListings));
e("<table style='color:#777;'><tr style='background-color:#888;color:#eee;'><td>Path\\file</td><td># ". SEARCH_SYMBOL ."'s</td></tr>");

foreach($otherListings as $key=>$value){
	/*
	e("<tr><td>" . $f->filePath . "</td>");
	e("<td>" . $f->numSymbols . "</td></tr>");*/
	e("<tr><td>");
	e($value->filePath);
	e("</td></tr>");
}
e("<tr><td style='text-align:right;'>Total files scanned: " . $fileCounter . "</td></tr></table>");

echo "<br><br>";
$i=0;
for($i=0; $i<count($otherListings); $i++){
	e($otherListings[i]->filePath);
	
}



//echo("email msg<br>" . $emailMsg);	//testing

function countVars($fileHandle){
    $count=0;
    while(!feof($fileHandle)) {
        $char = fread($fileHandle,1);
        if($char=="$") $count++; 
    }
    return $count;
}

function e($string){
	if(PRINT_OUTPUT) echo $string;
	if(EMAIL_OUTPUT) {
		global $emailMsg;
		$emailMsg .= $string;
	}
	return;
}

?>