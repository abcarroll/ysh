#!/usr/bin/env php
<?php
$targetDir = "/usr/local/bin";
$targetCmd = "ysh";

$targetFile = realpath($targetDir) . '/' . $targetCmd;

$myPath = realpath(__FILE__);
$myPath = dirname($myPath);
$myPath .= '/ysh.php';

echo "This will attempt to create a symlink: $targetFile\n";
echo "to ysh.php ... Are you sure?  (y or n): ";
$a = trim(strtolower(fgets(STDIN)));
echo "\n";
if(substr($a, 0, 1) == 'y') {
    if(file_exists($targetFile)) {
        echo "Target already exists ($targetFile)!\n";
    } elseif(!is_writable($targetDir)) {
        echo "Unable to write target ($targetFile)\n";
    } else {
        if(!symlink($myPath, $targetFile)) {
            echo "Unable to create symlink ($targetFile)!\n";
        } else {
            echo "Successfully created symlink.\n";
        }
    }
} else {
    echo "Aborted.\n";
}