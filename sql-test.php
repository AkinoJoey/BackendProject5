<?php

spl_autoload('.php');
spl_autoload_register();

$dir = 'Database/DMLTests/';
$filename = $argv[1];

if(file_exists($dir . $filename)){
    include($dir.$filename);
    print(sprintf('%sの実行が完了しました。', $filename));
}else{
    throw new Exception("ファイルが存在しません。");
}