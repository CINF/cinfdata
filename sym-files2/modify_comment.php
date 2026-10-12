<?php
// Modify comment in measurement table
include(dirname(__DIR__) . "/common_functions_v2.php");
include("graphsettings.php");
$type = "";
$settings = plot_settings($type, $params="", $ignore_invalid_type=True);
$db = setup_db($settings["chamber_name"]);
?>

<?php echo html_header()?>
  
<?php
if (!empty($_GET["time"])){
    $timestamp = $_GET["time"];
    $comment = $_GET["comment"];
    $query = "select id from " . $settings["measurements_table"] . " where time = \"" . $timestamp . "\"";
    $stmt = $db->prepare($query);
    $i = 0;
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach ($result as $row){
        $query = "update ". $settings["measurements_table"] . " set comment = \"" . $comment . "\", time = time where id = " . $row['id'];
        $stmt = $db->prepare($query);
        $stmt->execute();
        $valid = $i++;
    }
    if ($i==0){
        echo("<b>Not a valid timestamp</b>");
    }
    else{
        echo("<b>Updated " . $i . " measurement rows</b>");
    }
}
?>


<form action="modify_comment.php" method="get">
Select timestamp to modify<br>
<input name="time" type="text" size="13"><br>
New comment:<br>
<input name="comment" type="text" size="100"><br>
<input type="submit" value="Engage"><br>

<?php
$query = "select distinct time, comment from " . $settings["measurements_table"] . " order by time desc";
$result  = $db->query($query, PDO::FETCH_ASSOC);
foreach ($result as $row){
    print($row['time'] . " - " . $row['comment'] .  "<br>");
}
?>



<?php echo new_html_footer()?>
