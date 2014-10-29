<?
/*
 * Earthquake/scrape.php
 * Copyright (C) 2012 Shaun Landis
 */

/* Prevents errors from strtotime */
date_default_timezone_set('America/Los_Angeles');

processData(array_reverse(file('http://earthquake.usgs.gov/earthquakes/feed/csv/1.0/week')));
processData(array_reverse(file('http://earthquake.usgs.gov/earthquakes/feed/csv/2.5/week')));
processData(array_reverse(file('http://earthquake.usgs.gov/earthquakes/feed/csv/4.5/week')));

unset($database);

/*
 * Row schema
 * source, eqid, version, timestamp, latitude, longitude, magnitude, depth, nst, region
 *
 * As of 10/28/14, the USGS has changed the layout of the CSV data source
 * it now reflects the following:
 * DateTime,Latitude,Longitude,Depth,Magnitude,MagType,NbStations,Gap,Distance,RMS,Source,EventID,Version
 *
 */

function processData($data) {
	$database = new PDO("sqlite:usgs.db") or die("Unable to access database");
	$prep = $database->prepare("REPLACE INTO datum (source, eqid, timestamp, latitude, longitude, magnitude, depth, region) VALUES(:source,:eqid,:timestamp,:latitude,:longitude,:magnitude,:depth,:region)");
	
	/* First row is always headers */
	array_pop($data);

	foreach ($data as $row) {
		$p = str_getcsv($row);
		$prep->bindParam(":source", $p[10]);
		$prep->bindParam(":eqid", $p[11]);
		$prep->bindParam(":timestamp", strtotime($p[0]));
		$prep->bindParam(":latitude", $p[1]);
		$prep->bindParam(":longitude", $p[2]);
		$prep->bindParam(":magnitude", $p[4]);
		$prep->bindParam(":depth", $p[3]);
		$prep->bindParam(":region", $p[9]);
		$prep->execute();
	}

	unset($prep);
	unset($database);
}
?>
