<!DOCTYPE html>
<html>
<head>
<title>Maps</title>
<style>
body { background-color: #EEE ;}

#map_canvas5 {
	-moz-box-shadow: 10px 10px 5px #888;
	-webkit-box-shadow: 10px 10px 5px #888;
	box-shadow: 10px 10px 5px #888;
}
</style>
</head>

<?php
	$dt=md5(time());
	$file=(isset($_GET['file']))?basename($_GET['file']):NULL;
	$proto="http";
	$url="veille.lbn.fr";
	$uri=preg_replace('/gmap/','kml',$_SERVER["REQUEST_URI"]);
	$kml="$proto://$url$uri&$dt";
?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>

<script type="text/javascript">
function init()
{
	var quebec= new google.maps.LatLng(0,0);
	var myOptions={zoom:2,center:quebec,mapTypeId:google.maps.MapTypeId.ROADMAP}
	var map = new google.maps.Map(document.getElementById("map_canvas5"), myOptions);
	var ctaLayer = new google.maps.KmlLayer('<?=$kml?>',{preserveViewport:true});
	ctaLayer.setMap(map);

	google.maps.event.addListener(ctaLayer, 'status_changed', function () {
        console.log('KML load: ' + ctaLayer.getStatus());
        if (ctaLayer.getStatus() != 'OK') {
                alert('[' + ctaLayer.getStatus() + '] Google Maps could not load the layer. Please try again later.');
            }         });

}
</script>

<body onload="init()">

<div id="map_canvas5" style="width: 1000px; height: 600px; background-color: green; margin: 0px auto 0px auto;">map</div>

<div style='width:1000px; margin: 10px auto 0px auto; font-family: Arial,Helvetica; text-align: center; font-size: 80%;'>
<a href="index.php"> Back </a> --
<a href="kml.php?file=<?=$file?>"> Kml </a> --
<a href="gfile.php?file=<?=$file?>"> Asnum.csv </a> --
<a href="gfile.php?file=<?=basename($file,".csv")."-full.csv"; ?>"> full.csv </a>
</div>

<div >
<ul>
<li>vert    > cela représente moins de 75% des requêtes malicieuses</li>
<li>Jaune > cela représente entre de 75%-80% des requêtes malicieuses</li>
<li>rouge > cela représente plus de 80% des requêtes malicieuses.</li>
</ul>

</div>

<div id=maps-error></div>

</body>
</html>
