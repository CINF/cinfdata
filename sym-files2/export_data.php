<?php
include("../common_functions_v2.php");
include("graphsettings.php");

error_reporting (E_ALL ^ E_NOTICE);

# Get settings and initiate argument variables
$settings = plot_settings($_GET['type']);
$boolean_options='';

### Common
# Booleans
foreach (array('diff_left_y', 'linscale_left_y2', 'linscale_left_y1',
	       'linscale_left_y0', 'as_function_of', 'linscale_x0',
	       'linscale_x1', 'linscale_x2', 'linscale_right_y0',
	       'linscale_right_y1', 'linscale_right_y2',
	       'diff_right_y') as $value){
  $boolean_options .= ',' . $value . ':' . ($_GET[$value] ?? False);
}

# Scales
$left_yscale_bounding = $_GET['left_ymin'] . ',' . $_GET['left_ymax'];
$right_yscale_bounding = $_GET['right_ymin'] . ',' . $_GET['right_ymax'];
$xscale_bounding = ($_GET['xmin'] ?? '') . ',' . ($_GET['xmax'] ?? '');
# Plotlists
$left_plotlist = ''; $right_plotlist = '';
foreach (array('left_plotlist', 'right_plotlist') as $list){
  $$list = '';
  $plotlist = $_GET[$list] ?? array();
  if (count($plotlist) > 0){
    foreach($_GET[$list] as $id){
      $$list .= ',' . $id;
    }
  }
}

### Dateplot specific
$from_to  = ($_GET['from'] ?? '') . ',' . ($_GET['to'] ?? '');

### Plugin settings
$db = std_db();
$plugin_settings = $_GET['plugin_settings'] ?? '';
$plugin_settings_json = html_entity_decode($plugin_settings);
# Form intry in input table and get ID

$query = "INSERT INTO plot_com_in (input) values (:plugin_settings)";
$stmt = $db->prepare($query);
$stmt->bindParam (":plugin_settings", $plugin_settings_json, PDO::PARAM_STR);
$stmt->execute();
$stmt = $db->query("SELECT LAST_INSERT_ID()");
$input_id = $stmt->fetchColumn();

# Call python plot backend
$command = './export_data.py --type ' . $_GET['type'] .
  ' --boolean_options "' . $boolean_options . '"' .
  ' --left_plotlist "' . $left_plotlist . '"' .
  ' --right_plotlist "' . $right_plotlist . '"' .
  ' --xscale_bounding "' . $xscale_bounding . '"' .
  ' --left_yscale_bounding "' . $left_yscale_bounding . '"' .
  ' --right_yscale_bounding "' . $right_yscale_bounding . '"' .
  ' --from_to "' . $from_to . '"' .
  ' --input_id "' . $input_id . '"' .
  ' 2>&1';

exec($command, $command_output);

header("Content-type: text/plain");
#echo('<pre>');
for($i=0;$i<count($command_output);$i++){
 echo($command_output[$i]."\n");
}
#echo('</pre>');

?>
