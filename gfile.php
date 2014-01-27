<?php
$file=(isset($_GET['file']))?"temp/".basename($_GET['file']):NULL;
#if (!is_file($file)) exit;


$f=(preg_match('/full/',$file))?"-full":NULL;
header('Content-Type: text/csv');
header("Content-Disposition: attachment; filename='Ip-rapport$f.csv'");

readfile($file);

?>
