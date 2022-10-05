<?php
include("../common_functions_v2.php");
$db = std_db();

echo(html_header());
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
    $url = "https://";
} else {
    $url = "http://";
}
$url.= $_SERVER['HTTP_HOST'];
$url.= $_SERVER['REQUEST_URI'];

$query = "SELECT id,url,comment FROM short_links ORDER BY ID DESC"; 

echo("<table border='1' class=\"links\">"); 
echo("<tr><td><b>Id</b></td><td><b>Comment</b></td><td><b>Link</b></td></tr>"); 
foreach ($db->query($query) as $row){
  echo("<tr><td>"); 
  echo($row['id']); 
  echo("</td><td>"); 
  echo($row['comment']);
  echo("</td><td>");
  echo("<a href=\"link.php?id=" . $row['id'] . "\">" . $url . "?id=" . $row['id'] . "</a>");
  echo("</td></tr>");
}
echo("</table>");

echo(html_footer());
?>
