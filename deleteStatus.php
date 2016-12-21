<?php
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") 
                    rrmdir($dir."/".$object); 
                else unlink   ($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
session_start();
if( !isset($_SESSION['game']))
    die("No session");
//array_map('rmdir', glob("status/".$_SESSION['game']."/*.sts"));
$fileName = "status/".$_SESSION['game'];
//echo $dirName;
rrmdir($fileName);
unset($_SESSION['game']);
?>