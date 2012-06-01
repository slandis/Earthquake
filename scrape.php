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
 */

function processData($data) {
	$database = new PDO("sqlite:usgs.db") or die("Unable to access database");
	$prep = $database->prepare("REPLACE INTO datum (source, eqid, timestamp, latitude, longitude, magnitude, depth, region) VALUES(:source,:eqid,:timestamp,:latitude,:longitude,:magnitude,:depth,:region)");
	
	/* First row is always headers */
	array_pop($data);

	foreach ($data as $row) {
		$p = str_getcsv($row);
		$prep->bindParam(":source", $p[0]);
		$prep->bindParam(":eqid", $p[1]);
		$prep->bindParam(":timestamp", strtotime($p[3]));
		$prep->bindParam(":latitude", $p[4]);
		$prep->bindParam(":longitude", $p[5]);
		$prep->bindParam(":magnitude", $p[6]);
		$prep->bindParam(":depth", $p[7]);
		$prep->bindParam(":region", $p[9]);
		$prep->execute();
	}

	unset($prep);
	unset($database);
}
?>
