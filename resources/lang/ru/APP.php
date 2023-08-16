<?php
$trans = parse_ini_file(dirname(__FILE__).'/app.ini');
$file = dirname(__FILE__).'/override.ini';
if (file_exists($file)) {
    $override = parse_ini_file($file);
    $trans = array_merge($trans, $override);
}
return $trans;