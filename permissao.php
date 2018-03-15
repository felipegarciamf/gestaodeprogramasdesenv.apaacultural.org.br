<?php

header('Content-Type: text/plain');

/**
* Changes permissions on files and directories within $dir and dives recursively
* into found subdirectories.
*/
function chmod_r($dir)
{
    $dp = opendir($dir);
    while($file = readdir($dp))
    {
        if (($file == ".") || ($file == "..")) continue;

        $path = $dir . "/" . $file;
        $is_dir = is_dir($path);

        set_perms($path, $is_dir);
        if($is_dir) chmod_r($path);
    }
    closedir($dp);
}

function set_perms($file, $is_dir)
{
    $perm = substr(sprintf("%o", fileperms($file)), -4);
    $dirPermissions = "0755";
    $filePermissions = "0644";

    if($is_dir && $perm != $dirPermissions)
    {
        echo("Dir: " . $file . "\n");
        chmod($file, octdec($dirPermissions));
    }
    else if(!$is_dir && $perm != $filePermissions)
    {
        echo("File: " . $file . "\n");
        chmod($file, octdec($filePermissions));
    }

    flush();
}

chmod_r(dirname(__FILE__));

?>
