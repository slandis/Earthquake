<?
  $db = new PDO('sqlite:usgsdata.db') or die("No PDO!");

  $mdata = array();
  $ttdata = array();
//  $ldata = array();
//  $odata = array();
//  $wdata = array();

  $result = null;
  $row = null;
  $divs = "";
  
  $result = $db->query("SELECT * FROM datum WHERE region like '%JAPAN' ORDER BY timestamp DESC");

  foreach ($result as $row) {
    array_push($mdata, $row['magnitude']);
    array_push($ttdata, "'id:tooltip_".$row['id']."'");
    $divs .= "<div id=\"tooltip_".$row['id'] . "\">Magnitude " . $row['magnitude'] . "<br />" .date('l jS \of F Y h:i:s A', $row['timestamp'])."<br />".$row['region']."<br />".$row['latitude'] . ", " . $row['longitude'] ."</div>\n";
//    array_push($ddata, $row['dewpointf']);
//    array_push($pdata, $row['pressurein']);
//    array_push($wdata, $row['windchillf']);
  }

  $db = NULL;
?>
<html>
<head>
<script src="js/RGraph.common.core.js" ></script> 
<script src="js/RGraph.line.js" ></script>
<script src="js/RGraph.common.tooltips.js"></script>
</head>
<body>
<canvas id="MyGraph" width="720" height="500">[Your browser does not support canvases!]</canvas>
<script type="text/javascript">
window.onload = function() {
  graph = new RGraph.Line('MyGraph', [<? echo implode(',', $mdata); ?>]);
  graph.Set('chart.title', 'Recent Earthquakes - Japan');
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
  graph.Set('chart.tooltips', [<? echo implode(',', $ttdata); ?>]);
  graph.Set('chart.tooltips.effect', 'contract');
  graph.Draw();
}

</script>
<div style="display:none">
<? echo $divs; ?>
</div>
</body>
</html>