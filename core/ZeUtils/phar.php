<?php
$name = $argv[1];
$phar = new Phar( __DIR__ . '../'.$name.'.phar');
$phar->buildFromDirectory('../'.$name.'/');
$phar->compressFiles( Phar::GZ );
$phar->stopBuffering();
