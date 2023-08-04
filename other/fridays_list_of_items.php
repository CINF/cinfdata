<?php
include("../common_functions_v2.php");
$db = std_db();

echo(html_header());


# Start table
echo("<table class=\"nicetable\">\n" .
     "<tr>");
$headers = Array("Name", "Alc. [%]", "Volume [L]", "Brewery", "Price [DKK]");
foreach($headers as $header){
  echo("<th>" . $header . "</th>");
}
echo("</tr>");

# Get items from database
$query = "select name, alc, volume, brewery, price from fridays_items order by name";
$result = $db->query($query);

# Fill in rows with items
$color = false;
while($row = $result->fetch(PDO::FETCH_NUM)){
  if ($color){
    echo("<tr bgcolor=\"#EEEEEE\">\n");
    $color = false;
  } else {
    echo("<tr>\n");
    $color = true;
  }
  foreach($row as $key=>$item){
    if ($key == 4){
      echo("<td><b>" . htmlentities($item) . "</b></td>");
    } else {
      echo("<td>" . htmlentities($item) . "</td>");
      }
  }
  echo("</tr>\n");
}

# End table
echo("</table>");

echo(html_footer());
?>
