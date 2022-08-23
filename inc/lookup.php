<?php include 'config.php' ?>
<?php
$sql = "select 
	/*l.testdate Date,
	l.testtime Time,
	*/
	l.sugarlevel Level
 from levels_log l
	where id = ?";
	if($stmt = mysqli_prepare($link, $sql)){
			mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
			if(mysqli_stmt_execute($stmt)){
				$result = mysqli_stmt_get_result($stmt);
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
					print json_encode($row);
					
				}
		}
	}
	?>