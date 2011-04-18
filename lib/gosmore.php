<?php
ini_set ("memory_limit", "64M");
$output = "";

// Allowed parameters
$layers = array("mapnik", "cn", "test");
$formats = array("kml", "geojson");

$script_start = microtime(true);

$www_dir = '/home/nroets/public_html/demo';

$user_agent = $_SERVER['HTTP_USER_AGENT'];

$query = "QUERY_STRING='";
//echo "Content-Type: text/plain\n\nDebug\n";
//Coordinates
if (isset($_GET['flat']) && is_numeric($_GET['flat'])) {
	$query .= 'flat='.$_GET['flat'];
	$flat = $_GET['flat'];
}
else {	
	$query .= 'flat=53.04821';
	$flat = 53.04821;
}

if (isset($_GET['flon']) && is_numeric($_GET['flon'])) {
	$query .= '&flon='.$_GET['flon'];
	$flon = $_GET['flon'];
}
else {
	$query .= '&flon=5.65922';
	$flon = '5.65922';
}

if (isset($_GET['tlat']) && is_numeric($_GET['tlat'])) {
	$query .= '&tlat='.$_GET['tlat'];
        $tlat = $_GET['tlat'];
}
else {
	$query .= '&tlat=53.02616';
}

if (isset($_GET['tlon']) && is_numeric($_GET['tlon'])) {
	$query .= '&tlon='.$_GET['tlon'];
	$tlon = $_GET['tlon'];
}
else {
	$query .= '&tlon=5.66875';
	$tlon = '5.66875';
}

//Fastest/shortest route
if (isset($_GET['fast']) && is_numeric($_GET['fast'])) {
	$fast = $_GET['fast'];
}
else if (isset($_GET['short']) && is_numeric($_GET['short'])) {
	if ($_GET['short'] == '1') {
		$fast = '0';
	}
}
else {
	$fast = '1';
}
$query .= '&fast='. $fast;

//Transportation
if (isset($_GET['v'])) $query .= '&v='.$_GET['v'];

//Map layer
$layer = 'mapnik';
if (isset($_GET['layer']) && in_array($_GET['layer'], $layers)) {
	$layer = $_GET['layer'];
}

// Query result return format
$format = 'kml';
if (isset($_GET['format']) && in_array($_GET['format'], $formats)) {
	$format = $_GET['format'];
}

$lang = 'en';
if (isset($_GET['lang']) && preg_match ('[^a-zA-Z_-]', $_GET['lang']) == 0) {
  $lang = $_GET['lang'];
}

$query .= "'";

// Geographic pak file selection
if ($flon < -30) {
	// American continents (North and South)
	$pak = '/home/nroets/gosmore/america/gosmore.pak';
} else {
	// Europe, Asia, Africa and Oceania continents
	$pak = '/home/nroets/gosmore/europe/gosmore.pak';
}
$dir = '/home/nroets/gosmore/';
$command = $query." ./gosmore ".$pak. " 2>/dev/null | sed 'y/\"/_/' | ".
  "(cd ../routing-instructions/src/tools/translations; LANG=".$lang." ../../../build/src/routing-instructions/routing-instructions --dense 2>/dev/null)";
#  "LANG=en_GB.utf8 ../gosmore-instructions/gosmore-instructions --dense 2>/dev/null";

$res = chdir($dir);
$gosmore_start = microtime(true);
$result = exec($command, $output);
//echo "Content-Type: text/plain\n\n".$command; //$output[0],$output[1].$output[2];
//exit (0);
$gosmore_end = microtime(true);

$nodes = 0;
$instructions = "";
if (count($output) > 1)
{	// Loop through all the coordinates
	// $flat = $flon = 360.0;
	$distance = 0;
	$elements = array();
	$lineEnd = $format == "kml" ? "&lt;br&gt;\n" : "<br>";
	$node = array ();
	$seconds = 0;
	foreach ($output as $line)
	{
		if (count($node) > 4 && $node[5] != '') $instructions .= $node[5].$lineEnd;
		// We add node[5] to the instructions here in order to suppress the last
		// instruction, which is pretty pointless (Continue on fini).
		$node = split(",", trim ($line, "\n\r"));
/*                if (count($node) > 3 && $nodes == 0) {
		// The first entry only exists to indicate the first street name. Replace it with given flat,flon.
			$element = array("lat" => $flat, "lon" => $flon, "junction" => 'j', "name" => '');
			array_push($elements, $element);
			$nodes++;
		}
		else*/ if (count($node) > 3)
		{
			if ($time == 0 && $fast == '1') {
				$time = $node[4];
				$instructions .= floor($time / 60) . 'm '
				  . ($time - floor($time / 60) * 60) . 's' . $lineEnd;
			}
			$element = array("lat" => $node[0],
			                 "lon" => $node[1],
			                 "junction" => $node[2],
			                 "name" => $node[3]);
			array_push($elements, $element);
			
			if ($flat < 360)
			{
				$distance += getDistance($flat, $flon, $node[0], $node[1]);
			}
			$flat = $node[0];
			$flon = $node[1];
			$nodes++;
		}
	}
/*	$distance += getDistance($flat, $flon, $tlat, $tlon);
	$element = array("lat" => $tlat, "lon" => $tlon, "junction" => 'j', "name" => '');
	array_push($elements, $element);
	$nodes++;*/
	
	// Convert the returned coordinates to the requested output format
	switch ($format) {
	case 'kml':
		$output = asKML($elements, $distance, $instructions);
		break;
	case 'geojson':
		$output = asGeoJSON($elements, $distance, $instructions);
		break;
	default:
		$output = "unrecognised output format given";
	}
}

if ($nodes == 0) 
{
	if (count($output) > 1)
	{
		if (strcmp($output[2], 'No route found')) {
			$output = 'Unable to calculate a route';
		}
		else
		{
			$output = "An unexpected error occured in Gosmore:\n".print_r($output);
		}	
	}
	else if (count($output) == 0)
	{
		$output = "An unexpected error occured in Gosmore:\n".$result;
	}
	
}

// Return the result
echo $output;

// Do some housekeeping (update logfiles)

function getDistance($latitudeFrom, $longitudeFrom,
    $latituteTo, $longitudeTo)
{
    // 1 degree equals 0.017453292519943 radius
    $degreeRadius = deg2rad(1);
 
    // convert longitude and latitude values to radians before calculation
    $latitudeFrom  *= $degreeRadius;
    $longitudeFrom *= $degreeRadius;
    $latituteTo    *= $degreeRadius;
    $longitudeTo   *= $degreeRadius;
 
    // apply the Great Circle Distance Formula
    $d = sin($latitudeFrom) * sin($latituteTo) + cos($latitudeFrom)
       * cos($latituteTo) * cos($longitudeFrom - $longitudeTo);
 
    return (6371.0 * acos($d));
}

function getProcesses()
{
	$nProcesses = 0;
	
	exec("ps ax | grep gosmore", $ps, $return_var);

	foreach ($ps as $row => $process)
	{
		$properties = array();
		$properties = split(" ", $process);
		
		foreach ($properties as $item => $property)
		{
			//echo "property ".$item." = ".$property."\n";
			if (strcmp(trim($property), "./gosmore") == 0)
			{
				$nProcesses++;
				break;
			}
		}
	}
	return $nProcesses;
}

function asKML($elements, $distance, $instructions) {
	// meta data
	header('Content-Type: text/xml; charset=utf-8');

	// KML body
	$kml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$kml .= '<kml xmlns="http://earth.google.com/kml/2.0">'."\n";
	$kml .= '  <Document>'."\n";	$kml .= '    <name>KML Samples</name>'."\n";
	$kml .= '    <open>1</open>'."\n";
	$kml .= '    <distance>'.$distance.'</distance>'."\n";
	$kml .= '    <description>'.$instructions.'</description>'."\n";
	$kml .= '    <Folder>'."\n";
	$kml .= '      <name>Paths</name>'."\n";
	$kml .= '      <visibility>0</visibility>'."\n";
	$kml .= '      <description>Examples of paths.</description>'."\n";
	$kml .= '      <Placemark>'."\n";
	$kml .= '        <name>Tessellated</name>'."\n";
	$kml .= '        <visibility>0</visibility>'."\n";
	$kml .= '        <description><![CDATA[If the <tessellate> tag has a value of 1, the line will contour to the underlying terrain]]></description>'."\n";
	$kml .= '        <LineString>'."\n";
	$kml .= '          <tessellate>1</tessellate>'."\n";
	$kml .= '          <coordinates> ';
	foreach($elements as $element) {
		$kml .= $element["lon"].",".$element["lat"]."\n";
	}
	$kml .= '          </coordinates>'."\n";
	$kml .= '        </LineString>'."\n";
	$kml .= '      </Placemark>'."\n";
	$kml .= '    </Folder>'."\n";
	$kml .= '  </Document>'."\n";
	$kml .= '</kml>'."\n";
	return $kml;
}
function asGeoJSON($elements, $distance, $instructions) {
	// meta data
	header('Content-Type: application/json; charset=utf-8');

	$geoJSON = isset ($_GET['json_callback']) ? $_GET['json_callback']."({\n" : "{\n";
	$geoJSON .= "  \"type\": \"LineString\",\n";
	$geoJSON .= "  \"crs\": {\n";
	$geoJSON .= "    \"type\": \"name\",\n";
	$geoJSON .= "    \"properties\": {\n";
	$geoJSON .= "      \"name\": \"urn:ogc:def:crs:OGC:1.3:CRS84\"\n";
	$geoJSON .= "    }\n";
	$geoJSON .= "  },\n";
	$geoJSON .= "  \"coordinates\":\n";
	$geoJSON .= "  [\n";
	$sep = "";
	foreach($elements as $element) {
		$geoJSON .= $sep."[".$element["lon"].", ".$element["lat"]."]\n";
		$sep = ",";
	}
	$geoJSON .= "  ],";
	$geoJSON .= "  \"properties\": {\n";
	$geoJSON .= "    \"distance\": \"".$distance."\",\n";
	$geoJSON .= "    \"description\": \"".$instructions."\"\n";
	$geoJSON .= "    }\n";
	$geoJSON .= isset ($_GET['json_callback']) ? "});\n" : "}\n";
	return $geoJSON;
}

