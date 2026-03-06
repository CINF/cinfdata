<?php
include("../common_functions_v2.php");
echo(html_header());

$id = intval($_GET["id"] ?? 0);
$action = trim(strtolower(htmlspecialchars($_GET["action"] ?? '')));

$db = std_db($user='alarm_user');


/** Produces the HTML for the existing purchase_reminders */
function existing_purchase_reminders(){
  global $db;

  # Get the alarms
  $query = "SELECT id, description FROM alarm WHERE description like \"[purchase_reminders]%\"";
  $result = $db->query($query);

  # Start the table
  echo("<div style=\"width:45%;float:left\">");
  echo("<h1><a id=\"existing\"></a>Existing ordering items</h1>\n");
  echo("<table border=\"1\" class=\"nicetable\">\n");
  echo("\n<tr>\n");
  echo("<th>ID</th>\n<th>Description</th>\n<th>Value</th>\n<th colspan=3>Actions</th>\n");
  echo("</tr>");

  # Loop over alarms
  while($row = $result->fetch()) {

    $query = "select unix_timestamp(time), value from dateplots_purchase_reminders where type=" . $row[0] . " order by id desc limit 1";
    // $latest_time = single_sql_value($db, $query, 0); Not really needed for now
    $latest_value = single_sql_value($db, $query, 1);

    echo("\n\n<tr>\n");
    echo("<td>{$row[0]}</td>\n");
    echo("<td>{$row[1]}</td>\n");
    echo("<td>$latest_value</td>\n");
    echo("<td><a href=\"order.php?id=" . $row[0] . "&action=qr\">QR</a></td>\n");
    echo("<td><a href=\"order.php?id=" . $row[0] . "&action=reset\">Reset</a></td>\n");
    echo("<td><a href=\"order.php?id=" . $row[0] . "\">Order</a></td>\n");
    echo("</tr>");
  }

  # End the table
  echo("\n\n</table>\n");
  echo("</div>\n");
}

function update_value_in_purchase_reminders($value){
  global $db;
  global $id;
  $query = "INSERT INTO dateplots_purchase_reminders (type, value) values (:i, :v)";
  $stmt = $db->prepare($query);
  $stmt->bindValue(':i', $id);
  $stmt->bindValue(':v', $value);
  $stmt->execute();
}

// Handle the given action

if (($action === '') && ($id > 0)){
  $query = "select unix_timestamp(time), value from dateplots_purchase_reminders where type=" . $id . " order by id desc limit 1";
  // $latest_time = single_sql_value($db, $query, 0); Not really needed for now
  $latest_value = single_sql_value($db, $query, 1);
  update_value_in_purchase_reminders($latest_value + 1);
  echo("<h1>Thank you for your notifying</h1>\n");
}

if ($action === 'reset'){
  update_value_in_purchase_reminders(0);
  echo("<h1>Thank you for ordering supplies</h1>\n");
}

if ($action=='qr'){
  ob_start();
  $url = $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  $command = './qr_generator.py ' . $id . ' ' . $url;
  passthru($command, $return_code);
  $content_grabbed=ob_get_contents();
  ob_end_clean();
  echo($content_grabbed);

  echo("<h1>QR for item {$id}</h1>\n");
  echo("<img src=\"../figures/qr_{$id}.png\">\n");
}

// Print the overview
existing_purchase_reminders();

echo("\n\n\n");
echo(html_footer());

?>
