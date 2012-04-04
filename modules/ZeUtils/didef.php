<?php
require_once __DIR__ . '/../../public/bootstrap.php';
$DIR = __DIR__;

// in "package name" format
unset($argv[0]);
$components = $argv;

foreach ($components as $component) {
    $namespaces = explode('\\', $component);
    $module = $namespaces[0];
    $diCompiler = new Zend\Di\Definition\CompilerDefinition;
    $diCompiler->addDirectory($DIR .'/../'.$module.'/src/' . str_replace('\\', '/', $component));

    $diCompiler->compile();
    $classes = $diCompiler->toArrayDefinition()->toArray();
    $definitions = array();
    ob_start();
    echo <<<END
<?php
return array(
    'di' => array(
        'definition' => array(
            'class' => array(

END;

            foreach($classes as $kclass =>$class){
                $clsName = addslashes($kclass);
                echo <<<END
                '$clsName' => array(

END;
                $parameters = array();
                foreach( $class['parameters'] as $kparams => $params){
                    $methodName = addslashes($kparams);
                    echo <<<END
                    '$methodName' => array(

END;

                    $param = array();
                    foreach ($params as $k=>$v) {
                        $prmName = addslashes($v[0]);
                        $prmType = ($v[1]?"'".addslashes($v[1])."'":'null');
                        $prmReq = ($v[2]?'true':'false');
                        echo <<<END
                        '$prmName' => array(
                            'type' => $prmType,
                            'required' => $prmReq,
                        ),

END;
                        $param[$v[0]] = array(
                            'type'=> $v[1],
                            'required'=> $v[2],
                        );
                    }
                    $parameters[$kparams] = $param;
                    echo <<<END
                    ),

END;

                }
        //        $definition['parameters'] = $parameters;
                $definitions[$kclass] = $parameters;
                echo <<<END
                ),

END;
            }
    echo <<<END
            ),
        ),
    ),
);
END;

    $contents = ob_get_contents();
    ob_clean();
    file_put_contents(
        __DIR__ . '/../'.$module.'/config/' . 'module.di.config.php',
        $contents
//        '<?php return ' . var_export(array(
//                'di'=>array(
//                    'definition'=>array(
//                        'class'=> $definitions
//                    )
//                )
//            ), true
//        ) . ';'
    );
}