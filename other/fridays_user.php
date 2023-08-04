<?php
include("../common_functions_v2.php");
$db = std_db();


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

  global $db;
  $stmt = $db->prepare("select sum(amount) from fridays_transactions " .
			"where user_id = (select id from fridays_user where " .
			"user_barcode = ?)");
  $stmt->bindParam(1, $user_id, PDO::PARAM_STR);
  $stmt->execute();

  $result = $stmt->fetch();
  $result = $result[0];
  if ($result === null){
    echo("<p>No information found for user: " . $user_id . "</p>\n");
  } else {
    echo("<p><b>Balance: " . $result . " kr.</b></p>\n");
    echo("<h2>History</h2>\n");

    $stmt = $db->prepare("select time, amount, item_barcode from " .
			  "fridays_transactions where user_id = (select id from " .
			  "fridays_user where user_barcode = ?) order " .
			  "by id desc");
    $stmt->bindParam(1, $user_id, PDO::PARAM_STR);
    $stmt->execute();

    echo("<table class=\"nicetable\">");
    echo("<tr><th>Date time</th><th>Amount</th><th>Item barcode</th></tr>");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      $time = $row['time'];
      $amount = $row['amount'];
      $item_barcode = $row['item_barcode'];
      echo("<tr><td>$time</td><td>$amount</td><td>$item_barcode</td></tr>");
    }
    echo("</table>");

  }
  $stmt->closeCursor();
}
/*
# Secret link
echo("<p style=\"text-align:right\"><a href=\"#\" onClick=\"return checkMods(event)\">&pi;</a></p>");*/

?>