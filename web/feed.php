<?php
require_once 'navigation.php';
include_once("inc/global.inc.php");
global $conn;
?>

<link type="text/css" rel="stylesheet" href="css/feed.css" />

<script type="text/javascript">
    function openurl(url) {
        location.href = url;
    }
</script>

<?php
    require("feed_func.php");
?>

<div style="clear:both"></div>
<?
require_once 'footer.php';
?>
