<?php

echo "Files with too many $'s:<br>";

foreach(glob("*", GLOB_ONLYDIR) as $d){
    chdir($d);
    
 
 
 
    foreach(glob("*.php") as $fileName){
        $fileHandle = fopen($fileName,"r");
        $path = substr(getcwd(),30);
        //echo "# of $'s in ". $fileName .": ". countVars($fileHandle) ." at: ". $path ."<br>";
        $DCount = countVars($fileHandle);
        if($DCount>36) echo $path ."\\". $fileName .": ". $DCount ."<br>";
        fclose($fileHandle);
    }
    
    chdir("..");
}





function countVars($fileHandle){
    $count=0;
    while(!feof($fileHandle)) {
        $char = fread($fileHandle,1);
        if($char=="$") $count++; 
    }
    return $count;
}
?>