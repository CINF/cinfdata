<?php
// Modify the comment associated with measurement identified by a timestamp
include("../common_functions_v2.php");
include("graphsettings.php");
$type = "";
$settings = plot_settings($type, $params="", $ignore_invalid_type=True);
$user = $settings["sql_username"];
// Get password to update SQL table
$pass = isset($_POST["password"]) ? $_POST["password"] : "";
try {
    $db = specific_db($user, $pass);
} catch (PDOException $e) {
    echo html_header();
    echo('Enter password for user: '.$user);
    echo('<form action="modify_comment.php" method="post">');
    echo('<input type="password" name="password">');
    echo('<input type="submit" value="Submit password">');
    echo(html_footer());
    return "";
}
?>

<?php echo html_header()?>

<?php
if (!empty($_GET["time"])){
    $timestamp = $_GET["time"];
    $comment = $_GET["comment"];
    $query = "SELECT id FROM " . $settings["measurements_table"] . " WHERE time=\"$timestamp\"";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $i = 0;
    $query = "UPDATE " . $settings["measurements_table"] . " SET comment=\"$comment\", time=time WHERE id=:id";
    $stmt = $db->prepare($query);
    foreach ($result as $row) {
        $ret = $stmt->execute($row);
        if ($ret) {
            $valid = $i++;
        } else {
            echo("SQL update failed<br>");
        }
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
foreach ($db->query($query) as $row) {
    print($row["time"] . " - " . $row["comment"] .  "<br>");
}
?>



<?php echo html_footer()?>
