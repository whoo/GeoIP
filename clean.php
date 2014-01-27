<?
$file=glob('temp/ip-*');

foreach($file as $a)
{
	if (filesize($a)==0) @unlink($a);
	if (is_file($a))
	if ((fileatime($a) + 3600 ) <  time() )  @unlink($a);
}

?>
