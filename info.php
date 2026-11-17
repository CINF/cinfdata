<?php
include("common_functions_v2.php");
echo(html_header($root="/", $page_title="PHP Info", $includehead="", $charset="UTF-8"));
echo(phpinfo());
echo(html_footer());
?>
