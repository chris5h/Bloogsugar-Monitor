<?php include 'inc/config.php'; ?>
<?php include 'inc/head.php'; ?>
<?php include 'inc/functions.php'; ?>
<?php
if ($_GET){
	if (strlen($_GET['id']) > 0){
		delTest($link, $_GET['id']);
		
	}
}
if ($_POST && $_POST['action'] == 'edit' && strlen($_POST['date']) > 0 && strlen($_POST['time']) > 0 && strlen($_POST['id']) > 0 && strlen($_POST['level']) > 0){
	updateLevel($_POST['id'], $_POST['date'], $_POST['time'], $_POST['level']);
}	else if ($_POST && $_POST['action'] == 'del' && strlen($_POST['id']) > 0){
	delTest($_POST['id']);
}	else if ($_POST && strlen($_POST['start']) > 0 && strlen($_POST['end']) > 0){
	$history = array_reverse(History($link, $_POST['start'], $_POST['end']));
}	else	{
	$history = array_reverse(History($link, 'all', 'all'));
}
?>
<form method=post>
<table id="maintable" class="table table-striped table-bordered table-condensed">
	<tr>
		<td colspan=4>
		<div id="searchbar" style="display: none;">
		<table class="table table-striped table-bordered table-condensed">
			<tr>
				<th style="vertical-align: middle;"><label>Beginning</label></th>
				<td style="vertical-align: middle;"><input class="form-control" type=date name=start value="<?php echo date('Y-m-d',strtotime('2 days ago')); ?>" required></td>
			</tr>
			<tr>
				<th style="vertical-align: middle;"><label>End</label></th>
				<td style="vertical-align: middle;"><input class="form-control" type=date name=end value="<?php echo date('Y-m-d',strtotime('yesterday')); ?>" required></td>
			</tr>
			<tr>
				<td colspan=2 style="white-space: nowrap;">
				<input type="submit" class="btn btn-primary btn-lg" value="Submit" style="width: 40%;">&nbsp&nbsp<input type="reset" class="btn btn-danger btn-lg" value="Reset" style="width: 40%;">
				</td>
			</tr>
		</table>
		</div>
		
	</form>	
		</td>
		
	</tr>
	<tr>
		<th colspan=4><A onClick="ToggleSearch();"><b><div id=toggle style="display:inline;">Show</div> Search</b></a></td>
	</tr>
	<tr>
		<th colspan=4 style="background: grey; color: white;"><b>History</b></td>
	</tr>
	<tr>
		<th id="headdate">Date</th>
		<th id="headtime">Time</th>
		<th id="headlevel">Level</th>
		<th id="headsubmit" style="color:red;">Del</th>
	</tr>
<?php
foreach ($history as $key => $values){
	if ($values['change'] == 'up'){
		$sug = "<strong style=\"color: red;\">{$values['sugarlevel']}</strong>";
	}	else if ($values['change'] == 'down'){
		$sug = "<strong style=\"color: blue;\">{$values['sugarlevel']}</strong>";
	}	else{
		$sug = "<strong>{$values['sugarlevel']}</strong>";
	}
	?>
	<form method=post><input type=hidden name=action value=del><input type=hidden name=id value="<?php echo $values['id']; ?>">
	<tr>
		<td><?php echo date('m/d/y', strtotime($values['testdate'])); ?></td>
		<td><?php echo date('h:i A',strtotime($values['testtime'])) ?></td>
		<td><?php echo $sug; ?></td>
		<td>
			<a  onclick="hideByDiv('row<?php echo $values['id'];?>')"><img src="inc/edit.png" height=25 width=25></a>
			<input type="image" src="inc/del.png" height=25 width=25 alt="Submit Form"  onclick="return confirm('Are you sure?')"/>

				
		</td>
	</tr>
	</form>
	<form method=post><input type=hidden name=action value=edit><input type=hidden name=id value="<?php echo $values['id']; ?>">	
	<tr class="editrow" id="row<?php echo $values['id'] ?>" style="display:none;">
			<td id="editdate<?php echo $values['id'] ?>"><input class="form-control" type=date name=date value="<?php echo $values['testdate'] ?>"></td>
			<td id="edittime<?php echo $values['id'] ?>"><input class="form-control" type=time name=time value="<?php echo $values['testtime'] ?>"></td>
			<td id="editlevel<?php echo $values['id'] ?>"><input class="form-control" type=number name=level value="<?php echo $values['sugarlevel'] ?>"></td>
			<td id="editsubmit<?php echo $values['id'] ?>"><input type=submit></td>
			
	</tr>
	</form>
<?php
$sug = '';
}
?>

</table>
	</td>
</tr>

</table>
<br>
