<?php
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

<script src="https://cdn.tutorialjinni.com/sortable/0.8.0/js/sortable.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.tutorialjinni.com/sortable/0.8.0/css/sortable-theme-finder.min.css" />

<style>
.down {
background: #ff0000;
width: 15px;
height: 15px;
border-radius: 50%;
}
</style>

<style>
.up {
background: #00ff00;
width: 15px;
height: 15px;
border-radius: 50%;
}​
</style>

</head>

<body>
<?php
echo("<table class=\"sortable-theme-finder\" data-sortable>\n");
echo("<thead>");
echo("<th>&nbsp;</th><th>Hostname</th><th>Uptime</th><th>Load</th><th>Setup</th><th>Description</th><th>Apt</th><th>Git</th><th>Temp.</th><th>Python</th><th>Model</th>");
echo("</thead><tbody>");

for ($i=0; $i<sizeof($data); $i++){
  echo("<tr>");
  $color = ($data[$i]['up_or_down'] == 0) ? "\"#FF0000\"" : "\"#00FF00\"";
  $value = ($data[$i]['up_or_down'] == 0) ? "0" : "1";

  echo("<td data-value=\"" . $value . "\"><font color=" . $color . ">&#8226;</font></td>");
  if (substr($data[$i]['hostname'], 0, 6) == 'rasppi'){
    $sortval = (substr($data['hostname'], 6));
  }
  else{
    $sortval = 1000;
  }
  $sort_pos = preg_replace('/\D/', '', $data[$i]['hostname']);
  if ($sort_pos == ''){
      $sort_pos = '99999';
  }
  echo("<td data-value=\"" . $sort_pos . "\">" . $data[$i]['hostname'] . "</td>");
  if ($data[$i]['up_or_down'] == false){
    echo("<td colspan=2><b>Host is down</b></td>");
  }else{
       echo("<td>" . $data[$i]['up'] . "</td><td>" . $data[$i]['load'] . "</td>");
  }
  echo("<td>" . $data[$i]['location'] . "</td>");
  echo("<td>" . $data[$i]['purpose'] . "</td>");
  echo("<td>" . $data[$i]['apt_up'] . "</td>");
  echo("<td>" . $data[$i]['git'] . "</td>");
  echo("<td>" . $data[$i]['host_temperature'] . "</td>");
  echo("<td>" . $data[$i]['python_version'] . "</td>");
  echo("<td>" . $data[$i]['model'] . "</td>");
  #if (strpos($row[9], '(') == True){
  #  echo("<td>" . substr($row[9],0, strpos($row[9], '(')) . "</td>");
  #  }
  #  else{
  #echo("<td>" . $row[9] . "</td>");
  #  }
  echo("</tr>\n");
}
echo("</tbody></table>");
?>
</body>
</html>
