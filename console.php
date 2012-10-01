<?php
global $argv, $argc;
$type = explode(':', $argv[1]);
$section = $type[0];

switch ($section){
    case 'module':
        $command = $type[1];
        $args = array($argv[0]);
        for($i=2;$i<$argc; $i++){
            $args[]=$argv[$i];
        }
        $argv = $args;
        $argc = count($argv);

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
    default:
        echo <<<END
Use as:
- Generate class map:
    > php console.php module:classmap -p/n Namespace path/to/root/
- Generate class definitions:
    > php console.php module:definitions Namespace[\Component]
- Pack a module as a phar archive:
    > php console.php module:pack Namespace

END;
    break;
}