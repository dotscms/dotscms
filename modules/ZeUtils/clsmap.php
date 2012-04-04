<?php 
chdir(dirname(__DIR__));
/**
 * Use as: clsmap.php -p/-n Namespace /path/to/dir/
 */
global $padding;
echo "Use as: $ clsmap.php -p Prefix1 /path/to/dir/ -n Namespace2 /path/dir2/\n";

$paths = array();
for($i=1; $i<$argc;$i+=3){
    $paths[] = array(
        'separator' => ($argv[$i]=='-p'?'_':'\\'),
        'namespace' => $argv[$i+1],
        'path' => $argv[$i+2]
    );
}
$padding = 40;

$fullClassMap = array();
foreach($paths as $value){
    $path = $value['path'];
    $namespace = $value['namespace'];
    $separator = $value['separator'];

    $firstDir = substr($path, 0, strpos($path,'/'));
    $size = strlen($firstDir);
    $classMap = array();

    $classMap = getClassMap($namespace, $separator, $path, $classMap);
    foreach($classMap as $class=>$file){
        $classMap[$class] = substr($file, $size);
    }
    $fullClassMap = array_merge($fullClassMap, $classMap);
}

saveClassMap($fullClassMap, 'generated/classmap.php', "", $padding);
//exit();
//
//foreach($paths as $path){
//    if ($handle = opendir($path)) {
//        while (false !== ($file = readdir($handle))) {
//            if ((time()-filemtime($path.$file)) > 5*60*60) {  
//                if (preg_match('/\.pdf$/i', $file)) {
//                    unlink($path.$file);
//                }
//            }
//        }
//    }
//    closedir($handle);
//}

/**
 * Branch through all the php files in the path and create a reference to all the classes
 * @param string $namespace
 * @param string $path 
 * @return array
 */
function getClassMap($namespace, $separator='/',  $path='./', $classMap = array())
{
    global $padding;
    $handle = opendir($path);
    if ($handle) {
        $dirs = array();
        while (false !== ($file = readdir($handle))) {
            if (is_dir($path.$file) && $file!='.' && $file!='..'){
                $dirs[] = $file;
            }else {
                if (preg_match('/\.php$/i', $file)) {
                    $class = str_replace('.php', '', $file);
                    $strlen = strlen($namespace.$separator.$class);
                    if ($padding< $strlen){
                        $padding = $strlen;
                    }
                    $classMap[$namespace.$separator.$class] = $path.$file;
                }
            }
        }
        for($i=0;$i<count($dirs);$i++){
            $file = $dirs[$i];
            $classMap += getClassMap($namespace . $separator . str_replace(DIRECTORY_SEPARATOR, '', $file),
                    $separator, $path.$file.DIRECTORY_SEPARATOR, $classMap);
        }
        closedir($handle);
    }
    
    return $classMap;
}

/**
 * Save the provided class map as a php file
 * @param array $classMap
 * @param string $file
 * @return bool 
 */
function saveClassMap($classMap, $path = 'generated/classmap.php', $prefix="" , $padding=40)
{
$content = <<<END
<?php 
/**
 * Generated Class-File Relation Config
 */
\$prefix = dirname(__DIR__) . "$prefix";
return array(

END;
    foreach($classMap as $class=>$file){
        $content.="    " . str_pad("'$class'",$padding+3, ' ', STR_PAD_RIGHT). "=> \$prefix . '$file',\n";
    }
    $content .=");";
    file_put_contents(__DIR__ . DIRECTORY_SEPARATOR. $path, $content);
    echo "File generated successfully.\n";
    return true;
}
