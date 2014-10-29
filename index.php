<?php
/*
 * Earthquake/index.php
 * Copyright (C) 2011, 2012 Shaun Landis
 */

$database = new PDO('sqlite:usgs.db') or die("No PDO!");

$mdata = array();
$ttdata = array();

$result = null;
$row = null;
$divs = "";
  
$query = $database->query("SELECT * FROM datum ORDER BY timestamp DESC LIMIT 50");
$result = $query->fetchAll(PDO::FETCH_OBJ);

foreach ($result as $row) {
    array_push($mdata, $row->magnitude);
    array_push($ttdata, "'id:tooltip_".$row->eqid."'");
    $divs .= "<div id=\"tooltip_".$row->eqid."\">Magnitude ".$row->magnitude."<br />".date('l jS \of F Y h:i:s A', $row->timestamp)."<br />".$row->region."<br />".$row->latitude.", ".$row->longitude."</div>\n";
}

$db = NULL;
?>
<html>
<head>
<script src="packages/RGraph.common.core.js" ></script> 
<script src="packages/RGraph.line.js" ></script>
<script src="packages/RGraph.common.tooltips.js"></script>
</head>
<body>
<canvas id="MyGraph" width="720" height="500">[Your browser does not support canvases!]</canvas>
<script type="text/javascript">
window.onload = function() {
  graph = new RGraph.Line('MyGraph', [<?php echo implode(',', $mdata); ?>]);
  graph.Set('chart.title', '50 Most Recent Earthquakes');
  graph.Set('chart.colors', ['red', 'green', 'blue']);
  graph.Set('chart.linewidth', 1);
  graph.Set('chart.gutter', 45);
  graph.Set('chart.background.grid.autofit', true);
  graph.Set('chart.background.grid', true);
  graph.Set('chart.noendxtick', true);
  graph.Set('chart.yaxispos', 'right');
  graph.Set('chart.ymax', 10.0);
//  graph.Set('chart.units.post', 'Mag');
//  graph.Set('chart.tickmarks', ['circle']);
  graph.Set('chart.key', ['Magnitude']);
  graph.Set('chart.key.background', 'rgba(255,255,255,0.7)');
  graph.Set('chart.key.position.x', 5);
  graph.Set('chart.key.position.y', 5);
  graph.Set('chart.key.shadow', true);
  graph.Set('chart.tooltips', [<?php echo implode(',', $ttdata); ?>]);
  graph.Set('chart.tooltips.effect', 'contract');
  graph.Draw();
}

</script>
<div style="display:none">
<?php echo $divs; ?>
</div>
</body>
</html>
