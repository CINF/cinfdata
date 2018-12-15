<?php
#include("graphsettings.php");
include("../common_functions_v2.php");
$dbi = std_dbi();


$user_id = isset($_GET["user_id"]) ? $_GET["user_id"] : null;

echo(html_header($root="../", $title="Friday Bar User Information Page"));
if ($user_id === null){
  display_welcome();
} else {
  display_user($user_id);
}
echo(html_footer());



function display_welcome(){
  echo("<h2>Welcome</h2>\n");
  echo("<p>Please enter your user ID and hit Go!</p>\n");
  echo("<form>\n");
  echo("<input type=\"text\" name=\"user_id\"><br>");
  echo("<input type=\"submit\" value=\"Go!\">");
}

function display_user($user_id){
  echo("<p>Please enter your user ID and hit Go!</p>\n");
  echo("<form>\n");
  echo("<input type=\"text\" name=\"user_id\"><br>");
  echo("<input type=\"submit\" value=\"Go!\">");

  echo("<h2>Status for $user_id</h2>");

  global $dbi;
  $stmt = $dbi->prepare("select sum(amount) from fridays_transactions " . 
			"where user_id = (select id from fridays_user where " . 
			"user_barcode = ?)");
  $stmt->bind_param("s", $user_id);
  $stmt->execute();

  $stmt->bind_result($result);
  $stmt->fetch();
  $stmt->close();
  if ($result === null){
    echo("<p>No information found for user: " . $user_id . "</p>\n");
  } else {
    echo("<p><b>Balance: " . $result . " kr.</b></p>\n");
    echo("<h2>History</h2>\n");
    

    $stmt = $dbi->prepare("select time, amount, item_barcode from " . 
			  "fridays_transactions where user_id = (select id from " . 
			  "fridays_user where user_barcode = ?) order " . 
			  "by id desc");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $stmt->bind_result($time, $amount, $item_barcode);
    $stmt->store_result();
    echo("<table class=\"nicetable\">");
    echo("<tr><th>Date time</th><th>Amount</th><th>Item barcode</th></tr>");
    while ($stmt->fetch()){
      #print_r(type($row));
      echo("<tr><td>$time</td><td>$amount</td><td>$item_barcode</td></tr>");
    }
    echo("</table>");

  }
  $stmt->close();

  #$result = $dbi->query($query);

}


/*
function items_from_query($query, $type="int"){
  global $dbi;
  $result = $dbi->query($query);
  $data = Array();
  while($row = $result->fetch_row()) {
    if ($type == "int"){
      $data[] = Array($row[0], (int) $row[1]);
    } else {
      $data[] = Array($row[0], (float) $row[1]);
    }
  }
  return $data;
}  


echo(html_header());

echo("<h1>Items</h1>\n");
$query = "" . 
  "select fridays_items.name, count(fridays_transactions.id) as item_count " .
  "from fridays_transactions " .
  "INNER JOIN fridays_items " .
  "ON fridays_transactions.item_barcode=fridays_items.barcode " .
  "where fridays_transactions.user_id < 999999 and " .
  "fridays_transactions.item_barcode IS NOT NULL " .
  "group by fridays_transactions.item_barcode " . 
  "order by item_count desc, fridays_transactions.id desc;";
$pie_info = Array("title" => 'Top 10 items');
$pie_info['data'] = items_from_query($query);
$pie_json = JSON_encode($pie_info);
echo('<img alt="Vertical bar chart" class="centered" width="800" height="500" src="pie.php?data=' . urlencode($pie_json) . '&element_count=10"/>' . "\n");


echo("<h1>Revenue by item</h1>\n");

$query = "" .
  "select fridays_items.name, -sum(fridays_transactions.amount), " .
  "count(fridays_transactions.id) as item_count " .
  "from fridays_transactions " .
  "INNER JOIN fridays_items " .
  "ON fridays_transactions.item_barcode=fridays_items.barcode " .
  "where fridays_transactions.user_id < 999999 and fridays_transactions.item_barcode IS NOT NULL group by fridays_transactions.item_barcode order by item_count desc, fridays_transactions.id desc;";
$pie_info = Array("title" => 'Top 10 revenue by item');
$pie_info['data'] = items_from_query($query);
$pie_json = JSON_encode($pie_info);

echo('<img alt="Vertical bar chart" class="centered" width="800" height="500" src="pie.php?data=' . urlencode($pie_json) . '&element_count=10"/>' . "\n");

echo("<h1>Volume by item</h1>\n");
$query = "" . 
  "select fridays_items.name, count(fridays_transactions.id) * fridays_items.volume as item_count " .
  "from fridays_transactions " .
  "INNER JOIN fridays_items " .
  "ON fridays_transactions.item_barcode=fridays_items.barcode " .
  "where fridays_transactions.user_id < 999999 and " .
  "fridays_transactions.item_barcode IS NOT NULL " .
  "group by fridays_transactions.item_barcode " . 
  "order by item_count desc, fridays_transactions.id desc;";
$pie_info = Array("title" => 'Top 10 volume by item');
$pie_info['data'] = items_from_query($query, $type='float');
$pie_json = JSON_encode($pie_info);
echo('<img alt="Vertical bar chart" class="centered" width="800" height="500" src="pie.php?data=' . urlencode($pie_json) . '&element_count=10&decimals=2"/>' . "\n");


?>

<script>
function checkMods(event){
  if (event.shiftKey && event.ctrlKey) {
    event.preventDefault();
    window.location = "fridays_transactions.php?count=100";
    return true;
  } else {
    return false;
  }
}
</script>

<?php
# Secret link
echo("<p style=\"text-align:right\"><a href=\"#\" onClick=\"return checkMods(event)\">&pi;</a></p>");*/

?>