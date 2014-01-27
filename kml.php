<?php
header('Content-type: application/vnd.google-earth.kml+xml');
header('Content-Disposition: attachment; filename="export.kml"');
#header('Content-type: text/plain');

$TPL="<Placemark id='@IP@'>
	<name>@NAME@</name>
	<styleUrl>#@STYLE@</styleUrl>
	<description>@NAME@</description>
		<Point>
			<coordinates>@LNG@,@LAT@,0</coordinates>
		</Point>
</Placemark>
";


$TTPL="<kml xmlns='http://www.opengis.net/kml/2.2'>
<Document>
<Style id='red'> <IconStyle> <Icon> <href>http://maps.google.com/mapfiles/kml/paddle/red-blank.png</href> </Icon> </IconStyle> </Style>
<Style id='green'> <IconStyle> <Icon> <href>http://maps.google.com/mapfiles/kml/paddle/grn-blank.png</href> </Icon> </IconStyle> </Style>
<Style id='orange'><IconStyle><Icon><href>http://maps.google.com/mapfiles/kml/paddle/ylw-blank.png</href></Icon></IconStyle></Style>

<Folder>
<name>Data Scan </name>
<description>Data from Scan
</description>
@COORD@
</Folder>
</Document>
</kml>

";


$file=(isset($_GET['file']))?"temp/".basename($_GET['file']):NULL;

#$file="temp/ip-2Inr8g.csv";
if (!is_file($file)) { echo "error $file"; exit;}
$f= file($file);

$output="";


$max=0;
foreach($f as $lst)
{
	$ln[] = explode(';',$lst);
	list($ip,$lng,$lat,$asname,$count)=explode(';',$lst);
	$stats[]=(int)$count;
}

sort($stats);
#print_r($stats);
#print "max : ".max($stats);
#print "min : ".min($stats);
#print "mean; ".array_sum($stats)/count($stats);
#print "\n";
$p70= $stats[round((0.75*count($stats)))-1];
$p80= $stats[round((0.8*count($stats)))-1];


#print_r($ln);

foreach($ln as $a => $lst)
{
list($ip,$lng,$lat,$asname,$count)=$lst;
$out=$TPL;
$out=preg_replace('/@LNG@/',chop($lng),$out);
$out=preg_replace('/@IP@/',chop($ip),$out);
$out=preg_replace('/@LAT@/',chop($lat),$out);
$out=preg_replace('/@NAME@/',htmlspecialchars($asname)." (".chop($count)." hits)",$out);

if ((int)$count < $p70)
	$out=preg_replace('/@STYLE@/',"green",$out);
	elseif ((int)$count <= $p80)
	$out=preg_replace('/@STYLE@/',"orange",$out);
	else $out=preg_replace('/@STYLE@/',"red",$out);
$output.=$out;
}


$output= preg_replace('/@COORD@/',$output,$TTPL);
#file_put_contents("$file.kml",$output);
echo $output;
#readfile('Poutine.kml');

?>
