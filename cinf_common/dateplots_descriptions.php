<?php

include("graphsettings.php");
include("../common_functions_v2.php");
$db = std_db();

echo(html_header());
?>

<script src="https://cdn.tutorialjinni.com/sortable/0.8.0/js/sortable.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.tutorialjinni.com/sortable/0.8.0/css/sortable-theme-finder.min.css" />

<?php

echo("\n\n");

echo("<h1>Dateplot Descriptions</h1>\n");
echo("<table class=\"sortable-theme-finder\" data-sortable>\n");
echo("<thead>\n");
echo("<th>id</th><th>Codename</th><th>Description</th>\n");
echo("</thead>\n\n");
echo("<tbody>\n");

$query = "select id, codename, description from dateplots_descriptions order by id";
$stmt = $db->prepare($query);
$stmt->execute();
$stmt = $db->query($query);
while ($row = $stmt->fetch()){
  echo("<tr>");
  echo('<td>' . $row[0] . '</td>');
  echo('<td>' . $row[1] . '</td>');
  echo('<td>' . $row[2] . '</td>');
  echo("</tr>\n");
}
echo("</tbody></table>");


echo("</pre>");

echo("\n\n");
echo(html_footer());
?>