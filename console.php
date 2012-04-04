<?php
global $argv, $argc;
$type = explode(':', $argv[1]);
$section = $type[0];
$command = $type[1];
$args = array($argv[0]);
for($i=2;$i<$argc; $i++){
    $args[]=$argv[$i];
}
$argv = $args;
$argc = count($argv);
switch ($section){
    case 'module':
        switch ($command){
            case 'classmap':
                require __DIR__ .'/core/ZeUtils/clsmap.php';
                break;
            case 'definitions':
                require __DIR__ .'/core/ZeUtils/didef.php';
                break;
            case 'pack';
                require __DIR__ .'/core/ZeUtils/phar.php';
                break;
        }
        break;
    break;
}