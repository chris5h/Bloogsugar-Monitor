<?php include 'inc/config.php'; ?>
<?php include 'inc/head.php'; ?>
<?php include 'inc/functions.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if ($_POST && strlen($_POST['date']) > 0 && strlen($_POST['time']) > 0 && strlen($_POST['level'] > 0)){
	$x = logLevel($_POST['date'], $_POST['time'], $_POST['level']);
}
$last = LastTest();
$banner = "Your last test was <b>{$last['sugarlevel']}</b> on <b>".date('m/d/y',strtotime($last['testdate']))."</b> at <b>".date('g:i A',strtotime($last['testtime']))."</b>";
$ten = array_reverse(lastTen());
?>
<form method=post>
<table class="table table-striped table-bordered table-condensed">
<tr>
	<td colspan=2><?php echo $banner?></td>
</tr>
<tr>
	<th style="vertical-align: middle;"><label>Date</label></th>
	<td style="vertical-align: middle;"><input class="form-control" type=date name=date value="<?php echo date('Y-m-d'); ?>" required></td>
</tr>
<tr>
	<th style="vertical-align: middle;"><label>Time</label></th>
	<td style="vertical-align: middle;"><input class="form-control" type=time name=time value="<?php echo date('H:m'); ?>" required></td>
</tr>
<tr>
	<th style="vertical-align: middle;"><label>Sugar Level</label></th>
	<td style="vertical-align: middle;"><input class="form-control" type=number name=level value="" required></td>
</tr>
<tr>
	<td colspan=2 style="white-space: nowrap;">
	<input type="submit" class="btn btn-primary btn-lg" value="Submit" style="width: 40%;">&nbsp&nbsp<input type="reset" class="btn btn-danger btn-lg" value="Reset" style="width: 40%;">
	</td>
</tr>
<tr>
	<th style="vertical-align: middle;" colspan=2><label>Last 10 Entries</label></th>
</tr>
<tr>
	<td colspan=2 style="white-space: nowrap; padding:0; margin:0;">
		<table class="table table-striped table-bordered table-condensed" style="white-space: nowrap; padding:0; margin:0;" >
			<tr>
				<th>Date</th>
				<th>Time</th>
				<th>Level</th>
			</tr>
		<?php
		foreach ($ten as $key => $values){
			if ($values['change'] == 'up'){
				$sug = "<strong style=\"color: red;\">{$values['sugarlevel']}</strong>";
			}	else if ($values['change'] == 'down'){
				$sug = "<strong style=\"color: blue;\">{$values['sugarlevel']}</strong>";
			}	else{
				$sug = "<strong>{$values['sugarlevel']}</strong>";
			}
			?>
			<tr<?php if (isset($style)) {echo $style; unset($style);} ?>>
				<td><?php echo date('m/d/y', strtotime($values['testdate'])); ?></td>
				<td><?php echo date('h:i A',strtotime($values['testtime'])) ?></td>
				<td><?php echo $sug; ?></td>
			</tr>
		<?php
		$sug = '';
		}
		?>
</table>
<br>
</body>
</html>