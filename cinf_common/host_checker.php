<?php
# This page presents the host status of devices as gathered by https://github.com/CINF/machines/rasppi42/host_status.py
include("../common_functions_v2.php");
$db = std_db();

$query = 'select id, time, host, port, location, purpose, attr from host_checker';
$stmt  = $db->prepare($query);
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_BOTH)){
  if (sizeof($row["attr"]) > 0){
    $data[] = json_decode($row["attr"], True);
  }
  else{
    $data[] = array("hostname" => $row["host"], "location" => "<i>" . $row["location"] . "</i>");
  }
}
?>
<html>
<head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sortable/0.8.0/js/sortable.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sortable/0.8.0/css/sortable-theme-finder.min.css" />

</head>

<body>
<?php
echo("<table class=\"sortable-theme-finder\" data-sortable>\n");
echo("<thead>");
echo("<th>&nbsp;</th><th>Hostname</th><th>Port</th><th>Uptime</th><th>Load</th><th>Setup</th><th>Description</th><th>Apt</th><th>Git PyExpLabSys</th><th>Git machines</th><th>Temperature</th><th>Python</th><th>OS</th><th>Model</th><th>Last check</th>");
echo("</thead><tbody>");

for ($i=0; $i<sizeof($data); $i++){
  echo("<tr>");
  $color = ($data[$i]['up_or_down'] == 0) ? "\"#FF0000\"" : "\"#00FF00\"";
  $value = ($data[$i]['up_or_down'] == 0) ? "0" : "1";

  echo("<td data-value=\"" . $value . "\"><font color=" . $color . ">&#8226;</font></td>");
  # Hostname
  $sort_pos = preg_replace('/\D/', '', $data[$i]['hostname']);
  if ($sort_pos == ''){
      $sort_pos = '99999';
  }
  echo("<td data-value=\"" . $sort_pos . "\">" . $data[$i]['hostname'] . "</td>");
  echo("<td>" . $data[$i]['port'] . "</td>");
  # Uptime / load
  if ($data[$i]['up_or_down'] == false){
    #echo("<td colspan=2><b>Host is down</b></td>"); # colspan=2 interferes with sortable
    echo("<td><b>Host is</b></td>");
    echo("<td><b>down</b></td>");
  }else{
       echo("<td>" . $data[$i]['up'] . "</td><td>" . $data[$i]['load'] . "</td>");
  }
  # Other attributes
  echo("<td>" . $data[$i]['location'] . "</td>");
  echo("<td>" . $data[$i]['purpose'] . "</td>");
  echo("<td>" . $data[$i]['apt_up'] . "</td>");
  echo("<td>" . $data[$i]['git_pyexplabsys'] . "</td>");
  echo("<td>" . $data[$i]['git_machines'] . "</td>");
  echo("<td>" . $data[$i]['host_temperature'] . "</td>");
  echo("<td>" . $data[$i]['python_version'] . "</td>");
  # OS version
  $version_number = substr($data[$i]['os_version'], 0, strpos($data[$i]['os_version'], ' '));
  if (strlen($data[$i]['os_version']) == 0){
      $sortval = 1000;
  }else{
      if ($data[$i]['os_version'] == 'Unavailable'){
          $sortval = 10000;
      }else{
          $sortval = intval($version_number);
      }
  }
  echo("<td data-value=\"" . $sortval . "\">" . $data[$i]['os_version'] . "</td>");
  # Model
  echo("<td>" . $data[$i]['model'] . "</td>");
  # Last access
  echo("<td>" . $data[$i]['last_accessed'] . "</td>");
  # End-of-row
  echo("</tr>\n");
}
echo("</tbody></table>");
?>
</body>
</html>
