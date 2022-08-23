<?php include 'config.php' ?>
<?php include 'functions.php' ?>
<?php
print_r($post);exit;
if ($_POST && strlen($_POST['date'] > 0) && strlen($_POST['time'] > 0) && strlen($_POST['level'] > 0)){
	logLevel($link, $_POST['date'], $_POST['time'], $_POST['level']);
}

?>