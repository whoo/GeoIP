<?
@apache_setenv('no-gzip', 1);
@ini_set('zlib.output_compression', 0);
@ini_set('output_buffering',0);
require('pass.php');
?>
<!DOCTYPE html>
<html>
<head>
</head>
<style>
body { background-color: #EEE;  font-family: Arial,Helvetica;}
#res {height:400px; width:600px; overflow:hidden; margin-bottom: 10px;color:white; background-color:black; font-family: monospace;}
#ff{ height:400px; width: 600px;}
#com {width:600px; text-align:center; }
div {
margin: 10px auto 0 100px;
-moz-box-shadow: 10px 10px 5px #888;
-webkit-box-shadow: 10px 10px 5px #888;
box-shadow: 10px 10px 5px #888;
}
input { width: 80%; }
textarea { width: 90%; height: 350px;}
</style>
<body>
<h1>Ip 2 Info</h1>
<?
$mys = new mysqli($host,$root,$pass,'geoip');

if ($mys->connect_errno) {
	printf("Échec de la connexion : %s\n", $mys->connect_error);
	exit();
}

if ($_POST)
{
	# PADDING 
	echo str_repeat(" ", 1024);
	echo "\n<div id=res></div>";
	flush();
	ob_flush();
	###########
	$tb= preg_split("/[[:space:]]+/",$_POST['iplist']);
	$tbc=array_count_values($tb);
	$tb=array_unique($tb);
	$cvsf=$cvsm="";
	$cvsf="ip;Cc;Ville;lng;lat;As;count:\n";
	foreach($tb as $ip)
	{
		$query="call geoip.GetLoc('$ip');";
		$result = $mys->query($query);
		if ($result)
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$cvsf.= sprintf("%s;%s;%s;%s;%s;%s;%s\n",$ip,$row['country'],$row['city'],$row['longitude'],$row['latitude'],$row['ASnum'],$tbc[$ip]);
			$cvsm.= sprintf("%s;%s;%s;%s;%s\n",$ip,$row['longitude'],$row['latitude'],$row['ASnum'],$tbc[$ip]);
			preg_match('/(AS\d*) /',$row['ASnum'],$asnum);
			$AS=$asnum[1];
			echo "<script>";
			echo "document.getElementById('res').innerHTML+='Check : $ip';\n";	
			echo "document.getElementById('res').innerHTML+=' ".$row['country']." $AS <br>';\n";
			echo "document.getElementById('res').scrollTop =document.getElementById('res').scrollHeight;\n";
			echo "</script>\n";
			flush(); ob_flush();
			mysqli_free_result($result);   
			$mys->next_result();
		}


	}
	$name=tempnam('temp/','ip-');
	$name=basename($name);
	file_put_contents("temp/$name-full.csv",$cvsf);
	file_put_contents("temp/$name.csv",$cvsm);

	echo "<div id=com>";
	echo "<a href=gmap.php?file=$name.csv>Google Map </a>	--";
	echo "<a href=gfile.php?file=$name.csv>View AS </a> --";
	echo "<a href=gfile.php?file=$name-full.csv>View Full </a>	<br>";
	echo "</div>";
}
else {
include('clean.php');
	echo "<div id=ff><form method=POST>
		<textarea name=iplist >
217.19.48.80
8.8.8.8
</textarea>
		<input type=submit>
		</div>
		</form>";
}
?>
</body>
</html>
